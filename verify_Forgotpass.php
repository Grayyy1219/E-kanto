<?php
// Start the session
session_start();

// Check if there are any error messages
if (isset($_SESSION['error_message'])) {
    echo '<script>alert("' . $_SESSION['error_message'] . '");</script>';
    // Clear the error message from the session
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Verification</title>
    <link rel="stylesheet" href="E-kanto.css">
</head>

<body>
    <a href="Landing-Page.html">
        <img src="logo.png" alt="" class="headLogo" />
    </a>
    <div class="container">
        <div class="box">
            <div class="input">
                <form id="verifyResetPasswordForm" action="verifyforgetpassword_process.php" method="post">
                    <h1>Password Reset Verification</h1>
                    <label for="verificationCode">Verification Code</label>
                    <input type="text" name="verification_code" id="verificationCode" class="Sname" required>

                    <label for="newPassword">New Password</label>
                    <input type="password" name="newPassword" id="newPassword" required>

                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" required>

                    <input type="hidden" name="email_address" value="<?php echo isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : ''; ?>">

                    <input type="submit" name="verify_code" value="Verify">
                </form>
            </div>
            <p>Enter the verification code sent to your email and set a new password.</p>
        </div>
    </div>
    <footer>
        <a href="#" class="link">Conditions of Use</a>
        <a href="#" class="link">Privacy Notice</a>
        <a href="#" class="link">Help</a>
        <br>© 1996-2023, E-kanto or its affiliate
    </footer>

    <script>
        function resetPassword() {
            // Submit the form when the button is clicked
            document.getElementById("verifyResetPasswordForm").submit();
        }
    </script>
</body>

</html>
