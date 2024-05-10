<?php
include("connect.php");

$fname = $_POST['txtfname'];
$lname = $_POST['txtlname'];
$username = $_POST['txtusername'];
$password = $_POST['txtpassword'];
$email = $_POST['txtemail'];
$profile = 'upload/currentuser/new.png';
$verification = '0';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if the username or email already exists in the database
$checkQuery = "SELECT username, email FROM users WHERE username = ?";
$checkStmt = mysqli_prepare($con, $checkQuery);
mysqli_stmt_bind_param($checkStmt, "s", $username);
mysqli_stmt_execute($checkStmt);
mysqli_stmt_store_result($checkStmt);

if (mysqli_stmt_num_rows($checkStmt) > 0) {
    // User already exists
    mysqli_stmt_close($checkStmt);
    mysqli_close($con);
    echo '<script>alert("Username or email already exists. Please choose a different one.");</script>';
    echo '<script>window.location.href = "blockuser.php";</script>';
} else {
    // User doesn't exist, proceed with the insertion
    mysqli_stmt_close($checkStmt);

    $sql = "INSERT INTO users (Fname, LName, username, password, email, profile, verification) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $fname, $lname, $username, $hashedPassword, $email, $profile, $verification);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($con);

    echo '<script>alert("Signup Successfully!");</script>';
    echo '<script>window.location.href = "blockuser.php";</script>';
}
