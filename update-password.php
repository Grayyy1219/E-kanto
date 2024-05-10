<?php
// Include the database connection file
include('connect_db.php');

// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if not logged in
    header("Location: Sign-In.html");
    exit();
}

// Retrieve user details from the database
$user_id = $_SESSION["user_id"];
$query = "SELECT * FROM customer_info WHERE customer_id = $user_id";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch user details as an associative array
    $userDetails = mysqli_fetch_assoc($result);
} else {
    // Handle the query error
    die("Database query error: " . mysqli_error($conn));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $currentPassword = mysqli_real_escape_string($conn, $_POST['current_password']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirmNewPassword = mysqli_real_escape_string($conn, $_POST['confirm_new_password']);

    // Verify the current password
    if (password_verify($currentPassword, $userDetails['password'])) {
        // Check if the new password matches the confirmed password
        if ($newPassword === $confirmNewPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateQuery = "UPDATE customer_info SET password = '$hashedPassword' WHERE customer_id = $user_id";
            $updateResult = mysqli_query($conn, $updateQuery);

            // Check if the update was successful
            if ($updateResult) {
                // Redirect with success message
                header("Location: account-settings.php?success=true");
                exit();
            } else {
                // Handle the query error
                die("Database query error: " . mysqli_error($conn));
            }
        } else {
            // Redirect with error message for password mismatch
            echo "<script>alert('Password Mismatch. Please enter the correct current password.');</script>";
            echo '<script>window.history.back();</script>';
            exit();
        }
    } else {
        // Redirect with error message for incorrect current password
        echo "<script>alert('Incorrect current password. Please enter the correct current password.');</script>";
        echo '<script>window.history.back();</script>';

        exit();
    }
}
?>
