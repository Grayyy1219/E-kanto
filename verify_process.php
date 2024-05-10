<?php
session_start();
include("connect_db.php");

if (isset($_POST['code'])) {
    // Retrieve verification code and email from the form submission
    $userVerificationCode = mysqli_real_escape_string($conn, $_POST['verification_code']);
    $email = mysqli_real_escape_string($conn, $_POST['email_address']);

    // Retrieve the stored verification code from the database
    $sql = "SELECT verification_code FROM customer_info WHERE email_address = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Check if the verification code is set before accessing it
        $storedVerificationCode = isset($row['verification_code']) ? $row['verification_code'] : null;
        $userVerificationCode = trim($userVerificationCode);
        $storedVerificationCode = trim($storedVerificationCode);

        if ($userVerificationCode === $storedVerificationCode) {
            // Verification codes match, proceed with account verification
            if ($userVerificationCode === $storedVerificationCode) {
                // Verification codes match, proceed with account verification
            
                // Record the verification time
                $verificationTime = date('Y-m-d H:i:s'); // Get the current date and time
                $updateSql = "UPDATE customer_info SET account_verification = '1', verification_time = '$verificationTime' WHERE email_address = '$email'";
                
                if (mysqli_query($conn, $updateSql)) {
    
                    echo "Email: $email, Verification Code: $userVerificationCode";

            echo "Verification successful!";
        } else {
            echo "Error updating verification time: " . mysqli_error($conn);
        }
   
            // Update account_verification status to 1 in the database
                    
            $updateSql = "UPDATE customer_info SET account_verification = '1' WHERE email_address = '{$_SESSION['mailAd']}'";
            mysqli_query($conn,$updateSql);
            echo "<script>
            window.location.href = 'Landing-Page.php';
            alert('Sucessfull Verification');</script>";
                $userVerificationCode = mysqli_real_escape_string($conn, $_POST['verification_code']);
                $email = mysqli_real_escape_string($conn, $_POST['email_address']);
                

            } else {
                echo "Error updating account verification status: " . mysqli_error($conn);
            }
        } else {
            echo "Invalid verification code. User entered: $userVerificationCode, Stored in database: $storedVerificationCode";
        }
    } else {
        echo "Error in database query: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>
