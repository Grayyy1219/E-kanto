<link rel="stylesheet" href="css/stylemain.css">
<?php
include 'connect.php';
include 'query.php';

$productName = mysqli_real_escape_string($con, $_POST['Title']);
$quantity = mysqli_real_escape_string($con, $_POST['quantity']);

$getProductQuery = "SELECT * FROM books WHERE Title = '$productName' LIMIT 1";
$productResult = mysqli_query($con, $getProductQuery);

if (!$productResult) {
    echo "Error retrieving product details: " . mysqli_error($con);
    exit();
}

$productRow = mysqli_fetch_assoc($productResult);
$product_id = $productRow['BookID'];
$totalQuantity = $productRow['Quantity'];

$checkCartQuery = "SELECT * FROM cart WHERE customer_id = $UserID AND BookID = $product_id";
$checkCartResult = mysqli_query($con, $checkCartQuery);

$quantityInCart = 0;

if (mysqli_num_rows($checkCartResult) > 0) {
    $row = mysqli_fetch_assoc($checkCartResult);
    $quantityInCart = $row['quantity'];
}

$availableQuantity = $totalQuantity - $quantityInCart;

if ($quantity > $availableQuantity) {
    $quantity = $availableQuantity; // Set quantity to available stock
    echo "<div style='text-align: center; padding: 20px;'>";
    echo "<img src='image/no.png' alt='Error Image' style='width: 300px; height: 300px;'>";
    echo "<p style='font-size: 54px; font-weight: bold; margin-top: 10px;'>Apologies, we only have {$availableQuantity} units available in stock.<br>Perhaps some are already in your cart. </p>";
    echo "<a href='cart.php' style='text-decoration: none; color: #4CAF50; font-size: 30px; display: block; margin-top: 10px;'>View Cart</a>";
    echo "</div>";
    exit();
}

if ($quantityInCart > 0) {
    $newQuantity = $quantityInCart + $quantity;
    $updateCartQuery = "UPDATE cart SET quantity = $newQuantity WHERE customer_id = $UserID AND BookID = $product_id";

    if (mysqli_query($con, $updateCartQuery)) {
        echo '<div style="text-align: center; padding: 20px;">';
        echo '<img src="image/yescart.png" alt="Success Image" style="width: 300px; height: 300px;">';
        echo '<p style="font-size: 54px; font-weight: bold; margin-top: 10px;">Item successfully added to your cart!</p>';
        echo '<a href="cart.php" style="text-decoration: none; color: #4CAF50; font-size: 30px; display: block; margin-top: 10px;">View Cart</a>';
        echo '</div>';
    } else {
        echo "Error updating product quantity in the cart: " . mysqli_error($con);
    }
} else {
    $insertCartQuery = "INSERT INTO cart (customer_id, BookID, quantity) VALUES ($UserID, $product_id, $quantity)";

    if (mysqli_query($con, $insertCartQuery)) {
        echo '<div style="text-align: center; padding: 20px;">';
        echo '<img src="image/yescart.png" alt="Success Image" style="width: 300px; height: 300px;">';
        echo '<p style="font-size: 54px; font-weight: bold; margin-top: 10px;">Item successfully added to your cart!</p>';
        echo '<a href="cart.php" style="text-decoration: none; color: #4CAF50; font-size: 30px; display: block; margin-top: 10px;">View Cart</a>';
        echo '</div>';
    } else {
        echo "Error adding product to the cart: " . mysqli_error($con);
    }
}
