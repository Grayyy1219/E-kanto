<?php
session_start();
include("connect_db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\E-kanto\Exception.php';
require 'C:\xampp\htdocs\E-kanto\PHPMailer.php';
require 'C:\xampp\htdocs\E-kanto\SMTP.php';

function send_reset_code($email, $verificationCode)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ekantoshopping@gmail.com';
        $mail->Password   = 'vnph zont jddv yite';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('ekantoshopping@gmail.com', 'E-Kanto Shopping Commerce');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Recovery';
        $mail->Body    = "You have requested to reset your password. Please use the following verification code to complete the process: <b>$verificationCode</b><br><br>
        <b>Do not reply, this is an automated message.</b>";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';

        return true; // Indicate successful email sending
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

        return false; // Indicate failure in email sending
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password'])) {
    $errors = array();
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT * FROM customer_info WHERE email_address = '$email'";
    $result = mysqli_query($conn, $sql);
    $count_email = mysqli_num_rows($result);

    if ($count_email > 0) {
        $verificationCode = substr(str_shuffle("0123456789"), 0, 10);

        // Update the verification code and email in the session
        $_SESSION['verify_code'] = $verificationCode;
        $_SESSION['reset_email'] = $email;

        // Update the verification code in the database
        $updateSql = "UPDATE customer_info SET verification_code = '$verificationCode' WHERE email_address = '$email'";
        if (mysqli_query($conn, $updateSql)) {
            // Use $_SESSION['reset_email'] for the email
            $emailSent = send_reset_code($_SESSION['reset_email'], $verificationCode);

            echo "<script>
                  if ($emailSent) {
                      alert('Recovery code has been sent to your email.');
                      window.location.href = 'verify_Forgotpass.php';
                  } else {
                      alert('Failed to send recovery code. Please try again.');
                      window.location.href = 'Forgotpass.html';
                  }
              </script>";
        } else {
            echo "Error updating verification code: " . mysqli_error($conn);
        }
    } else {
        echo '<script>alert("Email not found.");</script>';
    }

    mysqli_close($conn);
}
