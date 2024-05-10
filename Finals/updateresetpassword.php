<?php
include("connect.php");
include("query.php");

if (isset($_POST['password'], $_POST['cpassword'], $_POST['email'])) {
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    if ($password === $cpassword) {
        $hashedNewPass = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($con, "UPDATE users SET password = ? WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $hashedNewPass, $email);

        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Password successfully reset");</script>';
            echo '<script>window.location.href = "Landingpage.php#Page";</script>';
        } else {
            echo '<script>alert("Error resetting password");</script>';
            echo '<script>window.history.back();</script>';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo '<script>alert("Passwords do not match");</script>';
        echo '<script>window.history.back();</script>';
    }
} else {
    echo '<script>alert("Invalid input");</script>';
    echo '<script>window.history.back();</script>';
}
