<?php
session_start(); // ADD THIS AT THE TOP

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer via Composer

$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $otp = rand(100000, 999999);
        $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        $stmt = $conn->prepare("UPDATE users SET otp=?, otp_expiry=? WHERE email=?");
        $stmt->bind_param("sss", $otp, $expires, $email);
        $stmt->execute();

        // ✅ Store email in session
        $_SESSION['reset_email'] = $email;

        // Send OTP email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'krishhalai83@gmail.com';
            $mail->Password   = 'hqmi tilq dkky yhgi'; // Use app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('krishhalai83@gmail.com', 'Gym Management');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for Password Reset';
            $mail->Body    = "
                <p>Hello,</p>
                <p>Your OTP for password reset is: <strong>$otp</strong></p>
                <p>This OTP is valid for 10 minutes.</p>
                <p>If you didn't request this, ignore the message.</p>
            ";

            $mail->send();

            // ✅ Redirect to OTP verification page
            header("Location: verify_otp.php");
            exit;

        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Mailer Error: {$mail->ErrorInfo}</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Email not found.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="forgot-password-container py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="forgot-password-form bg-white p-5 rounded-lg shadow-lg">
                    <div class="text-center mb-5">
                        <h1 class="fw-bold text-primary">Forgot Password</h1>
                        <p class="text-muted">Enter your email to reset your password</p>
                    </div>

                    <?= $message ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control form-control-lg" name="email" id="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                            <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                            <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                        </button>

                        <div class="text-center">
                            <p class="text-muted">Remember your password? <a href="login.php" class="text-primary text-decoration-none">Login</a></p>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    form.classList.add('was-validated');
                }
            }, false);
        });
    })();
</script>
</body>
</html>
