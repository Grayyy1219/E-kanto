<?php
session_start();
include("connect_db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\E-kanto\Exception.php';
require 'C:\xampp\htdocs\E-kanto\PHPMailer.php';
require 'C:\xampp\htdocs\E-kanto\SMTP.php';

function send_verify($email, $userName, $verificationCode)
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
        $mail->Subject = 'Account Verification:';
        $mail->Body    = "Thank you, $userName, for registering with E-Kanto Shop Commerce. To validate and confirm your account, please enter the following code: <b>$verificationCode</b><br><br>
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

if (isset($_POST["submit"])) {
    $errors = array();
    $last = mysqli_real_escape_string($conn, $_POST['lastName']);
    $first = mysqli_real_escape_string($conn, $_POST['firstName']);
    $middle = mysqli_real_escape_string($conn, $_POST['middleName']);
    $email = mysqli_real_escape_string($conn, $_POST['emailAddress']);
    $contact = mysqli_real_escape_string($conn, $_POST['number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['c_password']);

    $_SESSION['mailAd'] = $email;
    $_SESSION['verify_code'] = $verificationCode;

    $sql = "SELECT * FROM customer_info WHERE email_address = '$email'";
    $result = mysqli_query($conn, $sql);
    $count_email = mysqli_num_rows($result);

    if ($count_email > 0) {
        array_push($errors, "Email Address Already exists!");
    }

    if (empty($last) || empty($first) || empty($middle) || empty($email) || empty($contact) || empty($password)) {
        array_push($errors, "All fields are required");
    }

    if (strlen($password) < 6 || strlen($password) > 16) {
        array_push($errors, "Password must be between 6 to 16 characters");
    }

    if ($password != $cpassword) {
        array_push($errors, "Passwords do not match");
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<script>
              window.location.href = 'Sign-Up.html';
              alert('$error');</script>";
        }
        // Redirect to the form to fix errors
        echo "<script>location.href = 'Sign-Up.html';</script>";
    } else {
        if ($password == $cpassword) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $verificationCode = substr(str_shuffle("0123456789"), 0, 10);

            $sql = "INSERT INTO customer_info (last_name, first_name, middle_name, email_address, contact_number, password, verification_code) 
                    VALUES ('$last', '$first', '$middle', '$email', '$contact', '$hash', '$verificationCode')";

            if (mysqli_query($conn, $sql)) {
                echo "Records added successfully.";

                // No need to perform the update here, as the verification code is already inserted during registration

                $emailSent = send_verify($email, "$first $last", $verificationCode);

                echo "<script>
                      let code = '$verificationCode';
                      if (confirm('Would you like to verify your email now?')) {
                            if ($emailSent) {
                                window.location.href = 'verifyy.php';
                            } else {
                                alert('Failed to send verification email. Please try again.');
                            }
                      } else {
                          window.location.href = 'Landing-Page.html';
                      }
                  </script>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }
}
