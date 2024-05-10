<?php
include 'connect.php';
include 'query.php';
session_start();

if (!isset($_SESSION['selectedItems'])) {
    echo "No items selected for purchase.";
    exit();
}

$selectedItems = $_SESSION['selectedItems'];

$getSelectedItemsQuery = "SELECT books.BookID, books.Title, books.BookImage, cart.Quantity, books.Price
                         FROM cart
                         INNER JOIN books ON cart.BookID = books.BookID
                         WHERE cart.customer_id = ?
                         AND cart.cart_id IN ($selectedItems)";
$stmt = mysqli_prepare($con, $getSelectedItemsQuery);
mysqli_stmt_bind_param($stmt, "i", $UserID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo "Error retrieving selected items: " . mysqli_error($con);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/payment.css">
</head>

<body>
    <?php
    include("header.php");
    include("popups.php");
    ?>
    <section>
        <div class="wrapper" id="w3">
            <div class="checkout-container">
                <?php
                $totalPurchaseValue = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $totalPrice = $row['Quantity'] * $row['Price'];
                    $totalPurchaseValue += $totalPrice;
                    ?>
                    <div class="item-summary">
                        <img src="<?= $row['BookImage']; ?>" alt="Product Image" class="item-image">
                        <p>
                            <?= $row['Title']; ?> - Quantity:
                            <?= $row['Quantity']; ?> - Total Price: PHP
                            <?= $totalPrice; ?>.00
                        </p>
                    </div>
                    <?php
                }
                ?>
                <div class="checkout-button">
                    <button
                        style="font-weight:bold; font-size:20px; background-color: forestgreen; color: white; padding: 20px; border: none; border-radius: 5px;"
                        onclick="checkout()">Checkout</button>
                    <p>Total Purchase Value:</p>
                    <p style="font-weight: bold; font-size: larger; color: green;"> PHP
                        <?= $totalPurchaseValue; ?>.00
                    </p>
                </div>
            </div>
        </div>
    </section>


    <script>
        function checkout() {
            window.location.href = "payment.php";
        }
    </script>

</body>

</html>