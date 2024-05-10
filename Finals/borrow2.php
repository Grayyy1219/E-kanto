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
    // Include necessary files
    include("connect.php");
    include("query.php");
    include("header.php");
    include("popups.php");

    // Initialize variables
    $confirmationMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $bookTitle = $_POST["bookTitle"];
        $author = $_POST["author"];
        $availableCopies = 5;
        $borrowPeriod = 3;
        if ($availableCopies > 0) {
            $dueDate = date("Y-m-d", strtotime("+$borrowPeriod days"));
            $sql = "INSERT INTO borrow (book_id, customer_name, borrow_date, due_date) VALUES ((SELECT BookID FROM books WHERE title = '$bookTitle' AND author = '$author' LIMIT 1),'$FName $LName', CURDATE(), DATE_ADD(CURDATE(), INTERVAL $borrowPeriod DAY))";
            $sql3 = "UPDATE books SET Quantity = Quantity - 1, Solds = Solds + 1 WHERE title = '$bookTitle'";
            if ($con->query($sql) === TRUE && $con->query($sql3) === TRUE) {
                $confirmationMessage = "Thank you for borrowing!<br>Book: $bookTitle<br>Due Date: $dueDate<br>Please return on time.";
            } else {
                $confirmationMessage = "Error: " . $sql . "<br>" . $con->error;
            }
        } else {
            $confirmationMessage = "Sorry, the selected book is currently unavailable.";
        }
    }
    ?>
    <section class="wrapper" id="w1">

        <?php
        if (!empty($confirmationMessage)) {
            echo "<h1>Confirmation</h1>";
            echo "<p class='p2'>$confirmationMessage</p>";
        }
        ?>
        <br>
        <br>
        <div class="btnb">
            <a href="Landingpage.php"><button>Home</button></a>
            <a href="display_borrowed.php"><button style="background-color: #5dae87;">My Books</button></a>
        </div>
    </section>
</body>

</html>