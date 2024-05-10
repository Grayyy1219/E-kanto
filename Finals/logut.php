<?php
include("connect.php");
$query = mysqli_query($con, "UPDATE currentuser SET username = '0' WHERE userid = 1");

if ($query) {
    echo '<script>alert("Logout successfully");</script>';
    echo '<script>window.location.href = "Landingpage.php";</script>';
} else {
    echo "Update failed: " . mysqli_error($con);
    echo '<script>window.location.href = "Landingpage.php";</script>';
}
