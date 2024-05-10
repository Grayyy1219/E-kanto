<?php
include 'connect.php';
include 'query.php';

// Process search input
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($con, $_GET['search']);
    $sql = "SELECT payment_id, order_id, customer_id, payment_date, amount_paid, payment_mode FROM payment 
            WHERE payment_id LIKE '%$search%' OR order_id LIKE '%$search%' OR payment_date LIKE '%$search%' 
            OR amount_paid LIKE '%$search%' OR payment_mode LIKE '%$search%'
            OR customer_id IN (SELECT UserID FROM users WHERE FName LIKE '%$search%' OR LName LIKE '%$search%')";
} else {
    // If no search input, retrieve all payment records
    $sql = "SELECT payment_id, order_id, customer_id, payment_date, amount_paid, payment_mode FROM payment";
}

// Add start date and end date filters
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($con, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($con, $_GET['end_date']);
    $sql .= " AND payment_date BETWEEN '$start_date' AND '$end_date'";
}

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/paymenthistory.css">
</head>

<body>
    <?php include 'aheader.php'; ?>
    <div class="wrapper" id="page">
        <h2>Payment History</h2>

    <!-- Add start date and end date fields to the search form -->
    <form action="" method="get">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" placeholder="Enter search term">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date">
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date">
        <button type="submit">Search</button>
    </form>

  
        <table>
            <tr>
                <th>Reference No.</th>
                <th>Order ID</th>
                <th>Name</th>
                <th>Payment Date</th>
                <th>Amount Paid</th>
                <th>Payment Mode</th>
            </tr>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $sql2 = "SELECT FName, LName FROM users WHERE UserID = " . $row['customer_id'];
            $result2 = mysqli_query($con, $sql2);
            $userRow = mysqli_fetch_assoc($result2);

            echo "<tr>";
            echo "<td>" . $row['payment_id'] . "</td>";
            echo "<td>" . $row['order_id'] . "</td>";
            echo "<td>" . $userRow['FName'] . " " . $userRow['LName'] . "</td>";
            echo "<td>" . $row['payment_date'] . "</td>";
            echo "<td>" . $row['amount_paid'] . "</td>";
            echo "<td>" . $row['payment_mode'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No payment history found.</td></tr>";
    }
    ?>

</table>
</div>

</body>

</html>
