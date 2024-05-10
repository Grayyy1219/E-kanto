
<?php
include 'connect_db.php';

session_start();

// Check if the user is logged in and if the user ID is set in the session
if (isset($_SESSION['user_id'])) {
    $UserID = $_SESSION['user_id'];
} else {
    // Redirect to the login page or handle the case where the user is not logged in
    header("Location: login.php");
    exit();
}
$productName = mysqli_real_escape_string($conn, $_POST['Itemname']);
$quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

$getProductQuery = "SELECT * FROM products WHERE ItemName = '$productName' LIMIT 1";
$productResult = mysqli_query($conn, $getProductQuery);

if (!$productResult) {
    echo "Error retrieving product details: " . mysqli_error($con);
    exit();
}

$productRow = mysqli_fetch_assoc($productResult);
$product_id = $productRow['ItemID'];
$totalQuantity = $productRow['Quantity'];

$checkCartQuery = "SELECT * FROM cart WHERE customer_id = $UserID AND ItemID  = $product_id";
$checkCartResult = mysqli_query($conn, $checkCartQuery);

$quantityInCart = 0;

if (mysqli_num_rows($checkCartResult) > 0) {
    $row = mysqli_fetch_assoc($checkCartResult);
    $quantityInCart = $row['quantity'];
}

$availableQuantity = $totalQuantity - $quantityInCart;

if ($quantity > $availableQuantity) {
    $quantity = $availableQuantity;
    echo '<script>alert("Apologies, we only have '.$availableQuantity.' units available in stock.\nAlso Try check.");</script>';
    echo '<script>window.location.href = "cart.php";</script>';
    exit();
}
if ($quantityInCart > 0) {
    $newQuantity = $quantityInCart + $quantity;
    $updateCartQuery = "UPDATE cart SET quantity = $newQuantity WHERE customer_id = $UserID AND ItemID  = $product_id";

    if (mysqli_query($conn, $updateCartQuery)) {
        echo "<script>alert('Item successfully added to your cart!');</script>";
        echo '<script>window.location.href = "cart.php";</script>';
    } else {
        echo "Error updating product quantity in the cart: " . mysqli_error($conn);
    }
} else {
    $insertCartQuery = "INSERT INTO cart (customer_id, ItemID , quantity) VALUES ($UserID, $product_id, $quantity)";

    if (mysqli_query($conn, $insertCartQuery)) {
        echo "<script>alert('Item successfully added to your cart!');</script>";
        echo '<script>window.location.href = "cart.php";</script>';
    } else {
        echo "Error adding product to the cart: " . mysqli_error($conn);
    }
}
