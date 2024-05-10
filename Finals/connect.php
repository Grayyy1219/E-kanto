<?php
    $con = mysqli_connect("localhost","root");
    if (!$con) {
        echo"Could not connect! ".mysqli_error();
    }
    mysqli_select_db($con,"finals");

?>