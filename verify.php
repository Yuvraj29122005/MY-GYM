<?php
$conn = new mysqli("localhost", "root", "", "krish");

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verification_token = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $update = $conn->prepare("UPDATE users SET status = 'active', verification_token = '' WHERE email = ?");
        $update->bind_param("s", $email);
        if ($update->execute()) {
            echo "Email verified successfully. You can now <a href='login.php'>login</a>.";
        } else {
            echo "Error updating status.";
        }
    } else {
        echo "Invalid or expired verification link.";
    }
} else {
    echo "Invalid request.";
}
?>
