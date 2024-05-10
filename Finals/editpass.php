<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>The Book Haven</title>
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/editpass.css">
    <link rel="icon" href="Image/logo.ico">

    <?php
    include("connect.php");
    include("query.php");
    echo "<style>
        body {
            background-color: $backgroundcolor;
        }
        .fade-overlay {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0), $backgroundcolor);
    </style>";
    ?>
</head>

<body>
    <?php 
    include("header.php");
    include("popups.php"); 
    ?>
    <div class="popup-overlay"></div>
    <div id="spopup-overlay"></div>
    <div id="SettingsPopup" class="spopup">
        <div class="popup-content">
            <span class="close" onclick="closeSettingsPopup()"><b>&times;</b></span>
            <div class="sdiv">
                <form action="view.php" class="settings" method="post" enctype="multipart/form-data">
                    <?php
                    echo "<div class='profileimg'><p><img  src='$location' width='200 height='200'></p><br><br>";
                    echo "<p class='name'><b>" . $FName . " " . $LName . "</b></p>";
                    echo "<p class='emaillink'>" . $email . "</p></div>";
                    ?>
                    <div class="ssbuttons">
                        <a href="edituser.php">
                            <div class="inbtn">
                                <img src="Image/resume.png" width="25">
                                <p>Edit Basic Information</p>
                            </div>
                        </a>
                        <div class="inbtn">
                            <img src="Image/security.png" width="25">
                            <p>Change Password</p>
                        </div>
                        <div class="inbtn">
                            <img src="Image/book.png" width="25">
                            <p>Check Borrowed book</p>
                        </div>
                        <br><br>
                        <a href="logut.php">
                            <div class="inbtn">
                                <img src="Image/logout.png" width="25">
                                <input type="submit" formaction="logut.php" name="submit" value="Log Out">
                            </div>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <section></a>
        <div class="wrapper" id="w1">
            <form action="changepass.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="wedit">
                    <div class="weform">
                        <div class="inweform">
                            <div>
                                <p>Current Password:</p>
                                <div class="weitem">
                                    <input type='password' name='currentpass' value='' required>
                                </div>
                            </div>
                            <div>
                                <p>New Password:</p>
                                <div class="weitem">
                                    <input type='password' id='newpass' name='newpass' class="password-input" value='' required>
                                </div>
                            </div>
                            <div>
                                <p>Confirm Password:</p>
                                <div class="weitem">
                                    <input type='password' id='confirmpass' name='confirmpass' class="password-input" value='' required>
                                </div>
                            </div>
                            <label class="btn-save">
                                <div class="btnsave">
                                    Save Changes <input type="submit" name="submit">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <footer id="footer">
        <h5 style="color: rgba(255, 255, 255, 0.574);">FOLLOW OUR SOCIAL MEDIA</h5>
        <ul class="icons">
            <li><a href="https://www.facebook.com/lance.s02" class="social facebook"></a></li>
            <li><a href="https://twitter.com/LanceMusngi" class="social twitter"></a></li>
            <li><a href="https://www.instagram.com/lnc_grysn/" class="social instagram"></a></li>
        </ul>


        <ul class="copyright">
            <li><a href="Landingpage.html">THE BOOK HAVEN </a></li>
            <li>© 2023. ALL RIGHTS RESERVED.</li>
        </ul>
        <ul class="copyright">
            <li>
                <br>
                <p class="footer-links"><a href="#">Contact Us</a> I <a href="#">About Us</a> I <a href="#">Privacy</a></p> <br><br>
            </li>
        </ul>
    </footer>
    <script>
        function validateForm() {
            var newPassword = document.getElementById('newpass').value;
            var confirmPassword = document.getElementById('confirmpass').value;
            var passwordInputs = document.querySelectorAll('.password-input');

            if (newPassword !== confirmPassword) {
                alert("New Password and Confirm Password must match!");
                passwordInputs.forEach(function(element) {
                    element.classList.add('password-mismatch');
                });
                return false; // prevent form submission
            } else {
                passwordInputs.forEach(function(element) {
                    element.classList.remove('password-mismatch');
                });
            }

            return true; // allow form submission
        }

        function closeSettingsPopup() {
            document.getElementById('SettingsPopup').style.display = 'none';
            var overlay = document.querySelector('.popup-overlay');
            overlay.style.opacity = 0;
            setTimeout(function() {
                overlay.style.display = 'none';
            }, 300);
        }

        function showSettingsPopup() {
            document.getElementById("SettingsPopup").style.display = "block";
            setTimeout(function() {
                document.getElementById("spopup-overlay").style.display = "block";
            }, 10);
        }

        function closeSettingsPopup() {
            document.getElementById("spopup-overlay").style.display = "none";
            document.getElementById("SettingsPopup").style.display = "none";
        }
        document.getElementById('img').addEventListener('change', function(event) {
            const fileInput = event.target;
            const profileImage = document.getElementById('profileImage');

            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>