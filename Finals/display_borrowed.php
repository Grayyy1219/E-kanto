<?php
include("connect.php");
include("query.php");

$selectQuery = "SELECT books.*, borrow.borrow_date, borrow.due_date, borrow.returned, borrow.penalty_paid
                FROM books
                JOIN borrow ON books.BookID = borrow.book_id
                WHERE borrow.customer_name = '$FName $LName'
                ORDER BY borrow.returned ASC";
$result = mysqli_query($con, $selectQuery);
?>

<html>

<head>
    <title>The Book Haven</title>
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/borrow2.css">
    <link rel="icon" href="Image/logo.ico">
    <style>
        /* Add any additional styling here */
        .penalty-form {
            margin-top: 10px;
        }

        .penalty-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .penalty-form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .penalty-form p {
            margin-bottom: 10px;
            font-size: 14px;
            color: #888;
        }

        .penalty-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .penalty-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php include("header.php");
    include("popups.php"); ?>
    <section>
        <div class="wrapper" id="w3">
            <h2>Borrowed Books</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Book Cover</th>
                    <th>Title</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Action</th>
                </tr>
                <?php while ($book = mysqli_fetch_assoc($result)): ?>
                    <tr id='book_row_<?= $book['BookID'] ?>' class='<?= $book['returned'] ? 'returned-book' : '' ?>'>
                        <td>
                            <?= $book['BookID'] ?>
                        </td>
                        <td><img src='<?= $book['BookImage'] ?>' alt='<?= $book['Title'] ?>' width='50'></td>
                        <td>
                            <?= $book['Title'] ?>
                        </td>
                        <td>
                            <?= $book['borrow_date'] ?>
                        </td>
                        <td>
                            <?= $book['due_date'] ?>
                        </td>
                        <td style="max-width: 40px;">
                            <?php if ($book['returned'] == 1): ?>
                                Returned
                                <form method='post' action='remove_book.php' style="margin-top: 5px;">
                                    <input type='hidden' name='BookID' value='<?= $book['BookID'] ?>'>
                                    <button type='submit' class='remove-button' name='remove_book'>Remove</button>
                                </form>
                            <?php elseif (strtotime($book['due_date']) < time()): ?>
                                <form class='penalty-form' id='payForm_<?= $book['BookID'] ?>'>
                                    <input type='hidden' name='BookID' value='<?= $book['BookID'] ?>'>
                                    <label for='penalty_amount_<?= $book['BookID'] ?>'>Enter Penalty Amount:</label>
                                    <?php
                                    $daysOverdue = ceil((time() - strtotime($book['due_date'])) / (60 * 60 * 24));
                                    $penaltyAmount = max(0, $daysOverdue * 100); // Adjust penalty calculation as needed
                                    ?>
                                    <input type='number' name='penalty_amount' id='penalty_amount_<?= $book['BookID'] ?>'
                                        value="<?= $penaltyAmount ?>" required readonly>
                                    <p>Penalty for
                                        <?= $daysOverdue ?> days overdue: PHP
                                        <?= $penaltyAmount ?>
                                    </p>
                                    <button type='button' onclick='payPenalty(<?= $book["BookID"] ?>)'>Pay Penalty</button>
                                </form>
                            <?php else: ?>
                                <form method='post' action='return_book.php'>
                                    <input type='hidden' name='BookID' value='<?= $book['BookID'] ?>'>
                                    <button type='submit' class='return-button' name='return_book'>Return</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>

            <script>

                function payPenalty(bookId) {
                    console.log("Attempting to pay penalty for book ID: " + bookId);

                    var penaltyAmountInput = document.getElementById('penalty_amount_' + bookId);
                    var penaltyAmount = penaltyAmountInput.value;

                    var confirmPayment = confirm("This book has an unpaid penalty of PHP " + penaltyAmount + ". Do you want to pay it now?");

                    if (confirmPayment) {
                        var formData = new FormData();
                        formData.append('book_id', bookId);
                        formData.append('penalty_amount', penaltyAmount);
                        formData.append('pay_penalty', true);

                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "pay_penalty.php", true);
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 4) {
                                if (xhr.status == 200) {
                                    console.log("Response from pay_penalty.php: " + xhr.responseText);
                                    // Add any additional handling based on the response if needed
                                    if (xhr.responseText === "Payment successful") {
                                        alert("Your payment has been successfully processed. Thank you for your prompt settlement.");
                                         location.reload();
                                    } else {
                                        alert(xhr.responseText); // Show an alert with the error message
                                    }
                                } else {
                                    console.error("Error in pay_penalty.php: " + xhr.statusText);
                                }
                            }
                        };
                        xhr.send(formData);
                    }
                }
            </script>
        </div>
    </section>
</body>

</html>