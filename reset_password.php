<?php
session_start();
// if (!isset($_SESSION['verified_reset']) || !isset($_SESSION['reset_email'])) {
//     die("Unauthorized access.");
// }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="col-md-6 offset-md-3 bg-white p-5 rounded shadow">
        <h3 class="text-center text-primary mb-4">Reset Your Password</h3>
        <form method="POST" action="update_password.php">
            <div class="form-group mb-3">
                <label>New Password</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="form-group mb-3">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required minlength="6">
            </div>
            <button type="submit" class="btn btn-success w-100">Reset Password</button>
        </form>
    </div>
</div>
</body>
</html>
