<?php
include 'db_connection.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new product
    if (isset($_POST['add_product'])) {
        $productName = $_POST['product_name'];
        $brand = $_POST['brand'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];
        
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $uploadPath = 'image/products/'.$imageName;
        move_uploaded_file($imageTmp, $uploadPath);

        $sql = "INSERT INTO products (product_name, brand, category, price, stock, description, image, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'in-stock')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiss", $productName, $brand, $category, $price, $stock, $description, $imageName);
        $stmt->execute();
        $stmt->close();
        
        header("Location: protein.php");
        exit();
    }
    
    // Update product
    if (isset($_POST['update_product'])) {
        $id = $_POST['id'];
        $productName = $_POST['product_name'];
        $brand = $_POST['brand'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];
        $currentImage = $_POST['current_image'];
        
        if (!empty($_FILES['image']['name'])) {
            $imageName = $_FILES['image']['name'];
            $imageTmp = $_FILES['image']['tmp_name'];
            $uploadPath = 'image/products/'.$imageName;
            move_uploaded_file($imageTmp, $uploadPath);
        } else {
            $imageName = $currentImage;
        }

        $sql = "UPDATE products SET 
                product_name = ?,
                brand = ?,
                category = ?,
                price = ?,
                stock = ?,
                description = ?,
                image = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissi", $productName, $brand, $category, $price, 
                          $stock, $description, $imageName, $id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: protein.php");
        exit();
    }
    
    // Delete product
    if (isset($_POST['delete_product'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: protein.php");
        exit();
    }
}

// Get all products
$products = [];
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM products WHERE product_name LIKE ? OR brand LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
    $products[] = $row;
}
$stmt->close();

// Get single product for editing
$editProduct = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editProduct = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protein Products Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col-md-12 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Protein Products Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProteinModal">
                            <i class="fas fa-plus me-2"></i>Add New Product
                        </button>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="row mb-5">
                    <div class="col-12">
                        <form action="" method="get" class="d-flex justify-content-center">
                            <div class="input-group w-50">
                                <input type="text" class="form-control" placeholder="Search products..." name="search" value="<?= htmlspecialchars($search) ?>">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="card border-0 shadow">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Product List</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                                        <td><?= htmlspecialchars($product['brand']) ?></td>
                                        <td><?= htmlspecialchars($product['category']) ?></td>
                                        <td>₹<?= number_format($product['price'], 2) ?></td>
                                        <td><?= $product['stock'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                                                <?= $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="protein.php?edit=<?= $product['id'] ?>" class="btn btn-sm btn-info me-2">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="protein.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                                <button type="submit" name="delete_product" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProteinModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Protein Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="protein.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="product_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Brand</label>
                                <input type="text" class="form-control" name="brand" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Whey Protein">Whey Protein</option>
                                    <option value="Mass Gainer">Mass Gainer</option>
                                    <option value="BCAA">BCAA</option>
                                    <option value="Pre-Workout">Pre-Workout</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Price</label>
                                <input type="number" class="form-control" name="price" step="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" name="stock" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product Description</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_product" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <?php if ($editProduct): ?>
    <div class="modal fade show" id="editProteinModal" tabindex="-1" style="display: block; padding-right: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Protein Product</h5>
                    <a href="protein.php" class="btn-close"></a>
                </div>
                <form action="protein.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
                        <input type="hidden" name="current_image" value="<?= $editProduct['image'] ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="product_name" 
                                       value="<?= htmlspecialchars($editProduct['product_name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Brand</label>
                                <input type="text" class="form-control" name="brand" 
                                       value="<?= htmlspecialchars($editProduct['brand']) ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Whey Protein" <?= $editProduct['category'] == 'Whey Protein' ? 'selected' : '' ?>>Whey Protein</option>
                                    <option value="Mass Gainer" <?= $editProduct['category'] == 'Mass Gainer' ? 'selected' : '' ?>>Mass Gainer</option>
                                    <option value="BCAA" <?= $editProduct['category'] == 'BCAA' ? 'selected' : '' ?>>BCAA</option>
                                    <option value="Pre-Workout" <?= $editProduct['category'] == 'Pre-Workout' ? 'selected' : '' ?>>Pre-Workout</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Price</label>
                                <input type="number" class="form-control" name="price" 
                                       value="<?= $editProduct['price'] ?>" step="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" name="stock" 
                                       value="<?= $editProduct['stock'] ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <small class="text-muted">Current: <?= $editProduct['image'] ?></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product Description</label>
                                <textarea class="form-control" name="description" rows="3" required><?= 
                                    htmlspecialchars($editProduct['description']) 
                                ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="protein.php" class="btn btn-secondary">Close</a>
                        <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Close edit modal when clicking backdrop
        document.addEventListener('DOMContentLoaded', function() {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    window.location.href = 'protein.php';
                });
            }
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.btn-info {
    color: #fff;
    background-color: #0dcaf0;
    border-color: #0dcaf0;
}

.btn-info:hover {
    color: #fff;
    background-color: #31d2f2;
    border-color: #25cff2;
}

.pagination .page-link {
    color: #0d6efd;
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

@media (max-width: 768px) {
    .btn-toolbar {
        margin-top: 1rem;
    }
}

.card-img-top {
    height: 200px;
    object-fit: contain;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
}
</style>


