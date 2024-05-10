<?php
include("connect.php");
include("query.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_book']) && isset($_POST['BookID'])) {
    // Get the book_id from the form data
    $book_id = $_POST['BookID'];

    $returnBookQuery = "UPDATE borrow SET returned = 1 WHERE book_id = $book_id AND customer_name = '$FName $LName'";
    
    $returnBookResult = mysqli_query($con, $returnBookQuery);


    if ($returnBookResult) {
        // Update the book quantity in the books table
        $updateQuantityQuery = "UPDATE books SET quantity = quantity + 1 WHERE BookID = $book_id";
        $updateQuantityResult = mysqli_query($con, $updateQuantityQuery);

        if ($updateQuantityResult) {
            // Redirect back to the borrowed books page with a success message
            header("Location: display_borrowed.php?returned=1");
            exit();
        } else {
            echo '<script>alert("Error updating book quantity: ' . mysqli_error($conn) . '");</script>';
        }
    } else {
        echo '<script>alert("Error returning book: ' . mysqli_error($con) . '");</script>';
    }
} else {
    echo '<script>alert("Invalid request.");</script>';
}
?>
