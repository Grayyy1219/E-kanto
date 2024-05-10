
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="E-kanto.css">
</head>

<body>
    <a href="Landing-Page.html">
        <img src="logo.png" alt="" class="headLogo" />
    </a>
    <div class="container">
        <div class="box">
            <div class="input">
                <form action="verify_process.php" method="post">
                    <h1>Email Verification</h1>
                    <b>Verification Code</b>
                    <input type="text" name="verification_code" class="Sname" required>
                    <input type="hidden" name="verification_code" value="<?php echo isset($_SESSION['verify_code']) ? $_SESSION['verify_code'] : ''; ?>">
                    <input type="hidden" name="email_address" value="<?php echo isset($_SESSION['mailAd']) ? $_SESSION['mailAd'] : ''; ?>">
                    <input type="submit" name="code" value="Verify">
                </form>                
            </div>
            <p>Enter the verification code sent to your email.</p>
        </div>
    </div>
</body>

<footer>
    <a href="#" class="link">Conditions of Use</a>
    <a href="#" class="link">Privacy Notice</a>
    <a href="#" class="link">Help</a>
    <br>© 1996-2023, E-kanto or its affiliate
</footer>

</html>
