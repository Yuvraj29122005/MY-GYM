<?php
include 'masterpage/db_connect.php';

session_start();
// header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$email = $_SESSION['user'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$gender = $_POST['gender'] ?? '';
$new_email = $_POST['email'] ?? '';

// Validate input
if (empty($fullname) || empty($gender) || empty($new_email)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

// Check if email is being changed and if it already exists
if ($new_email !== $email) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND email != ?");
    $stmt->bind_param("ss", $new_email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // if ($result->num_rows > 0) {
    //     echo json_encode(['success' => false, 'message' => 'Email already exists']);
    //     header('Location: profile.php');
    //     exit();
    // }
}

// Update user profile
$stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, gender = ? WHERE email = ?");
$stmt->bind_param("ssss", $fullname, $new_email, $gender, $email);

if ($stmt->execute()) {
    // Update session if email was changed
    if ($new_email !== $email) {
        $_SESSION['user'] = $new_email;
    }
    // echo '<script> alert(Profile updated successfully); window.location.href="profile.php"</script>';
    header('Location: profile.php');
    // echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating profile']);
    header('Location: profile.php');
    exit();
}

$stmt->close();
$conn->close();
?>