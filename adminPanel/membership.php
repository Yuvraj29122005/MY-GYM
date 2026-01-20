<?php include 'sidebar.php'; ?>
<?php include_once 'db_connection.php'; ?>

<?php
// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_membership'])) {
        // Add new membership
        $name = $_POST['name'];
        $duration = $_POST['duration'];
        $price = $_POST['price'];
        $status = $_POST['status'];
        $plan_type = $_POST['plan_type'];
        
        // Process features
        $features = [];
        if (isset($_POST['gym_access'])) $features[] = "Gym Access";
        if (isset($_POST['classes'])) $features[] = "Group Classes";
        if (isset($_POST['trainer'])) $features[] = "Personal Trainer";
        if (isset($_POST['spa'])) $features[] = "Spa Access";
        $features_str = implode(", ", $features);
        
        $description = $_POST['description'];

        $stmt = $conn->prepare("INSERT INTO membership_plans (name, duration, price, status, plan_type, features, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssss", $name, $duration, $price, $status, $plan_type, $features_str, $description);
        $stmt->execute();
    } elseif (isset($_POST['update_membership'])) {
        // Update membership
        $id = $_POST['id'];
        $name = $_POST['name'];
        $duration = $_POST['duration'];
        $price = $_POST['price'];
        $status = $_POST['status'];
        $plan_type = $_POST['plan_type'];
        
        // Process features
        $features = [];
        if (isset($_POST['gym_access'])) $features[] = "Gym Access";
        if (isset($_POST['classes'])) $features[] = "Group Classes";
        if (isset($_POST['trainer'])) $features[] = "Personal Trainer";
        if (isset($_POST['spa'])) $features[] = "Spa Access";
        $features_str = implode(", ", $features);
        
        $description = $_POST['description'];

        $stmt = $conn->prepare("UPDATE membership_plans SET name=?, duration=?, price=?, status=?, plan_type=?, features=?, description=? WHERE id=?");
        $stmt->bind_param("ssdssssi", $name, $duration, $price, $status, $plan_type, $features_str, $description, $id);
        $stmt->execute();
    }
}

if (isset($_GET['delete_id'])) {
    // Delete membership
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM membership_plans WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Fetch all membership plans
$query = "SELECT * FROM membership_plans";
$result = $conn->query($query);
?>

<div class="container-fluid">
    <div class="row">
        <main class="col-12 px-0">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Membership Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMembershipModal">
                        <i class="fas fa-plus me-2"></i>Add New Plan
                    </button>
                </div>
            </div>

            <!-- Membership Plans Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Active Membership Plans</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Duration</th>
                                    <th>Price</th>
                                    <th>Features</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['duration']) ?></td>
                                    <td>₹<?= number_format($row['price'], 2) ?></td>
                                    <td><?= htmlspecialchars($row['features']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $row['status'] === 'Active' ? 'success' : 'secondary' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editMembershipModal"
                                                data-id="<?= $row['id'] ?>"
                                                data-name="<?= htmlspecialchars($row['name']) ?>"
                                                data-duration="<?= htmlspecialchars($row['duration']) ?>"
                                                data-price="<?= $row['price'] ?>"
                                                data-status="<?= $row['status'] ?>"
                                                data-plan-type="<?= $row['plan_type'] ?>"
                                                data-features="<?= htmlspecialchars($row['features']) ?>"
                                                data-description="<?= htmlspecialchars($row['description']) ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this plan?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add Membership Modal -->
<div class="modal fade" id="addMembershipModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Membership Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Plan Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Duration</label>
                            <select class="form-select" name="duration" required>
                                <option value="">Select Duration</option>
                                <option>1 Month</option>
                                <option>3 Months</option>
                                <option>6 Months</option>
                                <option>12 Months</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="">Select Status</option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan Type</label>
                        <select class="form-select" name="plan_type" required>
                            <option value="">Select Plan Type</option>
                            <option value="basic">Basic Plan</option>
                            <option value="premium">Premium Plan</option>
                            <option value="vip">VIP Plan</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Features</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="gym_access" id="gymAccess">
                                <label class="form-check-label" for="gymAccess">Gym Access</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="classes" id="classes">
                                <label class="form-check-label" for="classes">Group Classes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="trainer" id="trainer">
                                <label class="form-check-label" for="trainer">Personal Trainer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="spa" id="spa">
                                <label class="form-check-label" for="spa">Spa Access</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="5" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="add_membership">Save Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Membership Modal -->
<div class="modal fade" id="editMembershipModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="">
                <input type="hidden" name="id" id="editId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Membership Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Plan Name</label>
                        <input type="text" class="form-control" name="name" id="editName" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Duration</label>
                            <select class="form-select" name="duration" id="editDuration" required>
                                <option value="">Select Duration</option>
                                <option>1 Month</option>
                                <option>3 Months</option>
                                <option>6 Months</option>
                                <option>12 Months</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="price" id="editPrice" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="editStatus" required>
                                <option value="">Select Status</option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan Type</label>
                        <select class="form-select" name="plan_type" id="editPlanType" required>
                            <option value="">Select Plan Type</option>
                            <option value="basic">Basic Plan</option>
                            <option value="premium">Premium Plan</option>
                            <option value="vip">VIP Plan</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Features</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="gym_access" id="editGymAccess">
                                <label class="form-check-label" for="editGymAccess">Gym Access</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="classes" id="editClasses">
                                <label class="form-check-label" for="editClasses">Group Classes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="trainer" id="editTrainer">
                                <label class="form-check-label" for="editTrainer">Personal Trainer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="spa" id="editSpa">
                                <label class="form-check-label" for="editSpa">Spa Access</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editDescription" rows="5" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update_membership">Update Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Handle edit modal data
document.getElementById('editMembershipModal').addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const modal = this;
    
    // Set form values from data attributes
    modal.querySelector('#editId').value = button.dataset.id;
    modal.querySelector('#editName').value = button.dataset.name;
    modal.querySelector('#editDuration').value = button.dataset.duration;
    modal.querySelector('#editPrice').value = button.dataset.price;
    modal.querySelector('#editStatus').value = button.dataset.status;
    modal.querySelector('#editPlanType').value = button.dataset.planType;
    modal.querySelector('#editDescription').value = button.dataset.description;
    
    // Reset all checkboxes
    modal.querySelector('#editGymAccess').checked = false;
    modal.querySelector('#editClasses').checked = false;
    modal.querySelector('#editTrainer').checked = false;
    modal.querySelector('#editSpa').checked = false;
    
    // Check the appropriate features
    const features = button.dataset.features.split(', ');
    features.forEach(feature => {
        if(feature.includes('Gym Access')) modal.querySelector('#editGymAccess').checked = true;
        if(feature.includes('Classes')) modal.querySelector('#editClasses').checked = true;
        if(feature.includes('Trainer')) modal.querySelector('#editTrainer').checked = true;
        if(feature.includes('Spa')) modal.querySelector('#editSpa').checked = true;
    });
});
</script>

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

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #b02a37;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>