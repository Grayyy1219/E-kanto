<?php
include("connect.php");
include("query.php");
if (isset($_POST["submit"])) {
    // Check if variables are set
    if (isset($_POST['currentpass'], $_POST['newpass'], $password, $username, $con)) {
        $currentPass = mysqli_real_escape_string($con, $_POST['currentpass']);
        $newPass = mysqli_real_escape_string($con, $_POST['newpass']);

        // Hash the current password and compare
        if (password_verify($currentPass, $password)) {
            // Update the password using prepared statement
            $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($con, "UPDATE users SET password = ? WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "ss", $hashedNewPass, $username);

            if (mysqli_stmt_execute($stmt)) {
                echo '<script>alert("Password successfully changed");</script>';
                echo '<script>window.location.href = "Landingpage.php#Page";</script>';
            } else {
                echo '<script>alert("Error updating password");</script>';
                echo '<script>window.history.back();</script>';
            }

            mysqli_stmt_close($stmt);
        } else {
            echo '<script>alert("Incorrect current password");</script>';
            echo '<script>window.history.back();</script>';
        }
    } else {
        echo '<script>alert("Invalid input");</script>';
        echo '<script>window.history.back();</script>';
    }
}