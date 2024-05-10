<?php
include("connect.php");
include("query.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Finals\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\Finals\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Finals\PHPMailer\src\SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'noreply.thebookhaven@gmail.com';
    $mail->Password   = 'glyt mguu ymqy noks';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    // Recipients
    $mail->setFrom("$email", 'The Book Haven');
    $mail->addAddress("$email", '');

    // Generate a unique verification code 
    $verificationCode = rand(10000, 99999);
    $sql = "UPDATE users SET verification_code = '$verificationCode' WHERE email = '$email'";
    mysqli_query($con, $sql);
    // Include the verification code in the link
    $verificationLink = "http://localhost/Finals//verify.php?code=$verificationCode";

    // Set the email content
    $mail->isHTML(true);
    $mail->Subject = 'Confirm Your Book Haven Journey - Unlock the Magic!';
    $mail->Body    = "Thank you for creating an account with Book Haven! To ensure the security of your account, please verify your email address by clicking on the following link: <br> <b><a href='$verificationLink'>Verification Link</a></b> <br><br> If you did not create an account with Book Haven, please ignore this email.<br><br>Thank you for choosing Book Haven.";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    // Send the email
    $mail->send();

    // Store the verification code in the database
    // Add your database connection and update query here
    echo '<script>alert("Verification link sent to you email!");</script>';
    echo '<script>window.location.href = "landingpage.php";</script>';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
