<?php
include_once 'db_connection.php';

// Handle POST Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add User
    if (isset($_POST['add_user'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $status = $_POST['status'];
        $membership_plan = $_POST['membership_plan'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, gender, status, password, membership_plan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullname, $email, $gender, $status, $password, $membership_plan);
        $stmt->execute();
        header('Location: user.php');
        exit();
    }

    // Edit User
    if (isset($_POST['edit_user'])) {
        $id = $_POST['user_id'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $status = $_POST['status'];
        $membership_plan = $_POST['membership_plan'];
        
        $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, gender=?, status=?, membership_plan=? WHERE id=?");
        $stmt->bind_param("sssssi", $fullname, $email, $gender, $status, $membership_plan, $id);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        exit();
    }

    // Delete User
    if (isset($_POST['delete_user'])) {
        $id = $_POST['uid'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header('Location: user.php');
        exit();
    }
}

// Handle GET Request for Fetching User Data
if (isset($_GET['get_user']) && isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($user);
    exit();
}

include 'sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <main class="col-md-12 px-md-4">
            <div class="d-flex justify-content-between mb-3">
                <h2>User Management</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
            </div>

            <table class="table table-bordered bg-white shadow-sm">
                <thead class="table-dark">
                <tr>
                    <th>Fullname</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Membership Plan</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
                while ($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($row['gender'])) ?></td>
                        <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                        <td><?= htmlspecialchars($row['membership_plan']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="<?= $row['id'] ?>">Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="uid" value="<?= $row['id'] ?>">
                                <button type="submit" name="delete_user" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3 px-3">
                <div class="col-12">
                    <label>Fullname</label>
                    <input type="text" name="fullname" class="form-control" required>
                </div>
                <div class="col-12">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>
                <div class="col-6">
                    <label>Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-6">
                    <label>Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-6">
                    <label>Membership Plan</label>
                    <select name="membership_plan" class="form-select" required>
                        <option value="no plan">No Plan</option>
                        <option value="Basic">Basic</option>
                        <option value="Premium">Premium</option>
                        <option value="VIP">VIP</option>
                    </select>
                </div>
                <div class="col-6">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editUserForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3 px-3">
                <input type="hidden" name="edit_user" value="1">
                <input type="hidden" name="user_id" id="editUserId">
                <div class="col-12">
                    <label>Fullname</label>
                    <input name="fullname" id="editFullname" class="form-control" required>
                </div>
                <div class="col-12">
                    <label>Email</label>
                    <input name="email" id="editEmail" class="form-control" required>
                </div>
                <div class="col-6">
                    <label>Gender</label>
                    <select name="gender" id="editGender" class="form-select" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-6">
                    <label>Status</label>
                    <select name="status" id="editStatus" class="form-select" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <label>Membership Plan</label>
                    <select name="membership_plan" id="editMembershipPlan" class="form-select" required>
                        <option value="no plan">No Plan</option>
                        <option value="Basic">Basic</option>
                        <option value="Premium">Premium</option>
                        <option value="VIP">VIP</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle Edit Button Click
    $('.edit-btn').click(function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'user.php',
            data: { get_user: 1, id: userId },
            type: 'GET',
            dataType: 'json',
            success: function(user) {
                $('#editUserId').val(user.id);
                $('#editFullname').val(user.fullname);
                $('#editEmail').val(user.email);
                $('#editGender').val(user.gender);
                $('#editStatus').val(user.status);
                $('#editMembershipPlan').val(user.membership_plan);
                $('#editUserModal').modal('show');
            }
        });
    });

    // Handle Edit Form Submission
    $('#editUserForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'user.php',
            data: formData,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#editUserModal').modal('hide');
                    location.reload();
                }
            }
        });
    });
});
</script>
</body>
</html>