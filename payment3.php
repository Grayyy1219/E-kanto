<?php
include 'header.php';
include 'connect_db.php';
include 'query.php';
if (!isset($_SESSION['selectedItems'])) {
    echo "No products selected for purchase.";
    exit();
}

$selectedItems = $_SESSION['selectedItems'];
$Quantity = $_SESSION['Quantity'];
$totalPurchaseValue = $_SESSION['Price'];
$getSelectedItemsQuery = "SELECT ItemId, ItemName, ItemImage, Quantity, Price FROM products WHERE ItemId = $selectedItems";
$result = mysqli_query($conn, $getSelectedItemsQuery);

if (!$result) {
    echo "Error retrieving selected products: " . mysqli_error($conn);
    exit();
}


while ($row = mysqli_fetch_assoc($result)) {
    $totalPurchaseValue = $Quantity * $totalPurchaseValue;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentMode = isset($_POST['paymentMode']) ? $_POST['paymentMode'] : null;
    
    $saveOrderQuery = "INSERT INTO orders (customer_id, product_id, order_date, total_amount, order_quantity)
        VALUES ($UserID, ?, CURRENT_TIMESTAMP, ?, ?)";

    $stmt = mysqli_prepare($conn, $saveOrderQuery);

    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "idd", $selectedItems, $totalPurchaseValue, $Quantity);

        // Insert each selected item as a separate order
        mysqli_data_seek($result, 0); // Reset result pointer
        while ($row = mysqli_fetch_assoc($result)) {
        
            mysqli_stmt_execute($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing order query: " . mysqli_error($conn);
    }
    $paymentMode = $_POST['paymentMode'];

    $savePaymentQuery = "INSERT INTO payment (order_id, customer_id, payment_mode, amount_paid) 
                         VALUES (?, ?, ?, ?)";

    $stmtPayment = mysqli_prepare($conn, $savePaymentQuery);

    if ($stmtPayment) {
        $orderId = mysqli_insert_id($conn);
        mysqli_stmt_bind_param($stmtPayment, "iisd", $orderId, $UserID, $paymentMode, $totalPurchaseValue);

        $orderId = mysqli_insert_id($conn);
        $resultPayment = mysqli_stmt_execute($stmtPayment);

        if ($resultPayment) {
            echo "Payment details saved successfully.";
        } else {
            echo "Error saving payment details: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmtPayment);
    } else {
        echo "Error preparing payment details statement: " . mysqli_error($conn);
    }

    mysqli_data_seek($result, 0);

    while ($row = mysqli_fetch_assoc($result)) {
        $productId = $row['ItemId'];
        $deductQuantityQuery = "UPDATE products SET Quantity = Quantity - $Quantity WHERE ItemId = $productId";
        $resultDeductQuantity = mysqli_query($conn, $deductQuantityQuery);
        $saveOrderQuery = "UPDATE products SET Solds = Solds + $Quantity WHERE ItemId = $productId";
        $stmt = mysqli_query($conn, $saveOrderQuery);

        if (!$resultDeductQuantity) {
            echo "Error deducting quantity: " . mysqli_error($conn);
        }
    }

    $clearCartQuery = "DELETE FROM cart WHERE cart_id IN ($selectedItems) AND customer_id = $UserID";
    $resultClearCart = mysqli_query($conn, $clearCartQuery);

    if (!$resultClearCart) {
        echo "Error clearing cart: " . mysqli_error($conn);
    }
    echo "$selectedItems";
    header('Location: ordersuccessful3.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/payment.css">
</head>

<body>
    <section>
        <div class="wrapper" id="w3">
            <div class="payment-container">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="payment-form">
                    <div class="form-group"> <label for="paymentMode">Payment Mode:</label>
                        <select name="paymentMode" id="paymentMode" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <div class="payment-button">
                        <button
                            style="font-weight:bold; font-size:20px; background-color: forestgreen; color: white; padding: 20px; border: none; border-radius: 5px;"
                            type="submit">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>

</html>