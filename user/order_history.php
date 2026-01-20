<?php
// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulated logged-in user ID (replace with actual authentication)
$user_id = 1;

// Handle order deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $order_id = (int)$_POST['order_id'];
    
    // Check if order belongs to user
    $check_stmt = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
    $check_stmt->bind_param("ii", $order_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // First delete order items
            $delete_items_stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
            $delete_items_stmt->bind_param("i", $order_id);
            $delete_items_success = $delete_items_stmt->execute();
            
            if (!$delete_items_success) {
                throw new Exception("Failed to delete order items");
            }
            
            // Then delete the order
            $delete_order_stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
            $delete_order_stmt->bind_param("i", $order_id);
            $delete_order_success = $delete_order_stmt->execute();
            
            if (!$delete_order_success) {
                throw new Exception("Failed to delete order");
            }
            
            // Commit transaction
            $conn->commit();
            
            $_SESSION['message'] = "Order #$order_id has been deleted successfully.";
            $_SESSION['message_type'] = "success";
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $_SESSION['message'] = "Failed to delete order: " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
            
            // Log the error for debugging
            error_log("Order deletion failed: " . $e->getMessage());
        }
        
        // Close statements
        $delete_items_stmt->close();
        $delete_order_stmt->close();
    } else {
        $_SESSION['message'] = "Order not found or you don't have permission to delete it.";
        $_SESSION['message_type'] = "danger";
    }
    
    // Close check statement
    $check_stmt->close();
    
    // Redirect to avoid form resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Display messages if any
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Fetch all orders for this user
$orders = [];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($order = $result->fetch_assoc()) {
    // Fetch items for each order
    $items_stmt = $conn->prepare("
        SELECT oi.*, p.product_name, p.image 
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    $items_stmt->bind_param("i", $order['id']);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();
    $order['items'] = $items_result->fetch_all(MYSQLI_ASSOC);
    
    $orders[] = $order;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .order-card {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .order-header {
            padding: 1.25rem;
            background-color: #f8f9fa;
            cursor: pointer;
            border-bottom: 1px solid #dee2e6;
        }
        
        .order-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.25rem;
        }
        
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.875em;
            font-weight: 600;
        }
        
        .status-processing {
            background-color: #ffc107;
            color: #000;
        }
        
        .status-shipped {
            background-color: #0dcaf0;
            color: #000;
        }
        
        .status-delivered {
            background-color: #198754;
            color: #fff;
        }
        
        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
        }
        
        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #dee2e6;
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background-color: #0d6efd;
            border: 2px solid #fff;
        }
        
        .timeline-item.completed::before {
            background-color: #198754;
        }
        
        .timeline-item.active::before {
            background-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .timeline-item.pending::before {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include 'masterpage/header.php'; ?>

    <div class="container py-5">
        <?php if (isset($message)): ?>
            <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-history me-2"></i> Order History</h2>
                    <a href="products.php" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-bag me-2"></i> Continue Shopping
                    </a>
                </div>

                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">No Orders Found</h4>
                        <p class="text-muted">You haven't placed any orders yet.</p>
                        <a href="products.php" class="btn btn-primary mt-3">
                            <i class="fas fa-store me-2"></i> Browse Products
                        </a>
                    </div>
                <?php else: ?>
                    <div class="accordion" id="ordersAccordion">
                        <?php 
                        // Add a counter variable
                        $order_counter = 1;
                        foreach ($orders as $order): 
                            $order_total = $order['total_amount'];
                            $subtotal = $order_total + $order['discount_amount'];
                            $status_class = '';
                            switch(strtolower($order['status'])) {
                                case 'processing': $status_class = 'status-processing'; break;
                                case 'shipped': $status_class = 'status-shipped'; break;
                                case 'delivered': $status_class = 'status-delivered'; break;
                                case 'cancelled': $status_class = 'status-cancelled'; break;
                                default: $status_class = 'bg-secondary';
                            }
                        ?>
                        <div class="order-card">
                            <div class="order-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">Order #<?= $order['id'] ?></h5>
                                    <small class="text-muted">Placed on <?= date('M j, Y', strtotime($order['order_date'])) ?></small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge <?= $status_class ?> status-badge me-3"><?= $order['status'] ?></span>
                                    <span class="fw-bold">₹<?= number_format($order_total, 2) ?></span>
                                    
                                    <button class="btn btn-sm btn-link ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#order-<?= $order['id'] ?>">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div id="order-<?= $order['id'] ?>" class="collapse" data-bs-parent="#ordersAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="mb-3">Order Items</h6>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Price</th>
                                                            <th>Quantity</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($order['items'] as $item): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <img src="image/products/<?= htmlspecialchars($item['image']) ?>" 
                                                                         class="order-item-image me-3" 
                                                                         alt="<?= htmlspecialchars($item['product_name']) ?>">
                                                                    <div><?= htmlspecialchars($item['product_name']) ?></div>
                                                                </div>
                                                            </td>
                                                            <td>₹<?= number_format($item['price'], 2) ?></td>
                                                            <td><?= $item['quantity'] ?></td>
                                                            <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <?php if (!empty($order['shipping_address'])): ?>
                                            <div class="mt-4">
                                                <h6 class="mb-3">Shipping Address</h6>
                                                <address class="bg-light p-3 rounded">
                                                    <?= nl2br(htmlspecialchars($order['shipping_address'])) ?>
                                                </address>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="order-summary">
                                                <h6 class="mb-3">Order Summary</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td>Subtotal:</td>
                                                        <td class="text-end">₹<?= number_format($subtotal, 2) ?></td>
                                                    </tr>
                                                    <?php if ($order['discount_amount'] > 0): ?>
                                                    <tr>
                                                        <td>Discount:</td>
                                                        <td class="text-end text-danger">-₹<?= number_format($order['discount_amount'], 2) ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <tr class="border-top">
                                                        <td><strong>Total:</strong></td>
                                                        <td class="text-end"><strong>₹<?= number_format($order_total, 2) ?></strong></td>
                                                    </tr>
                                                </table>
                                                
                                                <div class="mt-4">
                                                    <h6 class="mb-3">Order Status Timeline</h6>
                                                    <div class="timeline">
                                                        <div class="timeline-item <?= strtolower($order['status']) === 'delivered' ? 'completed' : '' ?>">
                                                            <h6 class="mb-1">Order Placed</h6>
                                                            <p class="text-muted mb-0"><?= date('M j, Y g:i A', strtotime($order['order_date'])) ?></p>
                                                        </div>
                                                        
                                                        <div class="timeline-item <?= in_array(strtolower($order['status']), ['processing', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                                                            <h6 class="mb-1">Processing</h6>
                                                            <?php if (in_array(strtolower($order['status']), ['processing', 'shipped', 'delivered'])): ?>
                                                            <p class="text-muted mb-0"><?= date('M j, Y g:i A', strtotime($order['order_date'] . ' + 1 hour')) ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <div class="timeline-item <?= in_array(strtolower($order['status']), ['shipped', 'delivered']) ? 'completed' : '' ?>">
                                                            <h6 class="mb-1">Shipped</h6>
                                                            <?php if (in_array(strtolower($order['status']), ['shipped', 'delivered'])): ?>
                                                            <p class="text-muted mb-0"><?= date('M j, Y g:i A', strtotime($order['order_date'] . ' + 1 day')) ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <div class="timeline-item <?= strtolower($order['status']) === 'delivered' ? 'completed' : (strtolower($order['status']) === 'cancelled' ? 'pending' : '') ?>">
                                                            <h6 class="mb-1"><?= strtolower($order['status']) === 'delivered' ? 'Delivered' : (strtolower($order['status']) === 'cancelled' ? 'Cancelled' : 'Estimated Delivery') ?></h6>
                                                            <?php if (strtolower($order['status']) === 'delivered'): ?>
                                                            <p class="text-muted mb-0"><?= date('M j, Y g:i A', strtotime($order['order_date'] . ' + 3 days')) ?></p>
                                                            <?php elseif (strtolower($order['status']) === 'cancelled'): ?>
                                                            <p class="text-muted mb-0">Order was cancelled</p>
                                                            <?php else: ?>
                                                            <p class="text-muted mb-0">Expected by <?= date('M j, Y', strtotime($order['order_date'] . ' + 5 days')) ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Add delete button -->
                                                    <div class="mt-3 text-end">
                                                        <form method="post" onsubmit="return confirm('Are you sure you want to permanently delete this order? This action cannot be undone.');">
                                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                            <button type="submit" name="delete_order" class="btn btn-danger">
                                                                <i class="fas fa-trash-alt me-2"></i> Delete Order
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'masterpage/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Optional: Enhance delete confirmation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form[onsubmit]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you absolutely sure you want to delete this order? This action cannot be undone.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>