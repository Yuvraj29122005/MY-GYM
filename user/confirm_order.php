<?php
// Start session and include database connection
session_start();
include 'masterpage/db_connect.php';

// Check if user is logged in (replace with your actual authentication check)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch order items from database
$order_items = [];
$total_amount = 0;

// Check if we're coming from the cart (for new orders)
if (isset($_SESSION['cart_items']) && !empty($_SESSION['cart_items'])) {
    // Process the order creation (you would typically do this before showing confirmation)
    // This is just for demonstration - in a real app you'd have proper order processing
    
    // For each item in cart, get product details
    foreach ($_SESSION['cart_items'] as $product_id => $item) {
        $stmt = $conn->prepare("SELECT product_name, price, image FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $subtotal = $product['price'] * $item['quantity'];
            $total_amount += $subtotal;
            
            $order_items[] = [
                'image' => 'image/products/' . $product['image'],
                'name' => $product['product_name'],
                'price' => $product['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal
            ];
        }
    }
    
    // In a real application, you would:
    // 1. Create an order record in the orders table
    // 2. Create order items in the order_items table
    // 3. Clear the cart
    // 4. Then show the confirmation page
    
} elseif (isset($_GET['order_id'])) {
    // If viewing a past order confirmation
    $order_id = intval($_GET['order_id']);
    
    // Verify this order belongs to the current user
    $stmt = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Get order items
        $stmt = $conn->prepare("SELECT oi.quantity, oi.price, p.product_name, p.image 
                               FROM order_items oi 
                               JOIN products p ON oi.product_id = p.id 
                               WHERE oi.order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items_result = $stmt->get_result();
        
        while ($item = $items_result->fetch_assoc()) {
            $subtotal = $item['price'] * $item['quantity'];
            $total_amount += $subtotal;
            
            $order_items[] = [
                'image' => 'image/products/' . $item['image'],
                'name' => $item['product_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal
            ];
        }
    } else {
        // Order doesn't belong to user or doesn't exist
        header("Location: orders.php");
        exit();
    }
} else {
    // No order data to show
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .order-container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .order-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .order-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .order-item img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 8px;
            margin-right: 20px;
            border: 1px solid #eee;
            padding: 5px;
        }
        .order-details {
            flex: 1;
        }
        .order-details h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .order-details p {
            margin: 5px 0;
            color: #666;
        }
        .price {
            font-weight: 600;
            color: #333;
        }
        .summary-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-row {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .btn-continue {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn-continue:hover {
            background-color: #218838;
            color: white;
        }
        .empty-cart {
            text-align: center;
            padding: 40px 0;
        }
        .empty-cart i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'masterpage/header.php'; ?>
    
    <div class="order-container">
        <div class="order-header">
            <h1><i class="fas fa-check-circle text-success me-2"></i> Order Confirmation</h1>
            <p class="text-muted">Thank you for your purchase!</p>
        </div>
        
        <?php if (!empty($order_items)): ?>
            <h4 class="mb-4">Order Details</h4>
            
            <?php foreach ($order_items as $item): ?>
                <div class="order-item">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="order-details">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                        <p>Price: ₹<?= number_format($item['price'], 2) ?></p>
                    </div>
                    <div class="price">
                        ₹<?= number_format($item['subtotal'], 2) ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="summary-card">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>₹<?= number_format($total_amount, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>FREE</span>
                </div>
                <div class="summary-row">
                    <span>Tax:</span>
                    <span>₹0.00</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total:</span>
                    <span>₹<?= number_format($total_amount, 2) ?></span>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="products.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                </a>
                <a href="order_history.php" class="btn btn-primary">
                    View Order History <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>No order items found</h3>
                <p>We couldn't find any items for this order</p>
                <a href="products.php" class="btn btn-primary">
                    <i class="fas fa-store me-2"></i> Browse Products
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'masterpage/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>