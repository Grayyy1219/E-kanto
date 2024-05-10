
<?php
include 'header.php';
// Check if the user is logged in and if the user ID is set in the session
if (isset($_SESSION['user_id'])) {
    $UserID = $_SESSION['user_id'];
} else {
    // Redirect to the login page or handle the case where the user is not logged in
    header("Location: login.php");
    exit();
}
include 'connect_db.php';
$getCartItemsQuery = "SELECT cart.cart_id, products.ItemID  , products.ItemName, products.ItemImage, cart.quantity, products.price
                     FROM cart
                     INNER JOIN products ON cart.ItemID   = products.ItemID  
                     WHERE cart.customer_id = $UserID";
$result = mysqli_query($conn, $getCartItemsQuery);


$totalCartValue = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cart.css">
    <title>User Cart</title>
</head>

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
<body>
    <section class="wrapper">
        <div class="body2">
            <h1>Your Cart</h1>
            <div class="cart-container">
                <form method="post" action="" id="cartForm" enctype="multipart/form-data">
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $totalPrice = $row['quantity'] * $row['price'];
                        $totalCartValue += $totalPrice;
                        ?>
                        <div class="cart-item">
                            <input type="checkbox" name="selectedItems[]" value="<?= $row['cart_id']; ?>"
                                onchange="updateTotal(this)">
                            <img src="<?= $row['ItemImage']; ?>" alt="Product Image" class="cart-item-image">
                            <div class="cart-item-details">
                                <p>
                                    <?= $row['ItemName']; ?>
                                </p>
                                <p>Quantity:
                                    <?= $row['quantity']; ?>
                                </p>
                                <?php
                                echo "<p class='total-price'>Total Price: PHP $totalPrice</p>";
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="cart-total">
                        <button type="submit" name="deleteSelected" formaction="deleteCartItem.php"
                            onclick="return confirmAction()">Remove
                            Selected</button>
                        <button type="submit" name="buySelected" formaction="processCartAction.php">Buy Now</button>
                        <p>Total Selected Item Price: <span id="totalCartValue">PHP
                                <?= $totalCartValue = 0;
                                $totalCartValue; ?>.00
                            </span></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        function confirmAction() {
            var checkboxes = document.getElementsByName('selectedItems[]');

            // Check if no checkbox is selected
            var checkedBoxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
            if (checkedBoxes.length === 0) {
                alert("Please select an item to proceed to checkout.");
                return false;
            } else {
                if (confirm("Would you like to confirm the deletion of selected items?")) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        function updateTotal(checkbox) {
            var checkboxes = document.getElementsByName('selectedItems[]');
            var total = 0;

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    var totalPriceElement = checkboxes[i].closest('.cart-item').querySelector('.total-price');
                    var totalPriceText = totalPriceElement.textContent.trim();

                    var totalPrice = parseFloat(totalPriceText.replace(/[^\d]/g, ''));
                    total += totalPrice;
                }
            }

            document.getElementById('totalCartValue').textContent = total.toFixed(2);
        }
    </script>
</body>

</html>