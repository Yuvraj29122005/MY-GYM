<?php 
include 'masterpage/header.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = $search ? "WHERE name LIKE '%$search%' OR description LIKE '%$search%'" : '';

// Fetch equipment from database
$equipment_query = "SELECT * FROM equipment $whereClause ORDER BY id DESC";
$equipment_result = $conn->query($equipment_query);
?>

<section class="products py-5">
    <div class="container">
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
        
        <!-- Equipment Section -->
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold">Fitness Equipment</h2>
                <p class="text-muted">Professional equipment for home and gym workouts</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php if ($equipment_result && $equipment_result->num_rows > 0): ?>
                <?php while($item = $equipment_result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="product-card h-100 p-4 text-center shadow">
                        <img src="image/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="img-fluid mb-3">
                        <h4 class="mb-3"><?= htmlspecialchars($item['name']) ?></h4>
                        <p class="price">₹<?= number_format($item['price'], 2) ?></p>
                        <a href="login.php" class="btn btn-primary">Buy now</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted"><?= $search ? 'No equipment found matching your search' : 'No equipment available' ?></h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.products {
    background-color: #f8f9fa;
}

.products h2 {
    color: #333;
    position: relative;
    margin-bottom: 1rem;
}

.products h2:after {
    content: '';
    display: block;
    width: 50px;
    height: 3px;
    background: #0d6efd;
    margin: 15px auto;
}

.product-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.product-card img {
    max-height: 200px;
    object-fit: contain;
}

.product-card h4 {
    color: #333;
    font-weight: 600;
}

.price {
    font-size: 1.25rem;
    font-weight: bold;
    color: #0d6efd;
    margin-bottom: 1rem;
}

.btn-primary {
    padding: 0.5rem 2rem;
}

@media (max-width: 768px) {
    .product-card {
        margin-bottom: 1rem;
    }
    
    .input-group {
        width: 100% !important;
    }
}
</style>

<?php include 'masterpage/footer.php'; ?>