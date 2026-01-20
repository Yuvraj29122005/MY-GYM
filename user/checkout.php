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

// Initialize variables
$errors = [];
$success = '';
$discount = 0;
$discount_type = '';
$coupon_code = '';
$total = 0;

// Handle coupon application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
    $coupon_code = trim($_POST['coupon_code']);
    
    if (empty($coupon_code)) {
        $errors[] = "Please enter a coupon code";
    } else {
        // Check if coupon is valid
        $stmt = $conn->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = TRUE 
                               AND valid_from <= CURDATE() AND valid_until >= CURDATE() 
                               AND (usage_limit IS NULL OR times_used < usage_limit)");
        $stmt->bind_param("s", $coupon_code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $errors[] = "Invalid or expired coupon code";
        } else {
            $coupon = $result->fetch_assoc();
            $_SESSION['applied_coupon'] = $coupon;
            $success = "Coupon applied successfully";
        }
    }
}

// Handle coupon removal
if (isset($_GET['remove_coupon'])) {
    unset($_SESSION['applied_coupon']);
    $success = "Coupon removed successfully";
}

// Check for applied coupon in session
$applied_coupon = $_SESSION['applied_coupon'] ?? null;

// Fetch cart items and calculate subtotal
$cart_items = [];
$subtotal = 0;

$stmt = $conn->prepare("SELECT c.id as cart_id, p.id as product_id, p.product_name, p.price, p.image, c.quantity 
                       FROM cart c JOIN products p ON c.product_id = p.id 
                       WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($item = $result->fetch_assoc()) {
    $cart_items[] = $item;
    $subtotal += $item['price'] * $item['quantity'];
}

// Calculate discount if coupon is applied
if ($applied_coupon && $subtotal >= $applied_coupon['min_cart_value']) {
    if ($applied_coupon['discount_type'] === 'percentage') {
        $discount = ($subtotal * $applied_coupon['discount_value']) / 100;
        // Apply max discount if set
        if (isset($applied_coupon['max_discount_amount'])) {
            $discount = min($discount, $applied_coupon['max_discount_amount']);
        }
    } else {
        $discount = min($applied_coupon['discount_value'], $subtotal);
    }
    $discount_type = $applied_coupon['discount_type'];
}

$total = $subtotal - $discount;

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Validate cart not empty
    if (empty($cart_items)) {
        $errors[] = "Your cart is empty";
    } else {
        // Validate form inputs
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($name) || empty($email) || empty($address)) {
            $errors[] = "Please fill all required fields";
        } else {
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // 1. Create order record
                $order_sql = "INSERT INTO orders (user_id, order_date, total_amount, discount_amount, status, coupon_code) 
              VALUES (?, NOW(), ?, ?, 'Pending', ?)";
$stmt = $conn->prepare($order_sql);
$coupon_code_value = $applied_coupon ? $applied_coupon['code'] : NULL;
$stmt->bind_param("idds", $user_id, $subtotal, $discount, $coupon_code_value);
$stmt->execute();
$order_id = $conn->insert_id;   
                // 2. Record coupon usage if applied
                if ($applied_coupon) {
                    $coupon_usage_sql = "INSERT INTO coupon_usages (order_id, coupon_id, discount_amount) 
                                         VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($coupon_usage_sql);
                    $stmt->bind_param("iid", $order_id, $applied_coupon['id'], $discount);
                    $stmt->execute();
                    
                    // Update coupon usage count
                    $update_coupon_sql = "UPDATE coupons SET times_used = times_used + 1 WHERE id = ?";
                    $stmt = $conn->prepare($update_coupon_sql);
                    $stmt->bind_param("i", $applied_coupon['id']);
                    $stmt->execute();
                }
                
                // 3. Insert order items and update product quantities
                foreach ($cart_items as $item) {
                    // Insert order item
                    $order_item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                       VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($order_item_sql);
                    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                    $stmt->execute();
                    
                    // Update product stock
                    $update_stock_sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
                    $stmt = $conn->prepare($update_stock_sql);
                    $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
                    $stmt->execute();
                }
                
                // 4. Clear the cart
                $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
                $stmt = $conn->prepare($clear_cart_sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                
                // Commit transaction
                $conn->commit();
                
                // Clear coupon from session
                unset($_SESSION['applied_coupon']);
                
                // Redirect to order confirmation
                $_SESSION['order_id'] = $order_id;
                header("Location: order_confirmation.php");
                exit;
                
            } catch (Exception $e) {
                // Rollback on error
                $conn->rollback();
                $errors[] = "Error placing order: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .coupon-success {
            color: #28a745;
            font-weight: bold;
        }
        .coupon-container {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'masterpage/header.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <h2 class="mb-4">Checkout</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-0"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
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
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td>₹<?= number_format($item['price'], 2) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal</th>
                                    <th>₹<?= number_format($subtotal, 2) ?></th>
                                </tr>
                                <?php if ($applied_coupon): ?>
                                    <tr>
                                        <th colspan="3" class="text-end">
                                            Discount (<?= htmlspecialchars($applied_coupon['code']) ?>)
                                            <?php if ($applied_coupon['discount_type'] === 'percentage'): ?>
                                                (<?= $applied_coupon['discount_value'] ?>%)
                                            <?php endif; ?>
                                        </th>
                                        <th class="text-danger">-₹<?= number_format($discount, 2) ?></th>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th>₹<?= number_format($total, 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        
                        <!-- Coupon Section -->
                        <div class="coupon-container mt-4">
                            <?php if ($applied_coupon): ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="coupon-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Coupon "<?= htmlspecialchars($applied_coupon['code']) ?>" applied successfully
                                        (<?= $applied_coupon['discount_type'] === 'percentage' ? 
                                           $applied_coupon['discount_value'] . '% off' : 
                                           '₹' . $applied_coupon['discount_value'] . ' off' ?>)
                                    </div>
                                    <a href="?remove_coupon=1" class="btn btn-sm btn-outline-danger">Remove</a>
                                </div>
                            <?php else: ?>
                                <form method="POST" class="input-group">
                                    <input type="text" name="coupon_code" class="form-control" 
                                           placeholder="Enter coupon code" value="<?= htmlspecialchars($coupon_code) ?>">
                                    <button type="submit" name="apply_coupon" class="btn btn-primary">Apply</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Complete Your Order</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Shipping Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="place_order" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card me-2"></i> Place Order (₹<?= number_format($total, 2) ?>)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'masterpage/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>