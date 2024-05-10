<?php
include("connect.php");

if (isset($_POST["logof"])) {
    $name = $_FILES['logo']['name'];
    $tmp_name = $_FILES['logo']['tmp_name'];
    if ($name) {
        $location = "upload/page/$name";
        move_uploaded_file($tmp_name, $location);
        $query = mysqli_query($con, "UPDATE page set value ='$location' where ItemID = 1");
        echo '<script>alert("Successfully Change Logo");</script>';
        echo '<script>window.location.href = "admin.php#Page";</script>';
    }
} elseif (isset($_POST["companyf"])) {
    $companyName = mysqli_real_escape_string($con, $_POST['company']);
    $query = mysqli_query($con, "UPDATE page set value ='$companyName' where ItemID = 2");
    echo '<script>alert("Successfully Change Company Name");</script>';
    echo '<script>window.location.href = "admin.php#Page";</script>';
} elseif (isset($_POST["bgimgf"])) {
    $name = $_FILES['bgimg']['name'];
    $tmp_name = $_FILES['bgimg']['tmp_name'];
    if ($name) {
        $location = "upload/page/$name";
        move_uploaded_file($tmp_name, $location);
        $query = mysqli_query($con, "UPDATE page set value ='$location' where ItemID = 3");
        echo '<script>alert("Successfully Change Background Image");</script>';
        echo '<script>window.location.href = "admin.php#Page";</script>';
    }
} elseif (isset($_POST["bgcolorf"])) {
    $bgColor = mysqli_real_escape_string($con, $_POST['bgColor']);
    $query = mysqli_query($con, "UPDATE page set value ='$bgColor' where ItemID = 4");
    echo '<script>alert("Successfully Change Background Color");</script>';
    echo '<script>window.location.href = "admin.php#Page";</script>';
} elseif (isset($_POST["colorf"])) {
    $Color = mysqli_real_escape_string($con, $_POST['Color']);
    $query = mysqli_query($con, "UPDATE page set value ='$Color' where ItemID = 5");
    echo '<script>alert("Successfully Change Text Color");</script>';
    echo '<script>window.location.href = "admin.php#Page";</script>';
}
