<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>The Book Haven</title>
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="icon" href="Image/logo.ico">
</head>
<?php
include("connect.php");
$UserID = $_GET['UserID'];
$queryUser = mysqli_query($con, "SELECT * FROM users WHERE UserID = $UserID");
$rowUser = mysqli_fetch_assoc($queryUser);
$location = $rowUser["profile"];
$FName  = $rowUser["FName"];
$LName = $rowUser["LName"];
$address = $rowUser["address"];
$email = $rowUser["email"];
$phone = $rowUser["phone"];
?>

<body>
    <section>
        <div class="wrapper" id="w1">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="UserID" value="<?php echo "$UserID";?>";>
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
                                    Save Changes <input formaction="aupdateuser2.php" type="submit" name="submit">
                                </div>
                            </label>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </section>
    <script>
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