<?php
include 'db_connection.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new trainer
    if (isset($_POST['add_trainer'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $specialization = $_POST['specialization'];
        $experience = $_POST['experience'];
        
        $photoName = $_FILES['photo']['name'];
        $photoTmp = $_FILES['photo']['tmp_name'];
        $uploadPath = '../image/trainers/'.$photoName;
        move_uploaded_file($photoTmp, $uploadPath);

        $sql = "INSERT INTO trainers (first_name, last_name, email, specialization, experience, photo, status)
                VALUES (?, ?, ?, ?, ?, ?, 'active')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $specialization, $experience, $photoName);
        $stmt->execute();
        $stmt->close();
        
        header("Location: trainers.php");
        exit();
    }
    
    // Update trainer
    if (isset($_POST['update_trainer'])) {
        $id = $_POST['id'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $specialization = $_POST['specialization'];
        $experience = $_POST['experience'];
        $status = $_POST['status'];
        $currentPhoto = $_POST['currentPhoto'];
        
        if (!empty($_FILES['photo']['name'])) {
            $photoName = $_FILES['photo']['name'];
            $photoTmp = $_FILES['photo']['tmp_name'];
            $uploadPath = '../image/trainers/'.$photoName;
            move_uploaded_file($photoTmp, $uploadPath);
        } else {
            $photoName = $currentPhoto;
        }

        $sql = "UPDATE trainers SET 
                first_name = ?,
                last_name = ?,
                email = ?,
                specialization = ?,
                experience = ?,
                status = ?,
                photo = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissi", $firstName, $lastName, $email, $specialization, 
                          $experience, $status, $photoName, $id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: trainers.php");
        exit();
    }
    
    // Delete trainer
    if (isset($_POST['delete_trainer'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM trainers WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: trainers.php");
        exit();
    }
}

// Get all trainers for listing
$trainers = [];
$sql = "SELECT * FROM trainers";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    $trainers[] = $row;
}

// Get single trainer for editing
$editTrainer = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM trainers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editTrainer = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainers Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col-md-12 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Trainers Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainerModal">
                            <i class="fas fa-plus me-2"></i>Add New Trainer
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>

                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Specialization</th>
                                        <th>Experience</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($trainers as $trainer): ?>
                                    <tr>

                                    
                                        <td>
                                            <img src="../image/trainers/<?= $trainer['photo'] ?>" 
                                                 class="rounded-circle" style="width:40px;height:40px;">
                                        </td>
                                        <td><?= $trainer['first_name'] . ' ' . $trainer['last_name'] ?></td>
                                        <td><?= $trainer['specialization'] ?></td>
                                        <td><?= $trainer['experience'] ?> years</td>
                                        <td>
                                            <span class="badge bg-<?= 
                                                ($trainer['status'] == 'active') ? 'success' : 
                                                (($trainer['status'] == 'on-leave') ? 'warning' : 'danger'); 
                                            ?>">
                                                <?= ucfirst($trainer['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="trainers.php?edit=<?= $trainer['id'] ?>" class="btn btn-sm btn-info me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="trainers.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $trainer['id'] ?>">
                                                <button type="submit" name="delete_trainer" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this trainer?')">
                                                    <i class="fas fa-trash"></i>
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

    <!-- Add Trainer Modal -->
    <div class="modal fade" id="addTrainerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Trainer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="trainers.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="firstName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastName" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Specialization</label>
                                <select class="form-select" name="specialization" required>
                                    <option value="">Select Specialization</option>
                                    <option value="Yoga">Yoga</option>
                                    <option value="Cardio">Cardio</option>
                                    <option value="Strength Training">Strength Training</option>
                                    <option value="CrossFit">CrossFit</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" name="experience" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" name="photo" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_trainer" class="btn btn-primary">Save Trainer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Trainer Modal -->
    <?php if ($editTrainer): ?>
    <div class="modal fade show" id="editTrainerModal" tabindex="-1" style="display: block; padding-right: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Trainer</h5>
                    <a href="trainers.php" class="btn-close"></a>
                </div>
                <form action="trainers.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $editTrainer['id'] ?>">
                        <input type="hidden" name="currentPhoto" value="<?= $editTrainer['photo'] ?>">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="firstName" 
                                       value="<?= $editTrainer['first_name'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastName" 
                                       value="<?= $editTrainer['last_name'] ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?= $editTrainer['email'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Specialization</label>
                                <select class="form-select" name="specialization" required>
                                    <option value="">Select Specialization</option>
                                    <option value="Yoga" <?= ($editTrainer['specialization'] == 'Yoga') ? 'selected' : '' ?>>Yoga</option>
                                    <option value="Cardio" <?= ($editTrainer['specialization'] == 'Cardio') ? 'selected' : '' ?>>Cardio</option>
                                    <option value="Strength Training" <?= ($editTrainer['specialization'] == 'Strength Training') ? 'selected' : '' ?>>Strength Training</option>
                                    <option value="CrossFit" <?= ($editTrainer['specialization'] == 'CrossFit') ? 'selected' : '' ?>>CrossFit</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" name="experience" 
                                       value="<?= $editTrainer['experience'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" name="photo" accept="image/*">
                                <small class="text-muted">Current: <?= $editTrainer['photo'] ?></small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="active" <?= ($editTrainer['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                                <option value="on-leave" <?= ($editTrainer['status'] == 'on-leave') ? 'selected' : '' ?>>On Leave</option>
                                <option value="terminated" <?= ($editTrainer['status'] == 'terminated') ? 'selected' : '' ?>>Terminated</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="trainers.php" class="btn btn-secondary">Close</a>
                        <button type="submit" name="update_trainer" class="btn btn-primary">Update Trainer</button>
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
                    window.location.href = 'trainers.php';
                });
            }
        });
    </script>


    <style>
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }

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
    </style>



</body>
</html>