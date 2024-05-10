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
    <header>
        <a href="admin.php" class="ahead">
            <img src="Image\left-arrow.png" width="22">
            <h4>Go Back</h4>
        </a>
    </header>
    <section></a>
        <div class="wrapper" id="w1">
            <form action="achangepass.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
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
                                    <input type='password' id='newpass' name='newpass' class="password-input" value=''
                                        required>
                                </div>
                            </div>
                            <div>
                                <p>Confirm Password:</p>
                                <div class="weitem">
                                    <input type='password' id='confirmpass' name='confirmpass' class="password-input"
                                        value='' required>
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
    <script>
        function validateForm() {
            var newPassword = document.getElementById('newpass').value;
            var confirmPassword = document.getElementById('confirmpass').value;
            var passwordInputs = document.querySelectorAll('.password-input');

            if (newPassword !== confirmPassword) {
                alert("New Password and Confirm Password must match!");
                passwordInputs.forEach(function (element) {
                    element.classList.add('password-mismatch');
                });
                return false; // prevent form submission
            } else {
                passwordInputs.forEach(function (element) {
                    element.classList.remove('password-mismatch');
                });
            }

            return true; // allow form submission
        }

        function closeSettingsPopup() {
            document.getElementById('SettingsPopup').style.display = 'none';
            var overlay = document.querySelector('.popup-overlay');
            overlay.style.opacity = 0;
            setTimeout(function () {
                overlay.style.display = 'none';
            }, 300);
        }

        function showSettingsPopup() {
            document.getElementById("SettingsPopup").style.display = "block";
            setTimeout(function () {
                document.getElementById("spopup-overlay").style.display = "block";
            }, 10);
        }

        function closeSettingsPopup() {
            document.getElementById("spopup-overlay").style.display = "none";
            document.getElementById("SettingsPopup").style.display = "none";
        }
        document.getElementById('img').addEventListener('change', function (event) {
            const fileInput = event.target;
            const profileImage = document.getElementById('profileImage');

            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>