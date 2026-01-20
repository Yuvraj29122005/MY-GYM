<?php
include 'masterpage/header.php';// Database connection
$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$user_id = 1; // Simulated login

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $check = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $update = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        $update->bind_param("iii", $quantity, $user_id, $product_id);
        $update->execute();
    } else {
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id, $product_id, $quantity);
        $insert->execute();
    }
}

// Handle quantity update
if (isset($_POST['update_cart'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    $conn->query("UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = $user_id");
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
}

// Get search & category
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'all';

// Build product query
$where = [];
$params = [];
$types = '';

if ($search) {
    $where[] = "(product_name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}
if ($category != 'all') {
    $where[] = "category = ?";
    $params[] = $category;
    $types .= "s";
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$product_sql = "SELECT * FROM products $whereSql";
$stmt = $conn->prepare($product_sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$products = $stmt->get_result();

// Fetch cart
$cart_sql = "SELECT cart.id as cart_id, products.product_name, products.price, products.image, cart.quantity 
             FROM cart 
             JOIN products ON cart.product_id = products.id 
             WHERE cart.user_id = $user_id";
$cart_result = $conn->query($cart_sql);
?>
<br><br>
<!DOCTYPE html>
<html>
<head>
    <title>Shop & Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">🛒 One Page Shop & Cart</h2>

    <!-- Filter Form -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="all">All Categories</option>
                <?php 
                $cat_res = $conn->query("SELECT DISTINCT category FROM products");
                while ($row = $cat_res->fetch_assoc()) {
                    $sel = $row['category'] == $category ? 'selected' : '';
                    echo "<option value='{$row['category']}' $sel>{$row['category']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <?php while ($p = $products->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100">
                    <img src="image/products/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" style="height: 200px; object-fit: contain;">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($p['product_name']) ?></h5>
                        <p><?= htmlspecialchars($p['description']) ?></p>
                        <p><strong>₹<?= number_format($p['price'], 2) ?></strong></p>
                        <form method="POST" action="cart.php">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <input type="number" name="quantity" value="1" class="form-control mb-2" min="1">
                                <button type="submit" name="add_to_cart" class="btn btn-sm btn-primary w-100">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

  

</body>
</html>
