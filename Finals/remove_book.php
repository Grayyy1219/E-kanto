<?php
include("connect.php");
include("query.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_book'])) {
    $book_id = $_POST['BookID'];
    $customer_name = "$FName $LName";

    $ownershipQuery = "SELECT customer_name FROM borrow WHERE book_id = $book_id AND customer_name = '$customer_name'";
    $ownershipResult = mysqli_query($con, $ownershipQuery);

    if ($ownershipResult && mysqli_num_rows($ownershipResult) > 0) {
        $removeQuery = "DELETE FROM borrow WHERE book_id = $book_id AND customer_name = '$customer_name'";
        $removeResult = mysqli_query($con, $removeQuery);

        if ($removeResult) {
            echo "<script>alert('The book has been successfully marked as removed'); window.location.href = 'display_borrowed.php';</script>";
        } else {
            echo "Error: Unable to mark the book as removed. " . mysqli_error($con);
        }
    } else {
        echo "Error: The book is not currently borrowed by $customer_name.";
    }
}
?>