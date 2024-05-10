<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_select_db($conn, "admin_db");
?>
