<?php
include("connect.php");
include("query.php");
// Check if the password is sent from the client
if (isset($_POST['password'])) {
    $enteredPassword = $_POST['password'];

    // Your logic to retrieve the hashed password from a database or another secure source

    // Verify the entered password against the stored hashed password
    if (password_verify($enteredPassword, $hashedadminpassword)) {
        echo "true"; // Password is correct
    } else {
        echo "false"; // Incorrect password
    }
} else {
    echo "error"; // Password not provided
}
