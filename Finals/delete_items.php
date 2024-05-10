<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedItems'])) {
    $selectedItems = implode(",", $_POST['selectedItems']);

    // Perform the delete operation in the database
    $deleteQuery = "DELETE FROM Books WHERE BookID IN ($selectedItems)";
    $deleteResult = mysqli_query($con, $deleteQuery);

    if ($deleteResult) {
        echo '<script>alert("Username or email already exists. Please choose a different one.");</script>';
        echo "Success";
    } else {
        echo "Error";
        echo '<script>alert("Username or email already exists. Please choose a different one.");</script>';
    }
} else {
    echo '<script>alert("Username or email already exists. Please choose a different one.");</script>';
    echo "Invalid request";
}
?>