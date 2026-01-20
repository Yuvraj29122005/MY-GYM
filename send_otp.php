<?php
session_start();
include 'db_conn.php'; // Your DB connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 1) {
        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        $update = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
        $update->execute([$otp, $expiry, $email]);

        // Send OTP via email
        require 'PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Or your SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'krishhalai83@gmail.com';
        $mail->Password = 'hqmi tilq dkky yhgi';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('krishhalai83@gmail.com', 'My Gym');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP for Password Reset';
        $mail->Body = "Your OTP is: $otp. It will expire in 10 minutes.";

        if ($mail->send()) {
            $_SESSION['reset_email'] = $email;
            header("Location: verify_otp.php");
            exit();
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "<script>alert('Email not found'); window.location.href='forgot.php';</script>";
    }
}
?>
