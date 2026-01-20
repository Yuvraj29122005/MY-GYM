<?php include 'sidebar.php' ?>
<?php include_once 'db_connection.php'; ?>

<?php
// Handle add equipment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_equipment'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $status = $_POST['status'];

        $image_path = "";
        if (!empty($_FILES["image"]["name"])) {
            $target_dir = "../image/products/";

            // Make sure folder exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $image_path = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
        }

        $stmt = $conn->prepare("INSERT INTO equipment (name, category, price, status, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $name, $category, $price, $status, $image_path);
        $stmt->execute();
    }


    // Handle update
    if (isset($_POST['update_equipment'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $status = $_POST['status'];
    
        $target_dir = "../image/products/";
        $image_path = "";
    
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
    
        // Check if new image uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = basename($_FILES["image"]["name"]);
            $image_path = $target_dir . $image_name;
    
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                // Update with new image
                $stmt = $conn->prepare("UPDATE equipment SET name=?, category=?, price=?, status=?, image=? WHERE id=?");
                $stmt->bind_param("ssdssi", $name, $category, $price, $status, $image_path, $id);
            } else {
                echo "❌ Failed to move uploaded file.";
                exit;
            }
        } else {
            // Update without image
            $stmt = $conn->prepare("UPDATE equipment SET name=?, category=?, price=?, status=? WHERE id=?");
            $stmt->bind_param("ssdsi", $name, $category, $price, $status, $id);
        }
    
        $stmt->execute();
    }
     
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM equipment WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM equipment WHERE name LIKE ? OR category LIKE ?";
$stmt = $conn->prepare($query);
$search_term = "%$search%";
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-fluid">
    <div class="row">
        <main class="col-md-12 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Equipment Management</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                    <i class="fas fa-plus me-1"></i> Add New Equipment
                </button>
            </div>

            <!-- Search -->
            <form method="get" class="mb-4">
                <div class="input-group w-50 mx-auto">
                    <input type="text" name="search" class="form-control" placeholder="Search equipment..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary">Search</button>
                </div>
            </form>

            <!-- Equipment Table -->
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th>Status</th>
                            <!-- <th>Last Maintenance</th> -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?= $row['image'] ?>" class="img-thumbnail" width="60"></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td>₹<?= number_format($row['price'], 2) ?></td>
                            <td><span class="badge bg-<?= $row['status'] === 'Available' ? 'success' : 'danger' ?>"><?= $row['status'] ?></span></td>
                        
                            <td>
                                <button class="btn btn-sm btn-info text-white edit-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editEquipmentModal"
                                   
                                    data-id="<?= $row['id'] ?>"
                                    data-image="<?= $row['image']?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-category="<?= $row['category'] ?>"
                                    data-price="<?= $row['price'] ?>"
                                    data-status="<?= $row['status'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this equipment?')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Add Equipment Modal -->
<div class="modal fade" id="addEquipmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label>Equipment Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Category</label>
                    <select name="category" class="form-select" required>
                        <option>Cardio</option>
                        <option>Strength</option>
                        <option>Flexibility</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Price</label>
                    <input type="number" name="price" step="0.01" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Available">Available</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <!-- <div class="col-md-6">
                    <label>Last Maintenance</label>
                    <input type="date" name="last_maintenance" class="form-control" required>
                </div> -->
                <div class="col-md-6">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" name="add_equipment">Add Equipment</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Equipment Modal -->
<div class="modal fade" id="editEquipmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="post" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Edit Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="col-md-6">
                    <label>Image</label>
                    <input type="file" name="image" id="edit_image" class="form-control" accept="image/*" required>
                </div>
            <div class="modal-body row g-3">
                <input type="hidden" name="id" id="edit_id">
                <div class="col-md-6">
                    <label>Equipment Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Category</label>
                    <select name="category" id="edit_category" class="form-select" required>
                        <option>Cardio</option>
                        <option>Strength</option>
                        <option>Flexibility</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Price</label>
                    <input type="number" name="price" id="edit_price" step="0.01" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Status</label>
                    <select name="status" id="edit_status" class="form-select" required>
                        <option value="Available">Available</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <!-- <div class="col-md-6">
                    <label>Last Maintenance</label>
                    <input type="date" name="last_maintenance" id="edit_date" class="form-control" required>
                </div> -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" name="update_equipment">Update Equipment</button>
            </div>
        </form>
    </div>
</div>

<script>
// Populate edit modal
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('edit_id').value = this.dataset.id;
        document.getElementById('edit_name').value = this.dataset.name;
        document.getElementById('edit_category').value = this.dataset.category;
        document.getElementById('edit_price').value = this.dataset.price;
        document.getElementById('edit_status').value = this.dataset.status;
        document.getElementById('edit_image').value = this.dataset.image;
    });
});
</script>

<style>
.img-thumbnail {
    height: 50px;
    object-fit: cover;
}
</style>
