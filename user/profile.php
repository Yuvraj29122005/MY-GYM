<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Fetch user details from `users` table
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}
include 'masterpage/header.php';
?>


<div class="profile-container py-5">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Profile Section -->
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>Profile Information</h4>
                        <button class="btn btn-light btn-sm" onclick="toggleEditMode()">
                            <i class="fas fa-edit me-1"></i>Edit Profile
                        </button>
                    </div>
                    <div class="card-body">

                        <!-- View Mode -->
                        <div id="profile-view">
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0 text-muted">Full Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($user['fullname']); ?></p>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0 text-muted">Gender</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="mb-0 fw-bold"><?php echo ucfirst(htmlspecialchars($user['gender'])); ?></p>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0 text-muted">Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($user['email']); ?></p>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0 text-muted">Membership Plan</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($user['membership_plan']); ?></p>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-outline-primary" onclick="showChangePassword()">
                                    <i class="fas fa-lock me-2"></i>Change Password
                                </button>
                            </div>
                        </div>

                        <!-- Edit Mode -->
                        <form id="profile-form" class="d-none needs-validation" novalidate action="update_profile.php" method="POST">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fullName" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required
                                    pattern="^[a-zA-Z\s]{2,50}$">
                                <div class="invalid-feedback">
                                    Please enter a valid name (2-50 characters, letters only).
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="male" <?php echo ($user['gender'] == 'male') ? 'checked' : ''; ?> required>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="female" <?php echo ($user['gender'] == 'female') ? 'checked' : ''; ?> required>
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="other" value="other" <?php echo ($user['gender'] == 'other') ? 'checked' : ''; ?> required>
                                        <label class="form-check-label" for="other">Other</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                <div class="invalid-feedback">
                                    Please enter a valid email address.
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary me-2 px-4">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                <button type="button" class="btn btn-secondary px-4" onclick="toggleEditMode()">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-lock me-2"></i>Change Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="password-form" class="needs-validation" novalidate action="update_password.php" method="POST">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <input type="password" class="form-control" id="currentPassword" name="current_password" required minlength="8">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('currentPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <div class="invalid-feedback">
                                Please enter your current password.
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <input type="password" class="form-control" id="newPassword" name="new_password" required
                                pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <div class="invalid-feedback">
                                Password must be at least 8 characters with letters, numbers and special characters.
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="confirmPassword" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <div class="invalid-feedback">
                                Passwords do not match.
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-key me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Success</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p id="successMessage" class="mb-0"></p>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-circle me-2"></i>Error</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p id="errorMessage" class="mb-0"></p>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-container {
        background-color: #f8f9fa;
        min-height: calc(100vh - 76px);
        margin-top: 76px;
    }

    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }

    .card-header {
        border-bottom: none;
        padding: 1.5rem;
    }

    .card-body {
        padding: 2rem;
    }

    .form-control,
    .form-select {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .input-group .form-control {
        border-radius: 8px 0 0 8px;
    }

    .input-group .btn {
        border-radius: 0 8px 8px 0;
        border: 2px solid #dee2e6;
        border-left: none;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.15);
    }

    .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .profile-pic {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .profile-pic:hover {
        transform: scale(1.05);
    }

    .profile-pic-edit {
        position: absolute;
        right: 0;
        bottom: 0;
        background: rgba(13, 110, 253, 0.9);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .profile-pic-edit:hover {
        background: rgba(13, 110, 253, 1);
        transform: scale(1.1);
    }

    .country-code {
        font-weight: normal;
        color: #6c757d;
    }

    .modal-content {
        border: none;
        border-radius: 15px;
    }

    .modal-header {
        border-radius: 15px 15px 0 0;
    }

    .was-validated .form-control:invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
</style>

<script>
    function toggleEditMode() {
        const viewMode = document.getElementById('profile-view');
        const editMode = document.getElementById('profile-form');

        if (viewMode.classList.contains('d-none')) {
            viewMode.classList.remove('d-none');
            editMode.classList.add('d-none');
        } else {
            viewMode.classList.add('d-none');
            editMode.classList.remove('d-none');
        }
    }

    function showChangePassword() {
        const changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        changePasswordModal.show();
    }

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = event.currentTarget.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.getElementById('profilePicInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (!validTypes.includes(file.type)) {
                document.querySelector('.profile-pic-error').style.display = 'block';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePicView').src = e.target.result;
                document.querySelector('.profile-pic-error').style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    function validatePasswordForm() {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (newPassword !== confirmPassword) {
            document.getElementById('confirmPassword').setCustomValidity('Passwords do not match');
            return;
        }

        document.getElementById('confirmPassword').setCustomValidity('');
        showSuccessMessage('Password changed successfully!');
        const changePasswordModal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
        changePasswordModal.hide();

        // Reset form
        document.getElementById('password-form').reset();
        document.getElementById('password-form').classList.remove('was-validated');
    }

    function showSuccessMessage(message) {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        document.getElementById('successMessage').textContent = message;
        successModal.show();
    }

    function showErrorMessage(message) {
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        document.getElementById('errorMessage').textContent = message;
        errorModal.show();
    }
</script>

<?php include 'masterpage/footer.php'; ?>