<?php
// Connect to the database
$cons = mysqli_connect("localhost", "root", "");

// Check connection
if ($cons->connect_error) {
    die("Connection failed: " . $cons->connect_error);
}
mysqli_select_db($cons, "laundry_db");

// Function to fetch filtered laundry customers
function fetchFilteredCustomers($contactFilter) {
    global $cons;

    // Build the SQL query with the contact filter
    $sql = "SELECT * FROM laundry_customers";
    if (!empty($contactFilter)) {
        $sql .= " WHERE contact like '%$contactFilter%'";
    }

    $result = $cons->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Initialize contact filter variable
$contactFilter = '';

// Handle form submission for filtering
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contactFilter = $_POST["contact_filter"];
}

// Get filtered customers
$filteredCustomers = fetchFilteredCustomers($contactFilter);

// Close connection
$cons->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="laundry.css">
    <title>View Laundry Customers</title>
</head>
<body>
    <h2>View Laundry Customers</h2>

    <!-- Filter form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="contact_filter">Filter by Contact:</label>
        <input type="text" name="contact_filter" id="contact_filter" value="<?php echo $contactFilter; ?>">
        <input type="submit" value="Filter">
    </form>

    <?php if (count($filteredCustomers) > 0): ?>
        <table class="customer-table">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Wash Count</th>
            </tr>
            <?php foreach ($filteredCustomers as $customer): ?>
                <tr>
                    <td><?php echo $customer['id']; ?></td>
                    <td><?php echo $customer['first_name']; ?></td>
                    <td><?php echo $customer['last_name']; ?></td>
                    <td><?php echo $customer['contact']; ?></td>
                    <td><?php echo $customer['address']; ?></td>
                    <td><?php echo $customer['wash_count']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No customers found.</p>
    <?php endif; ?>
</body>
</html>
