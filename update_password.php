<?php
session_start();
include 'adminPanel/db_connection.php';

// Check session
// if (!isset($_SESSION['reset_email']) || !isset($_SESSION['verified_reset'])) {
//     echo "<script>alert('Unauthorized access. Please request a new OTP.'); window.location.href='forgot.php';</script>";
//     exit();
// }

$email = $_SESSION['reset_email'];
$password = trim($_POST['password']);
$confirm = trim($_POST['confirm_password']);

// Validate input
if (empty($password) || empty($confirm)) {
    echo "<script>alert('Please fill in both password fields.'); window.location.href='reset_password.php';</script>";
    exit();
}

if ($password !== $confirm) {
    echo "<script>alert('Passwords do not match.'); window.location.href='reset_password.php';</script>";
    exit();
}

if (strlen($password) < 6) {
    echo "<script>alert('Password must be at least 6 characters long.'); window.location.href='reset_password.php';</script>";
    exit();
}

// Hash the password and update the user
$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ?, otp = NULL, otp_expiry = NULL WHERE email = ?");
$stmt->bind_param("ss", $hashed, $email);

if ($stmt->execute()) {
    // Clear session after successful reset
    unset($_SESSION['reset_email']);
    unset($_SESSION['verified_reset']);
    session_destroy();

    echo "<script>alert('Password reset successful! Please log in.'); window.location.href='login.php';</script>";
} else {
    echo "<script>alert('Error updating password. Please try again.'); window.location.href='reset_password.php';</script>";
}
?>
