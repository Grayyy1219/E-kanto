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
    <header >
        <a href="admin.php" class="ahead">
        <img src="Image\left-arrow.png" width="22">
            <h4>Go Back</h4>
        </a>
     </header>
    <section></a>
        <div class="wrapper" id="w1">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="wedit">
                    <div class="weditimg">
                        <?php
                        echo "<img id='profileImage' src='$alocation' alt='Profile Picture'>";
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
                                echo "<input type='text' name='first_name' value='$aFName'>";
                                ?>
                            </div>
                            <div class="weitem">
                                <div class="border">
                                    <p>Last Name:</p>
                                </div>
                                <?php
                                echo "<input type='text' name='last_name' value='$aLName'>";
                                ?>
                            </div>
                            <div class="weitem">
                                <div class="border">
                                    <p>Address:</p>
                                </div>
                                <?php
                                echo "<input type='text' name='address' value='$aaddress'>";
                                ?>
                            </div>
                        </div>
                        <div class="inweform">
                            <div class="weitem">
                                <div class="border">
                                    <p>Email:</p>
                                </div>
                                <?php
                                echo "<input type='text' name='email' id='emailInput' value='$aemail' style='text-transform: none;' pattern='.*\.com' title='Please enter a valid email address'>";
                                ?>

                            </div>
                            <div class="weitem">
                                <div class="border">
                                    <p>Phone:</p>
                                </div>
                                <?php
                                echo "<input type='tel' name='phone' value='$aphone' pattern='^(\d{11}|\d{12}|\d{13})?$' title='Enter 11 or 13 digits'>";
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
                                    Save Changes <input formaction="aupdateuser.php" type="submit" name="submit">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
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