<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>The Book Haven</title>
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/header.css">
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
            <form action="" method="post" enctype="multipart/form-data">
                <div class="wedit">
                    <div class="weditimg">
                        <?php
                        echo "<img id='profileImage' src='$location' alt='Profile Picture'>";
                        ?>
                        <label class="btn-upload-img">
                            Upload Profile Picture <input type="file" id="img" name="img" accept="image/*">
                        </label>
                    </div>
                    <div class="weform">
                        <div class="inweform">
                            <div class="weitem">
                                <div class="border">
                                    <p>First Name:</p>
                                </div>
                                <?php
                                echo "<input type='text' name='first_name' value='$FName'>";
                                ?>
                            </div>
                            <div class="weitem">
                                <div class="border">
                                    <p>Last Name:</p>
                                </div>
                                <?php
                                echo "<input type='text' name='last_name' value='$LName'>";
                                ?>
                            </div>
                            <div class="weitem">
                                <div class="border">
                                    <p>Address:</p>
                                </div>
                                <?php
                                echo "<input type='text' name='address' value='$address'>";
                                ?>
                            </div>
                        </div>
                        <div class="inweform">
                            <div class="weitem">
                                <div class="border">
                                    <p>Email:</p>
                                </div>
                                <?php
                                echo "<input type='text' name='email' id='emailInput' value='$email' style='text-transform: none;' pattern='.*\.com' title='Please enter a valid email address'>";
                                ?>

                            </div>
                            <div class="weitem">
                                <div class="border">
                                    <p>Phone:</p>
                                </div>
                                <?php
                                echo "<input type='tel' name='phone' value='$phone' pattern='^(\d{11}|\d{12}|\d{13})?$' title='Enter 11 or 13 digits'>";
                                ?>

                            </div>
                            <div class="weitem" style="opacity: 0;">
                                <div class="border">
                                    <p>Username:</p>
                                    <input type="text" name="username" value="">
                                </div>
                            </div>
                            <label class="btn-save">
                                <div class="btnsave">
                                    Save Changes <input formaction="updateuser.php" type="submit" name="submit">
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