<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/borrow.css">
    <link rel="stylesheet" href="css/header.css">
    <title>Order Success</title>
    <style>
    
        #img {
            max-width: 300px;
            height: auto;
        }

        .success-message {
            margin-top: 20px;
            font-size: 20px;
            color: green;
        }

        #home-link {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
     <header >
        <a href="admin.php" class="ahead">
        <img src="Image\left-arrow.png" width="22">
            <h4>Go Back</h4>
        </a>
     </header>
    <?php
    include 'connect.php';
    session_start();
    include 'query.php';
    ?>
    <section class="wrapper" id="w1">
        <?php
        $selectedItems = $_SESSION['selectedItems'];
        $Quantity = $_SESSION['Quantity'];
        $totalPurchaseValue = $_SESSION['Price'];
        $getSelectedItemsQuery = "SELECT BookID, Title, BookImage, Quantity, Price FROM books WHERE BookId = $selectedItems";
        $result = mysqli_query($con, $getSelectedItemsQuery);
        while ($row = mysqli_fetch_assoc($result)) {
            $bookTitle = $row['Title'];
        }

        $confirmationMessage = "Thank you for Buying!<br>$bookTitle<br>Price: $totalPurchaseValue.00<br><br>Happy reading!<br>Thank you for choosing Book Haven..";

        if (!$result) {
            echo "Error retrieving selected items: " . mysqli_error($con);
            exit();
        }
        if (!empty($confirmationMessage)) {
            echo "<h1>Confirmation</h1>";
            echo "<p class='p2'>$confirmationMessage</p>";
        }
        ?>
        <br>
        <br>
        <div class="btnb">
            <a href="admin.php#inventory"><button>Home</button></a>
        </div>
    </section>

</body>

</html>