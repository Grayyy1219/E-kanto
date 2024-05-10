<?php
$queryUser = mysqli_query($con, "SELECT * FROM currentuser WHERE UserID = 1");
$rowUser = mysqli_fetch_assoc($queryUser);

$location = $rowUser["profile"];
$username = $rowUser["username"];
$FName = $rowUser["FName"];
$LName = $rowUser["LName"];
$email = $rowUser["email"];
$address = $rowUser['address'];
$phone = $rowUser['phone'];



if ($username != 0) {
    $queryUser = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $password = $rowUser["password"];
    $verification = $rowUser["verification"];
    $block = $rowUser["block"];

    $on = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
    $rowon = mysqli_fetch_assoc($on);
    $UserID = $rowon["UserID"];
    $query69 = mysqli_query($con, "SELECT COUNT(cart_id) AS count from cart WHERE customer_id = $UserID");
    $row69 = mysqli_fetch_assoc($query69);
    $cartcount = $row69["count"];
    $query70 = mysqli_query($con, "SELECT COUNT(id) AS count from borrow WHERE customer_name = '$FName $LName' and returned !=1");
    $row70 = mysqli_fetch_assoc($query70);
    $borrowcount = $row70["count"];
} else {
    $verification = '0';
    $block = '0';
}
$queryProfile = mysqli_query($con, "SELECT profile FROM currentuser WHERE username = '$username'");
$rowProfile = mysqli_fetch_assoc($queryProfile);
$location = $rowProfile["profile"];

$queryPage = mysqli_query($con, "SELECT * FROM page WHERE ItemID IN (1, 2, 3, 4, 5)");
while ($rowPage = mysqli_fetch_assoc($queryPage)) {
    if ($rowPage["ItemID"] == 1) {
        $logo = $rowPage["value"];
    } elseif ($rowPage["ItemID"] == 2) {
        $companyname = $rowPage["value"];
    } elseif ($rowPage["ItemID"] == 3) {
        $backgroundimg = $rowPage["value"];
    } elseif ($rowPage["ItemID"] == 4) {
        $backgroundcolor = $rowPage["value"];
    } elseif ($rowPage["ItemID"] == 5) {
        $color = $rowPage["value"];
    }
}
for ($i = 1; $i <= 4; $i++) {
    $_SESSION["slide$i"] = "slide$i";
    ${"slide$i"} = $_SESSION["slide$i"];
}
$querycompanyname = mysqli_query($con, "select * from page where ItemID = 2");
$rowcompanyname = mysqli_fetch_assoc($querycompanyname);
$companyname = $rowcompanyname["value"];
$querybackgroundcolor = mysqli_query($con, "select * from page where ItemID = 4");
$rowbackgroundcolor = mysqli_fetch_assoc($querybackgroundcolor);
$backgroundcolor = $rowbackgroundcolor["value"];

$queryAdmin = mysqli_query($con, "SELECT * FROM users WHERE UserID = '1'");
$rowUser2 = mysqli_fetch_assoc($queryAdmin);
$hashedadminpassword = $rowUser2["password"];
$alocation = $rowUser2["profile"];
$ausername = $rowUser2["username"];
$aFName = $rowUser2["FName"];
$aLName = $rowUser2["LName"];
$aemail = $rowUser2["email"];
$aaddress = $rowUser2['address'];
$aphone = $rowUser2['phone'];