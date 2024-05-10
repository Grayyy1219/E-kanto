<link rel="stylesheet" href="css/forgetpass.css">
<div class="forget">
    <div class="white"></div>
    <?php
    include("connect.php");
    include("query.php");
    echo " <div id='bg' class='image-container'>
                <img src='$backgroundimg'>
            </div>
            <div class='logoname'>
            <img src='$logo' alt='' class='logo' width='250'>
            <p><strong>$companyname</strong></p>
            </div>";
    ?>
    <form action="forget.php" class="settings" method="post" enctype="multipart/form-data">
        <div class="enter-email">
            <div class="text-wrapper">Forget Password</div>
            <p class="div">Enter your registered email below</p>
            <div class="group">
                <div class="overlap">
                    <div class="text-wrapper-2">Email address</div>
                    <div class="overlap-group-wrapper">
                        <div class="overlap-group">
                            <div class="rectangle">
                                <input type="text" class="inputname" name="email" placeholder="example@gmail.com" pattern='.*\.com' required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divregistarion">
                <input class="submitbtn" type="submit" value="Send Reset link">
            </div>
        </div>
    </form>

</div>