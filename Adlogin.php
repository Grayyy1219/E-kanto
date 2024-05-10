<?php
session_start(); // Start or resume a session

// Connect to the database
$conn = mysqli_connect("localhost", "root", "");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_select_db($conn, "admin_db");

// Function to sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags($input));
}

if (isset($_POST["adUser"]) && isset($_POST["adPass"])) {
    $username = sanitizeInput($_POST["adUser"]);
    $password = sanitizeInput($_POST["adPass"]);

    // Query to check admin credentials
    $sql = "SELECT * FROM admin_users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // If a matching admin user is found
    if ($result->num_rows == 1) {
        // Set session variable to mark user as logged in
        $_SESSION['admin_id'] = $username;

        // Redirect to admin panel
        header("Location: Admin_Panel.php");
        exit(); // Ensure that no further code is executed after the redirect
    } else {
        // Redirect back to login page
        echo "<script>window.location.href='admin_login.html';</script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>