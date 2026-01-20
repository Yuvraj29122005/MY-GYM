<?php
// Start session
session_start();

// Redirect if no order ID in session
if (!isset($_SESSION['order_id'])) {
    header("Location: cart.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order ID from session
$order_id = $_SESSION['order_id'];

// Fetch order details
$order_stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows === 0) {
    // Order not found
    header("Location: cart.php");
    exit;
}

$order = $order_result->fetch_assoc();

// Fetch order items
$items_stmt = $conn->prepare("
    SELECT oi.*, p.product_name, p.image 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$order_items = $items_result->fetch_all(MYSQLI_ASSOC);

// Calculate order total
$order_total = $order['total_amount'];
$subtotal = $order_total + $order['discount_amount']; // Add back discount to get subtotal

// Clear session after displaying
unset($_SESSION['order_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .order-card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .order-header {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        .order-item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .summary-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
        }
        .divider {
            border-top: 1px dashed #dee2e6;
            margin: 1.5rem 0;
        }
        .thank-you-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'masterpage/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="order-card mb-5">
                    <div class="order-header text-center">
                        <div class="thank-you-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2 class="mb-2">Thank You For Your Order!</h2>
                        <p class="text-muted">Your order has been received and is being processed.</p>
                        <div class="alert alert-success d-inline-block">
                            <strong>Order Number:</strong> #<?= htmlspecialchars($order['id']) ?>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        
                        
                        <div class="divider"></div>
                        
                        <h5 class="mb-3">Shipping Address</h5>
                        <address>
                            <?= nl2br(htmlspecialchars($order['shipping_address'] ?? 'Not specified')) ?>
                        </address>
                        
                        <div class="divider"></div>
                        
                        <h5 class="mb-3">Order Items</h5>
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
                                    <?php foreach ($order_items as $item): ?>
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
                        
                        <div class="divider"></div>
                        
                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <div class="summary-card">
                                    <h5 class="mb-3">Order Summary</h5>
                                    <table class="table table-borderless">
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
                                </div>
                            </div>
                        </div>
                        
                        <div class="divider"></div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted mb-4">
                                A confirmation email has been sent to <strong><?= htmlspecialchars($order['customer_email'] ?? 'your email') ?></strong>.
                                You'll receive another email when your order ships.
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="order_history.php" class="btn btn-outline-primary">
                                    <i class="fas fa-history me-2"></i> View Order History
                                </a>
                                <a href="products.php" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag me-2"></i> Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">What happens next?</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-box-open fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6>Order Processing</h6>
                                        <p class="text-muted small">We're preparing your items for shipment.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-truck fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6>Shipping</h6>
                                        <p class="text-muted small">Your order will be shipped within 1-2 business days.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-home fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6>Delivery</h6>
                                        <p class="text-muted small">Expected delivery in 3-5 business days.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'masterpage/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>