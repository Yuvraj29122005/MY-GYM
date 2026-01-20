<!-- verify_otp.php -->
<?php session_start();

// session_start();
$conn = new mysqli("localhost", "root", "", "krish");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $entered_otp = $_POST['otp'];

    $stmt = $conn->prepare("SELECT otp, otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if ($row['otp'] === $entered_otp && $row['otp_expiry'] > date("Y-m-d H:i:s")) {
            // OTP is correct and not expired
            $_SESSION['verified_reset'] = true;
            $_SESSION['reset_email'] = $email;
            header("Location: reset_password.php");
            exit;
        } else {
            $error = "Invalid or expired OTP.";
        }
    } else {
        $error = "Email not found.";
    }
}





?>
<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="col-md-6 offset-md-3 bg-white p-5 rounded shadow">
        <h3 class="text-center mb-4 text-primary">Verify OTP</h3>
        <form action="reset_password.php" method="POST">
            <div class="form-group mb-3">
                <label>Enter OTP</label>
                <input type="text" name="otp" class="form-control" required maxlength="6">
            </div>
            <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
        </form>
    </div>
</div>
</body>
</html>
