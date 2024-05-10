<div class="navi">
    <header>
        <?php
        echo "<a href='Landingpage.php#home'><img src='$logo' width='50'></a>
            <div class='logoname'>
                <a href='Landingpage.php#home'>
                    <p><strong>$companyname</strong></p>
                </a>
            </div>";
        ?>
        <div class="header">
            <a href="Landingpage.php#Books">Category</a>
            <a href="Landingpage.php#Trending">Trending</a>
            <a href="Landingpage.php#Browse">Browse</a>
            <?php
            $query = mysqli_query($con, "select * from currentuser where UserID = '1'");
            $row = mysqli_fetch_assoc($query);
            $location = $row["profile"];
            $username = $row["username"];
            $FName = $row["FName"];
            $LName = $row["LName"];
            $email = $row["email"];
           
            if ($username == "0") {
                echo "<div class='out'>
                    <a class='loginb'>Log In</a>
                    <a class='signupb'>Sign Up</a>
                    </div>";
            } else {

                echo "<div class='profile'>
                    <img src='$location' width='40' height='40' id='currentuser'>
                    <div id='inout'>
                       <p class='name'>$FName $LName</p>
                        <p>$email</p>
                    </div>
                        <div class='carts'>
                        <a href='cart.php'><img src='Image/shopping-cart.png' width='33' id='currentuser'><div class='cartcount'>$cartcount</div></a>
                        <a href='display_borrowed.php'><img src='Image/book.png' width='33'><div class='cartcount'>$borrowcount</div></a>
                    </div>
                    
                </div>
                <div>
                    <a  onclick='showSettingsPopup()'><img src='Image/setting.png' width='25' id='profile'></a>
                    </div>";
            }
            ?>
        </div>
    </header>
</div>