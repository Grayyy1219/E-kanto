<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $publisher = $_POST["publisher"];
    $genre = $_POST["genre"];
    $forSale = $_POST["for_sale"];
    $price = isset($_POST["price"]) ? $_POST["price"] : null;
    $quantity = $_POST["quantity"];

    // Check if a new image file is uploaded
    if (isset($_FILES['bookImage']) && $_FILES['bookImage']['size'] > 0) {
        $name = $_FILES['bookImage']['name'];
        $tmp_name = $_FILES['bookImage']['tmp_name'];
        $location = "upload/books/$name";
        if (move_uploaded_file($tmp_name, $location)) {
            $bookImage = $location;  // Update $bookImage with the new file path
        } else {
            echo "Error uploading file.";
            exit;
        }
    } else {
        $bookImage = "default_image_path.jpg"; 
    }

    $query = "INSERT INTO books (Title, Author, Publisher, Genre, BookImage, Forsale, Price, Quantity) 
              VALUES ('$title', '$author', '$publisher', '$genre', '$bookImage', '$forSale', '$price', '$quantity')";

    if (mysqli_query($con, $query)) {
        echo '<script>alert("Book Created successfully!");</script>';
        echo "<script>window.location.href = 'admin.php#inventory';</script>";
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}
?>