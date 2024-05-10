<?php
session_start();
include 'connect.php';
include 'query.php';

if (!isset($_SESSION['selectedItems'])) {
    echo "No items selected for purchase.";
    exit();
}

$selectedItems = $_SESSION['selectedItems'];
$Quantity = $_SESSION['Quantity'];
$totalPurchaseValue = $_SESSION['Price'];
$getSelectedItemsQuery = "SELECT BookID, Title, BookImage, Quantity, Price FROM books WHERE BookId = $selectedItems";
$result = mysqli_query($con, $getSelectedItemsQuery);

if (!$result) {
    echo "Error retrieving selected items: " . mysqli_error($con);
    exit();
}


while ($row = mysqli_fetch_assoc($result)) {
    $totalPurchaseValue = $Quantity * $totalPurchaseValue;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentMode = isset($_POST['paymentMode']) ? $_POST['paymentMode'] : null;
    
    $saveOrderQuery = "INSERT INTO orders (customer_id, product_id, order_date, total_amount, order_quantity)
        VALUES ($UserID, ?, CURRENT_TIMESTAMP, ?, ?)";

    $stmt = mysqli_prepare($con, $saveOrderQuery);

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
        echo "Error preparing order query: " . mysqli_error($con);
    }
    $paymentMode = $_POST['paymentMode'];

    $savePaymentQuery = "INSERT INTO payment (order_id, customer_id, payment_mode, amount_paid) 
                         VALUES (?, ?, ?, ?)";

    $stmtPayment = mysqli_prepare($con, $savePaymentQuery);

    if ($stmtPayment) {
        $orderId = mysqli_insert_id($con);
        mysqli_stmt_bind_param($stmtPayment, "iisd", $orderId, $UserID, $paymentMode, $totalPurchaseValue);

        $orderId = mysqli_insert_id($con);
        $resultPayment = mysqli_stmt_execute($stmtPayment);

        if ($resultPayment) {
            echo "Payment details saved successfully.";
        } else {
            echo "Error saving payment details: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmtPayment);
    } else {
        echo "Error preparing payment details statement: " . mysqli_error($con);
    }

    mysqli_data_seek($result, 0);

    while ($row = mysqli_fetch_assoc($result)) {
        $productId = $row['BookID'];
        $deductQuantityQuery = "UPDATE books SET Quantity = Quantity - $Quantity WHERE BookID = $productId";
        $resultDeductQuantity = mysqli_query($con, $deductQuantityQuery);
        $saveOrderQuery = "UPDATE books SET Solds = Solds + $Quantity WHERE BookID = $productId";
        $stmt = mysqli_query($con, $saveOrderQuery);

        if (!$resultDeductQuantity) {
            echo "Error deducting quantity: " . mysqli_error($con);
        }
    }

    $clearCartQuery = "DELETE FROM cart WHERE cart_id IN ($selectedItems) AND customer_id = $UserID";
    $resultClearCart = mysqli_query($con, $clearCartQuery);

    if (!$resultClearCart) {
        echo "Error clearing cart: " . mysqli_error($con);
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
     <header >
        <a href="admin.php" class="ahead">
        <img src="Image\left-arrow.png" width="22">
            <h4>Go Back</h4>
        </a>
     </header>
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