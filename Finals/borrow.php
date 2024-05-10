<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/borrow.css">
    <link rel="stylesheet" href="css/header.css">
</head>

<body>
    <?php
    include("connect.php");
    include("query.php");
    include("header.php");
    include("popups.php");
    $BookID = $_POST['BookID'];
    $checkBorrowedQuery = "SELECT COUNT(*) AS num_borrowed FROM borrow WHERE customer_name = '$FName $LName' AND book_id = $BookID AND returned = 0";
    $checkBorrowedResult = mysqli_query($con, $checkBorrowedQuery);
    $numBorrowed = mysqli_fetch_assoc($checkBorrowedResult)['num_borrowed'];
    if ($numBorrowed == 0) {
        // Include the file with the logic
        $bookTitle = $author = $dueDate = '';
        $borrowPeriod = 3;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve user input
            $bookTitle = $_POST["Title"];
            $author = $_POST["Author"];
            $quantity = $_POST["Quantity"];


            // Check if the user can borrow more books
            if ($borrowcount < 3) {
                if ($quantity > 0) {
                    $dueDate = date("Y-m-d", strtotime("+3 days"));
                    echo "<section class='wrapper' id='w1'>
                            <br><br>
                            <h1>Borrow Book</h1>
                            <form method='post' action=''>
                                <label for='bookTitle'>Book Title:</label>
                                <input type='text' name='bookTitle' value='" . htmlspecialchars($bookTitle) . "' readonly>
                                <label for='author'>Author:</label>
                                <input type='text' name='author' value='" . htmlspecialchars($author) . "' readonly>
                                <label>Borrow Period: $borrowPeriod days</label>
                                <label for='dueDate'>Due Date:</label>
                                <input type='text' name='dueDate' value='" . htmlspecialchars($dueDate) . "' readonly>
                                <button type='submit' formaction='borrow2.php'>Borrow</button>
                            </form>
                        </section>";
                } else {
                    echo "<script>alert('This book has no available stock')</script>";
                    echo "<script>window.history.back();</script>";
                }

            } else {
                echo '<script>alert("You have reached the maximum limit of borrowed books \n(Maximum of 3)")</script>';
                echo '<script>window.location.href = "display_borrowed.php";</script>';
            }
        }
    } else {
        echo '<script>alert("You have already borrowed this book."); window.location.href = "display_borrowed.php";</script>';
    }
    ?>



</body>

</html>