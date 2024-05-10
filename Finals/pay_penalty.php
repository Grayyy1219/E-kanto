<?php
include("connect.php");
include("query.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_penalty'])) {

    include("connect.php");

    $bookId = $_POST['book_id'];
    $penaltyAmount = $_POST['penalty_amount'];

    $updateQuery = "UPDATE borrow SET penalty_paid = $penaltyAmount, returned = 1 WHERE book_id = $bookId AND customer_name = '$FName $LName'";
    $result = mysqli_query($con, $updateQuery);

    if ($result) {
        echo "Payment successful";
    } else {
        echo "Payment failed. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>