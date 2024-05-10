<?php
include("connect.php");

$targetUserId = 1;
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$address = $_POST['address'];
$email = $_POST['email'];
$phone = $_POST['phone'];

if (isset($_POST["submit"])) {
    if ($_FILES['img']['size'] > 0) {
        $name = $_FILES['img']['name'];
        $tmp_name = $_FILES['img']['tmp_name'];
        $location = "upload/currentuser/$name";
        move_uploaded_file($tmp_name, $location);
    } else {
        $queryRetrieveProfile = mysqli_query($con, "SELECT profile FROM currentuser WHERE userid = $targetUserId");
        $row = mysqli_fetch_assoc($queryRetrieveProfile);
        $location = $row['profile'];
    }

    $queryUpdate = mysqli_query($con, "UPDATE currentuser SET profile = '$location', FName = '$first_name', LName = '$last_name', Email = '$email', address ='$address', phone = '$phone' WHERE userid = $targetUserId");

    if ($queryUpdate) {
        $queryRetrieveUsername = mysqli_query($con, "SELECT * FROM currentuser WHERE userid = $targetUserId");

        if ($queryRetrieveUsername && mysqli_num_rows($queryRetrieveUsername) > 0) {
            $row = mysqli_fetch_assoc($queryRetrieveUsername);
            $targetUsername = $row['username'];
            $targetFName = $row['FName'];
            $targetLName = $row['LName'];

            $queryUpdateUsers = mysqli_query($con, "UPDATE users SET profile = '$location', FName = '$first_name', LName = '$last_name', Email = '$email', address ='$address', phone = '$phone' WHERE username = '$targetUsername'");

            echo '<script>alert("Profile updated successfully for User: ' . $targetFName . ' ' . $targetLName . '");</script>';
            echo '<script>window.location.href = "Landingpage.php";</script>';
        } else {
            echo '<script>alert("Error retrieving username. Please try again later.");</script>';
        }
    } else {
        echo '<script>alert("Error updating profile. Please try again later.");</script>';
    }
} else {
    echo '<script>alert("Error updating profile. Please try again later.");</script>';
    echo '<script>window.location.href = "edituser.php";</script>';
}
