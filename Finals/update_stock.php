<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["productId"]) && isset($_POST["newQuantity"])) {
    $productId = $_POST["productId"];
    $newQuantity = $_POST["newQuantity"];

    $updateQuery = "UPDATE Books SET Quantity = $newQuantity WHERE BookID = $productId";

    if (mysqli_query($con, $updateQuery)) {
        echo "Stock updated successfully";
    } else {
        echo "Error updating stock: " . mysqli_error($con);
    }
} else {
    echo "Invalid request";
}

?>