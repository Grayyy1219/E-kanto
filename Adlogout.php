<?php
session_start(); // Start or resume a session

// Check if the user is already logged in
if (isset($_SESSION['admin_id'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Send a JSON response indicating successful logout
    echo json_encode(["message" => "Logout successful"]);
    exit();
} else {
    // If the user is not logged in, send a JSON response indicating that
    echo json_encode(["message" => "User not logged in"]);
    exit();
}
?>
