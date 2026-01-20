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

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    // Validate quantity
    if ($quantity < 1) {
        $errors[] = "Quantity must be at least 1";
    } else {
        // Check if product exists in cart
        $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing item
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        } else {
            // Add new item
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        }
        
        if ($stmt->execute()) {
            $success = "Product added to cart successfully";
        } else {
            $errors[] = "Error adding product to cart";
        }
    }
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    if ($stmt->execute()) {
        $success = "Item removed from cart";
    } else {
        $errors[] = "Error removing item from cart";
    }
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity < 1) {
        $errors[] = "Quantity must be at least 1";
    } else {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
        if ($stmt->execute()) {
            $success = "Quantity updated successfully";
        } else {
            $errors[] = "Error updating quantity";
        }
    }
}

// Fetch cart items
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
    } else {
        $discount = min($applied_coupon['discount_value'], $subtotal);
    }
    $discount_type = $applied_coupon['discount_type'];
}

$total = $subtotal - $discount;

// Checkout process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Validate cart not empty
    if (empty($cart_items)) {
        $errors[] = "Your cart is empty";
    } else {
        // Redirect to checkout page
        header("Location: checkout.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cart-container {
            padding: 2rem 0;
        }
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .quantity-input {
            width: 60px;
            text-align: center;
        }
        .coupon-success {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'masterpage/header.php'; ?>

    <div class="container cart-container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Your Shopping Cart</h2>
                
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
                
                <?php if (!empty($cart_items)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td>
                                            <img src="image/products/<?= htmlspecialchars($item['image']) ?>" 
                                                 class="cart-item-image me-3" 
                                                 alt="<?= htmlspecialchars($item['product_name']) ?>">
                                            <?= htmlspecialchars($item['product_name']) ?>
                                        </td>
                                        <td>₹<?= number_format($item['price'], 2) ?></td>
                                        <td>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                                <input type="number" name="quantity" 
                                                       value="<?= $item['quantity'] ?>" 
                                                       class="form-control quantity-input" min="1">
                                                <button type="submit" name="update_quantity" class="btn btn-sm btn-outline-primary mt-2">
                                                    Update
                                                </button>
                                            </form>
                                        </td>
                                        <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                        <td>
                                            <a href="?remove=<?= $item['cart_id'] ?>" class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Are you sure you want to remove this item?')">
                                                Remove
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                    <td colspan="2" class="fw-bold">₹<?= number_format($subtotal, 2) ?></td>
                                </tr>
                                <?php if ($applied_coupon): ?>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">
                                            Discount (<?= htmlspecialchars($applied_coupon['code']) ?>)
                                            <?php if ($applied_coupon['discount_type'] === 'percentage'): ?>
                                                (<?= $applied_coupon['discount_value'] ?>%)
                                            <?php endif; ?>
                                        </td>
                                        <td colspan="2" class="fw-bold text-danger">-₹<?= number_format($discount, 2) ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td colspan="2" class="fw-bold">₹<?= number_format($total, 2) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <form method="POST" class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Apply Coupon</h5>
                                    <?php if ($applied_coupon): ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="coupon-success">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Coupon "<?= htmlspecialchars($applied_coupon['code']) ?>" applied
                                                (<?= $applied_coupon['discount_type'] === 'percentage' ? 
                                                   $applied_coupon['discount_value'] . '% off' : 
                                                   '₹' . $applied_coupon['discount_value'] . ' off' ?>)
                                            </div>
                                            <a href="?remove_coupon=1" class="btn btn-sm btn-outline-danger">Remove</a>
                                        </div>
                                    <?php else: ?>
                                        <div class="input-group">
                                            <input type="text" name="coupon_code" class="form-control" 
                                                   placeholder="Enter coupon code" value="<?= htmlspecialchars($coupon_code) ?>">
                                            <button type="submit" name="apply_coupon" class="btn btn-primary">Apply</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="products.php" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Continue Shopping
                            </a>
                            <form method="POST" class="d-inline">
                                <button type="submit" name="checkout" class="btn btn-primary">
                                    <i class="fas fa-credit-card me-1"></i> Proceed to Checkout
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Your cart is empty</h4>
                        <a href="protein.php" class="btn btn-primary mt-3">
                            <i class="fas fa-store me-1"></i> Browse Products
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'masterpage/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-update quantity when changed (optional)
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
</body>
</html>