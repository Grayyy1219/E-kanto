<?php
include("connect.php");

$targetUserId = $_POST['UserID'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$address = $_POST['address'];
$email = $_POST['email'];
$phone = $_POST['phone'];

if (isset($_POST["submit"])) {
    // Check if the file input is set and has data
    if ($_FILES['img']['size'] > 0) {
        $name = $_FILES['img']['name'];
        $tmp_name = $_FILES['img']['tmp_name'];
        $location = "upload/currentuser/$name";
        move_uploaded_file($tmp_name, $location);
    } else {
        // If no new image is uploaded, retain the existing image path
        $queryRetrieveImage = mysqli_query($con, "SELECT profile FROM users WHERE UserID = '$targetUserId'");
        if ($queryRetrieveImage && mysqli_num_rows($queryRetrieveImage) > 0) {
            $row = mysqli_fetch_assoc($queryRetrieveImage);
            $location = $row['profile'];
        }
    }

    // Check if the targetUserId exists in the database
    $queryRetrieveUsername = mysqli_query($con, "SELECT * FROM users WHERE UserID = '$targetUserId'");
    if ($queryRetrieveUsername && mysqli_num_rows($queryRetrieveUsername) > 0) {
        $row = mysqli_fetch_assoc($queryRetrieveUsername);
        $targetFName = $row['FName'];
        $targetLName = $row['LName'];

        // Update the user profile
        $queryUpdateUsers = mysqli_query($con, "UPDATE users SET profile = '$location', FName = '$first_name', LName = '$last_name', email = '$email', address ='$address', phone = '$phone' WHERE UserID = '$targetUserId'");

        if ($queryUpdateUsers) {
            echo '<script>alert("Profile updated successfully for User: ' . $targetFName . ' ' . $targetLName . '");</script>';
            echo '<script>window.location.href = "blockuser.php";</script>';
        } else {
            echo '<script>alert("Error updating profile. Please try again later.");</script>';
        }
    } else {
        echo '<script>alert("Error retrieving user information. Please try again later.");</script>';
    }
} else {
    echo '<script>alert("Error updating profile. Please try again later.");</script>';
    echo '<script>window.location.href = "edituser.php";</script>';
}
?>