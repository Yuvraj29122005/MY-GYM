<?php include 'sidebar.php'; ?>
<?php include_once 'db_connection.php'; ?>

<?php


if (!$conn->query($create_table_query)) {
    die("Error creating table: " . $conn->error);
}

// Insert default values if table is empty
$check_empty = "SELECT COUNT(*) as count FROM admin_settings";
$result = $conn->query($check_empty);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $default_password = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_default = "INSERT INTO admin_settings (gym_name, email, phone, address, open_time, close_time, password) 
                      VALUES ('My Gym', 'gym@example.com', '1234567890', '123 Gym Street', '06:00:00', '22:00:00', '$default_password')";
    
    if (!$conn->query($insert_default)) {
        die("Error inserting default values: " . $conn->error);
    }
}

// Fetch admin settings from database
$settings_query = "SELECT * FROM admin_settings WHERE id = 1";
$settings_result = $conn->query($settings_query);
$settings = $settings_result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_general'])) {
        // Update general settings
        $gym_name = mysqli_real_escape_string($conn, $_POST['gymName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']); 
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $open_time = mysqli_real_escape_string($conn, $_POST['openTime']);
        $close_time = mysqli_real_escape_string($conn, $_POST['closeTime']);

        $update_query = "UPDATE admin_settings SET 
            gym_name = '$gym_name',
            email = '$email',
            phone = '$phone', 
            address = '$address',
            open_time = '$open_time',
            close_time = '$close_time'
            WHERE id = 1";

        if ($conn->query($update_query)) {
            // Fetch updated settings immediately
            $settings_query = "SELECT * FROM admin_settings WHERE id = 1";
            $settings_result = $conn->query($settings_query);
            $settings = $settings_result->fetch_assoc();
            
            $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                          Settings updated successfully!
                          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                       </div>";
        } else {
            $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                          Error updating settings: " . $conn->error . "
                          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                       </div>";
        }
    }

    if (isset($_POST['update_password'])) {
        // Update password
        $current_password = mysqli_real_escape_string($conn, $_POST['currentPassword']);
        $new_password = mysqli_real_escape_string($conn, $_POST['newPassword']);
        
        // Verify current password
        $verify_query = "SELECT password FROM admin_settings WHERE id = 1";
        $result = $conn->query($verify_query);
        $row = $result->fetch_assoc();
        
        if (password_verify($current_password, $row['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $password_query = "UPDATE admin_settings SET password = '$hashed_password' WHERE id = 1";
            
            if ($conn->query($password_query)) {
                $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                              Password updated successfully!
                              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                           </div>";
            } else {
                $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                              Error updating password
                              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                           </div>";
            }
        } else {
            $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                          Current password is incorrect
                          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                       </div>";
        }
    }
}
?>

<div id="message-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <main class="col-md-12 px-md-4">    
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Settings</h1>
            </div>

            <div class="row">
                <!-- Settings Navigation -->
                <div class="col-lg-3">
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Settings Menu</h5>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#general" class="list-group-item list-group-item-action active d-flex align-items-center" data-bs-toggle="list">
                                <i class="fas fa-cog me-3"></i>General Settings
                            </a>
                            <a href="#security" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="list">
                                <i class="fas fa-shield-alt me-3"></i>Security
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Settings Content -->
                <div class="col-lg-9">
                    <div class="tab-content">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general">
                            <div class="card">
                                <div class="card-header bg-primary text-white d-flex align-items-center">
                                    <i class="fas fa-cog me-2"></i>
                                    <h5 class="mb-0">General Settings</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="" id="generalSettingsForm">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Gym Name</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-dumbbell"></i></span>
                                                        <input type="text" class="form-control" name="gymName" value="<?php echo htmlspecialchars($settings['gym_name']); ?>">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Contact Email</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($settings['email']); ?>">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Phone Number</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                        <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($settings['phone']); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Address</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                        <textarea class="form-control" name="address" rows="3"><?php echo htmlspecialchars($settings['address']); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Business Hours</label>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                                <input type="time" class="form-control" name="openTime" value="<?php echo htmlspecialchars($settings['open_time']); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                                <input type="time" class="form-control" name="closeTime" value="<?php echo htmlspecialchars($settings['close_time']); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" name="update_general" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Save Changes
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div class="tab-pane fade" id="security">
                            <div class="card">
                                <div class="card-header bg-primary text-white d-flex align-items-center">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <h5 class="mb-0">Security Settings</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="" id="securitySettingsForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">Change Password</label>
                                                    <div class="input-group mb-2">
                                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                        <input type="password" class="form-control" name="currentPassword" placeholder="Current Password" required>
                                                    </div>
                                                    <div class="input-group mb-2">
                                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                        <input type="password" class="form-control" name="newPassword" placeholder="New Password" required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                        <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm New Password" required>
                                                    </div>
                                                    <button type="submit" name="update_password" class="btn btn-primary">
                                                        <i class="fas fa-key me-2"></i>Change Password
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.alert {
    margin-bottom: 0;
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
$(document).ready(function() {
    // Handle general settings form submission
    $('#generalSettingsForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: window.location.href,
            data: $(this).serialize(),
            success: function(response) {
                // Show success message
                $('#message-container').html("<div class='alert alert-success alert-dismissible fade show' role='alert'>Settings updated successfully!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
                
                // Auto hide after 2 seconds
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 2000);
            },
            error: function() {
                $('#message-container').html("<div class='alert alert-danger alert-dismissible fade show' role='alert'>Error updating settings<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
                
                // Auto hide after 2 seconds
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 2000);
            }
        });
    });

    // Handle security settings form submission
    $('#securitySettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        // Check if passwords match
        const newPassword = $('input[name="newPassword"]').val();
        const confirmPassword = $('input[name="confirmPassword"]').val();
        
        if (newPassword !== confirmPassword) {
            $('#message-container').html("<div class='alert alert-danger alert-dismissible fade show' role='alert'>New passwords do not match!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
            
            // Auto hide after 2 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 2000);
            return;
        }

        $.ajax({
            type: 'POST',
            url: window.location.href,
            data: $(this).serialize(),
            success: function(response) {
                $('#message-container').html("<div class='alert alert-success alert-dismissible fade show' role='alert'>Password updated successfully!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
                $('#securitySettingsForm')[0].reset();
                
                // Auto hide after 2 seconds
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 2000);
            },
            error: function() {
                $('#message-container').html("<div class='alert alert-danger alert-dismissible fade show' role='alert'>Error updating password<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
                
                // Auto hide after 2 seconds
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 2000);
            }
        });
    });

    // Auto-hide alerts after 3 seconds
    $(document).on('shown.bs.alert', '.alert', function() {
        const $alert = $(this);
        setTimeout(function() {
            $alert.alert('close');
        }, 3000);
    });
});
</script>
