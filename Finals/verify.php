<?php
// Include your database connection file
include("connect.php");

// Get the verification code from the URL
$verificationCode = $_GET['code'];

// Check if the verification code is not empty
if (!empty($verificationCode)) {
    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("UPDATE users SET verification = 1 WHERE verification_code = ?");
    $stmt->bind_param("s", $verificationCode);

    // Execute the update query
    if ($stmt->execute()) {
        echo '<script>alert("Your email has been successfully verified.\n\nWelcome to the Book Haven community!\nHappy reading!\n\nThank you for choosing Book Haven.");</script>';
        echo '<script>window.location.href = "landingpage.php";</script>';
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "Invalid verification code";
}

// Close the database connection
$con->close();
