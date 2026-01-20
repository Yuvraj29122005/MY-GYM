<?php 
include 'masterpage/header.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search and category parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Build WHERE clause with prepared statements
$whereClauses = [];
$params = [];
$types = '';

if (!empty($search)) {
    $whereClauses[] = "(name LIKE ? OR category LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'ss';
}

if ($category != 'all') {
    $whereClauses[] = "category = ?";
    $params[] = $category;
    $types .= 's';
}

$where = '';
if (!empty($whereClauses)) {
    $where = 'WHERE ' . implode(' AND ', $whereClauses);
}

// Prepare and execute query
$query = "SELECT id, name, category, price, image FROM equipment $where ORDER BY id DESC";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Error in query preparation: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$equipment_result = $stmt->get_result();
?>
  
<section class="products py-5">
    <div class="container">
        <!-- Equipment Section -->
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold">Fitness Equipment</h2>
                <p class="text-muted">Professional equipment for home and gym workouts</p>
            </div>
        </div>
        
        <!-- Search and Filter Form -->
        <form method="GET" class="row g-3 align-items-center filter-form mb-5">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search equipment..." 
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="all" <?= ($category == 'all') ? 'selected' : '' ?>>All Categories</option>
                    <?php
                    // Get distinct categories from database
                    $cat_query = "SELECT DISTINCT category FROM equipment WHERE category IS NOT NULL";
                    $cat_result = $conn->query($cat_query);
                    
                    if ($cat_result && $cat_result->num_rows > 0) {
                        while ($cat_row = $cat_result->fetch_assoc()) {
                            $cat = htmlspecialchars($cat_row['category']);
                            $selected = ($category == $cat) ? 'selected' : '';
                            echo "<option value=\"$cat\" $selected>$cat</option>";
                        }
                    }
                    ?>
                </select>
              
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="?" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
            </div>
        </form>

        <!-- Equipment Display -->
        <div class="row g-4">
            <?php if ($equipment_result && $equipment_result->num_rows > 0): ?>
                <?php while($item = $equipment_result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="product-card h-100 p-4 text-center shadow">
                        <img src="image/products/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" 
                             alt="<?= htmlspecialchars($item['name'] ?? 'Equipment Image') ?>" 
                             class="img-fluid mb-3 product-image">
                        <h4 class="mb-3"><?= htmlspecialchars($item['name'] ?? 'Unnamed Equipment') ?></h4>
                        <p class="text-muted mb-2">Category: <?= htmlspecialchars($item['category'] ?? 'N/A') ?></p>
                        <p class="price">₹<?= isset($item['price']) ? number_format($item['price'], 2) : '0.00' ?></p>
                        <a href="login.php" class="btn btn-primary">Buy now</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted"><?= ($search || $category != 'all') ? 'No equipment found matching your criteria' : 'No equipment available' ?></h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.products {
    background-color: #f8f9fa;
    min-height: 70vh;
}

.filter-form {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.product-card {
    background: #fff;
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.product-image {
    max-height: 200px;
    width: auto;
    object-fit: contain;
}

.price {
    font-size: 1.25rem;
    font-weight: bold;
    color: #0d6efd;
    margin: 1rem 0;
}

.btn-primary {
    padding: 0.5rem 2rem;
    border-radius: 50px;
}

@media (max-width: 768px) {
    .filter-form .col-md-3 {
        margin-top: 10px;
    }
    
    .product-image {
        max-height: 150px;
    }
}
</style>

<?php include 'masterpage/footer.php'; ?>