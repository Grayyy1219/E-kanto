<?php
// Retrieve data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Process and save the data to the database
$adminUsername = "adminmain"; // Replace with the actual username of your admin
$currentPassword = $data['currentPassword'];
$newPassword = $data['newPassword'];

include 'connectdb.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate the current password (replace with your actual validation logic)
$sqlCheckPassword = "SELECT * FROM admin_users WHERE username = '$adminUsername'";
$result = $conn->query($sqlCheckPassword);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedPassword = $row['password'];

    // Verify the current password
    if ($currentPassword !== $storedPassword) {
        echo "Incorrect current password.";
    } else {
        // Current password is correct, update the password
        // For simplicity, we're assuming the password is stored in plain text (not recommended)
        $sqlUpdatePassword = "UPDATE admin_users SET password = '$newPassword' WHERE username = '$adminUsername'";

        if ($conn->query($sqlUpdatePassword) === TRUE) {
            echo "Password updated successfully!";
        } else {
            echo "Error updating password: " . $sqlUpdatePassword . "<br>" . $conn->error;
        }
    }
} else {
    echo "User not found.";
}

$conn->close();
?>
