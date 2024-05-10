<?php
session_start();
include 'connect.php';
include 'query.php';

if (!isset($_SESSION['selectedItems'])) {
    echo "No items selected for purchase.";
    exit();
}

$selectedItems = $_SESSION['selectedItems'];

$getSelectedItemsQuery = "SELECT books.BookID, books.Title, books.price, cart.quantity 
                         FROM cart 
                         INNER JOIN books ON cart.BookID = books.BookID 
                         WHERE cart.customer_id = ? AND cart.cart_id IN ($selectedItems)";

$stmtGetSelectedItems = mysqli_prepare($con, $getSelectedItemsQuery);
mysqli_stmt_bind_param($stmtGetSelectedItems, "i", $UserID);
mysqli_stmt_execute($stmtGetSelectedItems);
$result = mysqli_stmt_get_result($stmtGetSelectedItems);

if (!$result) {
    echo "Error retrieving selected items: " . mysqli_error($con);
    exit();
}

$totalPurchaseValue = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $totalPurchaseValue += $row['quantity'] * $row['price'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentMode = isset($_POST['paymentMode']) ? $_POST['paymentMode'] : null;

    // Insert order record
    $saveOrderQuery = "INSERT INTO orders (customer_id, order_date, total_amount, order_quantity)
        VALUES (?, CURRENT_TIMESTAMP, ?, ?)";

    $stmtSaveOrder = mysqli_prepare($con, $saveOrderQuery);

    if ($stmtSaveOrder) {
        // Bind parameters
        mysqli_stmt_bind_param($stmtSaveOrder, "idd", $UserID, $totalAmount, $orderQuantity);

        // Insert order with multiple product IDs
        $totalAmount = $totalPurchaseValue;
        $orderQuantity = 0;

        mysqli_stmt_execute($stmtSaveOrder);

        $orderId = mysqli_insert_id($con);

        // Reset result pointer
        mysqli_data_seek($result, 0);

        while ($row = mysqli_fetch_assoc($result)) {
            $productId = $row['BookID'];
            $orderQuantity += $row['quantity'];

            // Update order with product IDs
            $updateOrderQuery = "UPDATE orders SET product_id = CONCAT(product_id, ',', ?) WHERE order_id = ?";
            $stmtUpdate = mysqli_prepare($con, $updateOrderQuery);

            if ($stmtUpdate) {
                mysqli_stmt_bind_param($stmtUpdate, "si", $productId, $orderId);
                mysqli_stmt_execute($stmtUpdate);
                mysqli_stmt_close($stmtUpdate);
            } else {
                echo "Error updating order with product IDs: " . mysqli_error($con);
            }
        }

        mysqli_stmt_close($stmtSaveOrder);
    } else {
        echo "Error preparing order query: " . mysqli_error($con);
    }

    $savePaymentQuery = "INSERT INTO payment (order_id, customer_id, payment_mode, amount_paid) 
                         VALUES (?, ?, ?, ?)";

    $stmtSavePayment = mysqli_prepare($con, $savePaymentQuery);

    if ($stmtSavePayment) {
        mysqli_stmt_bind_param($stmtSavePayment, "iisd", $orderId, $UserID, $paymentMode, $totalPurchaseValue);

        $resultSavePayment = mysqli_stmt_execute($stmtSavePayment);

        if ($resultSavePayment) {
            echo "Payment details saved successfully.";
        } else {
            echo "Error saving payment details: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmtSavePayment);
    } else {
        echo "Error preparing payment details statement: " . mysqli_error($con);
    }

    // Reset result pointer
    mysqli_data_seek($result, 0);

    // Initialize arrays to store product IDs and quantities
    $productIDs = array();
    $quantities = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $productId = $row['BookID'];
        $quantity = $row['quantity'];

        // Deduct quantity and update sold count in a single query
        $updateBooksQuery = "UPDATE books SET quantity = quantity - ?, Solds = Solds + ? WHERE BookID = ?";
        $stmtUpdateBooks = mysqli_prepare($con, $updateBooksQuery);

        if ($stmtUpdateBooks) {
            mysqli_stmt_bind_param($stmtUpdateBooks, "iii", $quantity, $quantity, $productId);
            mysqli_stmt_execute($stmtUpdateBooks);
            mysqli_stmt_close($stmtUpdateBooks);

            // Collect product IDs and quantities
            $productIDs[] = $productId;
            $quantities[] = $quantity;
        } else {
            echo "Error updating quantity and sold count: " . mysqli_error($con);
        }
    }

    // Combine product IDs and quantities into a single string
    $productIDsString = implode(',', $productIDs);
    $quantitiesString = implode(',', $quantities);

    // Update order table with collected product IDs and quantities
    $updateOrderQuery = "UPDATE orders SET product_id = ?, order_quantity = ? WHERE order_id = ?";
    $stmtUpdateOrder = mysqli_prepare($con, $updateOrderQuery);

    if ($stmtUpdateOrder) {
        mysqli_stmt_bind_param($stmtUpdateOrder, "ssi", $productIDsString, $quantitiesString, $orderId);
        mysqli_stmt_execute($stmtUpdateOrder);
        mysqli_stmt_close($stmtUpdateOrder);
    } else {
        echo "Error updating order with product IDs and quantities: " . mysqli_error($con);
    }

    // Update order table with collected product IDs
    $productIDsString = implode(',', $productIDs);
    $updateOrderQuery = "UPDATE orders SET product_id = ? WHERE order_id = ?";
    $stmtUpdateOrder = mysqli_prepare($con, $updateOrderQuery);

    if ($stmtUpdateOrder) {
        mysqli_stmt_bind_param($stmtUpdateOrder, "si", $productIDsString, $orderId);
        mysqli_stmt_execute($stmtUpdateOrder);
        mysqli_stmt_close($stmtUpdateOrder);
    } else {
        echo "Error updating order with product IDs: " . mysqli_error($con);
    }


    $clearCartQuery = "DELETE FROM cart WHERE cart_id IN ($selectedItems) AND customer_id = ?";
    $stmtClearCart = mysqli_prepare($con, $clearCartQuery);
    mysqli_stmt_bind_param($stmtClearCart, "i", $UserID);

    $resultClearCart = mysqli_stmt_execute($stmtClearCart);

    if (!$resultClearCart) {
        echo "Error clearing cart: " . mysqli_error($con);
    }

    header('Location: ordersuccessful.php');
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
    <?php include("header.php");
    include("popups.php"); ?>
    <section>
        <div class="wrapper" id="w3">
            <div class="payment-container">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="payment-form">
                    <div class="form-group"> <label for="paymentMode">Payment Mode:</label> <select name="paymentMode"
                            id="paymentMode" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="paypal">PayPal</option>
                        </select> </div>
                    <div class="payment-button"> <button
                            style="font-weight:bold; font-size:20px;         background-color: forestgreen; color: white; padding: 20px;         border: none; border-radius: 5px;"
                            type="submit">Submit Payment</button> </div>
                </form>
            </div>
        </div>
    </section>
</body>

</html>