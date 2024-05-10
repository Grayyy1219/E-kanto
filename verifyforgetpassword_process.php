<?php
session_start();
include("connect_db.php");

// Ensure that session_start() is at the top of your script
// and there is no output before it.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_code'])) {
    // Retrieve and sanitize form data
    $enteredVerificationCode = mysqli_real_escape_string($conn, $_POST['verification_code']);
    $storedVerificationCode = isset($_SESSION['verify_code']) ? $_SESSION['verify_code'] : '';
    $email = isset($_POST['email_address']) ? $_POST['email_address'] : '';
    $newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

    // Validate form data
    $errorMessages = array();

    // Perform validation as needed
    if ($enteredVerificationCode !== $storedVerificationCode) {
        $errorMessages[] = "Invalid verification code. Please try again.";
    }

    if (empty($newPassword) || empty($confirmPassword)) {
        $errorMessages[] = "New password and confirm password are required.";
    }

    if ($newPassword !== $confirmPassword) {
        $errorMessages[] = "Passwords do not match.";
    }

    // Password strength check
    if (strlen($newPassword) < 6) {
        $errorMessages[] = "Password must be at least 6 characters long.";
    }

  
    if (empty($errorMessages)) {
        // Continue with updating the password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $updatePasswordSql = "UPDATE customer_info SET password = ? WHERE email_address = ?";
        $stmt = mysqli_prepare($conn, $updatePasswordSql);
        mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $email);

        if (mysqli_stmt_execute($stmt)) {
            // Password updated successfully with delayed redirect
            echo "<script>
                    alert('Password updated successfully.');
                    setTimeout(function() {
                        window.location.href = 'Sign-In.html';
                    }, 1000);
                  </script>";

            // Unset session variables
            unset($_SESSION['verify_code']);
            exit(); // Ensure script stops here after redirect
        } else {
            // Error updating password
            echo "<p>Error updating password: " . mysqli_error($conn) . "</p>";
        }
    } else {
        // Display validation errors on the page
        foreach ($errorMessages as $error) {
            echo "<p>Error: $error</p>";
        }

        // Redirect back to the password reset verification form
        header("Location: verify_Forgotpass.php");
        exit(); // Ensure script stops here after redirect
    }
} else {
    // Handle cases where the form wasn't submitted
    echo "<p>Form not submitted.</p>";
}
?>