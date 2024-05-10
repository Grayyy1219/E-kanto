<?php
session_start();
include("connect_db.php");

if (isset($_POST['email'], $_POST['password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM customer_info WHERE email_address = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        if ($row['blocked'] == 1) {
            $alertMessage = "Your account is blocked. Please contact our customer service for assistance.";
            header("Location: blocked.html?alert=" . urlencode($alertMessage));
            exit();
        }

        if (password_verify($password, $row['password'])) {
            $alertMessage = "Login successful!";
            $_SESSION['user_id'] = $row['customer_id'];
            $_SESSION['user_email'] = $row['email_address'];
            header("Location: Landing-Page.php?alert=" . urlencode($alertMessage));
            exit();
        } else {
            $alertMessage = "Incorrect password. Please try again.";
        }
    } else {
        $alertMessage = isset($row) ? "User not found. Please check your email address." : "User not found. Please check your email address. " . mysqli_error($conn);
    }

    header("Location: Sign-In.html?alert=" . urlencode($alertMessage));
    exit();
}
?>
