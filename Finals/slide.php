<?php
include("connect.php");
if ($_POST["submit"]) {
    $slideindex = $_POST["slideindex"];
    $name = $_FILES['myfile']['name'];
    $tmp_name = $_FILES['myfile']['tmp_name'];
    if ($name) {
        $location = "upload/slideshow/$name";
        move_uploaded_file($tmp_name, $location);
        $query = mysqli_query($con, "UPDATE slideshow set imagelocation='$location' where SlideID='$slideindex'");
        if ($query) {
            echo '<script>alert("Successfully Change Slide");</script>';
            echo '<script>window.location.href = "admin.php";</script>';
        } else {
            echo '<script>alert("Failed to Change Slide");</script>';
            echo '<script>window.location.href = "admin.php";</script>';
        }
    }
}
?>

