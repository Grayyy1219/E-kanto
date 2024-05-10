<link rel="stylesheet" href="css/stylemain.css">
<?php
include 'header.php';
include 'connect_db.php';
include 'query.php';

$selectedItems = $_GET['selectedItems'];

$_SESSION['selectedItems'] = $selectedItems;
$Quantity = $_GET['Quantity'];
$_SESSION['Quantity'] = $Quantity;
$Price = $_GET['Price'];
$_SESSION['Price'] = $Price;
$getSelectedItemsQuery = "SELECT ItemID, ItemName, ItemImage, Quantity, Price FROM products WHERE ItemID = ?";
$stmt = mysqli_prepare($conn, $getSelectedItemsQuery);
mysqli_stmt_bind_param($stmt, "i", $selectedItems);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo "Error retrieving selected products: " . mysqli_error($conn);
    exit();
}
$getProductQuery = "SELECT * FROM products WHERE ItemID = '$selectedItems' LIMIT 1";
$productResult = mysqli_query($conn, $getProductQuery);
$productRow = mysqli_fetch_assoc($productResult);
$product_id = $selectedItems;
$availableQuantity = $productRow['Quantity'];
if ($Quantity > $availableQuantity) {
    $quantity = $availableQuantity;
    echo "<div style='text-align: center; padding: 20px;'>";
    echo "<img src='image/no.png' alt='Error Image' style='width: 300px; height: 300px;'>";
    echo "<p style='font-size: 54px; font-weight: bold; margin-top: 10px;'>Apologies, we only have {$availableQuantity} units available in stock.<br>Perhaps some are already in your cart. </p>";
    echo "<a href='Landing page.php' style='text-decoration: none; color: #9e22ff; font-size: 30px; display: block; margin-top: 10px;'>Home</a>";
    echo "</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="stylez.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .checkout-container {
            width: 70%;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-sizing: border-box;
            margin-top: 20px;
        }

        .item-summary {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .item-image {
            width: 80px;
            height: 80px;
            margin-right: 10px;
        }

        .checkout-button {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="checkout-container">
        <?php
        $totalPurchaseValue = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $totalPrice = $Quantity * $row['Price'];
            $totalPurchaseValue += $totalPrice;
            $_SESSION['Price'] = $totalPurchaseValue;
            ?>
            <div class="item-summary">
                <img src="<?= $row['ItemImage']; ?>" alt="Product Image" class="item-image">
                <p>
                    <?= $row['ItemName']; ?> - Quantity:
                    <?= $Quantity; ?> - Total Price: PHP
                    <?= $row['Price'];?>
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

    <script>
        function checkout() {
            window.location.href = "payment3.php";
        }
    </script>
</body>

</html>