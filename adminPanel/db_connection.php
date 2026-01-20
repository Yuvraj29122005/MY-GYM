<?php
$servername = "localhost";
$username = "root";        // adjust as needed
$password = "";            // adjust as needed
$dbname = "krish"; // replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
