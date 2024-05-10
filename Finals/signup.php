<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/stylemain.css">
<div id="SignupPopup" class="popup" style="display: block; opacity: 1;">
    <div class="popup-content">
        <span class="close" onclick="closeSignupPopup()"><b>&times;</b></span>
        <div class="form-box">
            <form action="createuser2.php" class="form" method="post" enctype="multipart/form-data"
                onsubmit="return validateForm()">
                <span class=" title">Sign Up</span>
                <span class="subtitle">Create a free account with your details.</span>
                <div class="form-container">
                    <div class="input-row">
                        <input type="text" class="input2" placeholder="First Name" name="txtfname" required>
                        <input type="text" class="input2" placeholder="Last Name" name="txtlname" required>
                    </div>
                    <input type="text" class="input2" placeholder="Username" name="txtusername" required>
                    <input type="password" class="input2" placeholder="Password" name="txtpassword" id="password"
                        required>
                    <input type='password' class='input2' placeholder='Confirm Password' name='txtcpassword'
                        id="confirmPassword" required>
                    <input type="email" class="input2" placeholder="Email" name="txtemail" required>
                </div>
                <input type="submit" class="submit" name="submit" value="Sign Up">
                <div class="form-section">
                    <p>Already have an account? <a href="#" class='sloginb'>Log In</a></p>
                </div>
            </form>

        </div>
    </div>
</div>