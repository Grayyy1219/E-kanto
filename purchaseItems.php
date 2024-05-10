<?php
include 'header.php';
include 'connect_db.php';
include 'query.php';


if (!isset($_SESSION['selectedItems'])) {
    echo "No items selected for purchase.";
    exit();
}

$selectedItems = $_SESSION['selectedItems'];

$getSelectedItemsQuery = "SELECT products.ItemID, products.ItemName, products.ItemImage, cart.Quantity, products.Price
                         FROM cart
                         INNER JOIN products ON cart.ItemID = products.ItemID
                         WHERE cart.customer_id = ?
                         AND cart.cart_id IN ($selectedItems)";
$stmt = mysqli_prepare($conn, $getSelectedItemsQuery);
mysqli_stmt_bind_param($stmt, "i", $UserID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo "Error retrieving selected items: " . mysqli_error($conn);
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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        section {
            padding: 20px;
        }

        .wrapper {
            width: 80%;
            margin: 0 auto;
        }

        .checkout-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .item-summary {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
        }

        .item-image {
            max-width: 100px;
            max-height: 100px;
            margin-right: 15px;
            border-radius: 8px;
        }

        .checkout-button {
            text-align: center;
            padding: 20px;
        }

        button {
            font-weight: bold;
            font-size: 20px;
            background-color: forestgreen;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: darkgreen;
        }

        p {
            margin: 10px 0;
        }

        p.total-purchase-value {
            font-weight: bold;
            font-size: larger;
            color: green;
        }
    </style>
</head>

<body>
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
                        <img src="<?= $row['ItemImage']; ?>" alt="Product Image" class="item-image">
                        <p>
                            <?= $row['ItemName']; ?> - Quantity:
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