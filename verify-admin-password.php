<?php
include("connectdb.php");

if (isset($_POST['adminPassword'])) {
    $adminUsername = 'adminmain'; // Replace with your actual admin username
    $enteredPassword = mysqli_real_escape_string($conn, $_POST['adminPassword']);

    $sql = "SELECT password FROM admin_users WHERE username = ?";

    // Using prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    
    // Binding parameters using bind_param
    $stmt->bind_param("s", $adminUsername);
    
    // Executing the prepared statement
    $stmt->execute();

    // Binding result variables
    $stmt->bind_result($storedPassword);

    // Fetching the result
    $stmt->fetch();

    if (!empty($storedPassword)) {
        // Compare plain text passwords
        if ($enteredPassword === $storedPassword) {
            echo "success";
        } else {
            echo "failure";
        }
    } else {
        echo "failure";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "failure";
}
?>
