<?php
// Include your database connection code
include("connect_db.php");

// Your SQL query to get customer details
$query = "SELECT customer_id, first_name, last_name, email_address, blocked FROM customer_info";
$result = $pdo->query($query);

if ($result) {
    // Fetch the results as an associative array
    $customers = $result->fetchAll(PDO::FETCH_ASSOC);

    // Send the JSON response with customer details
    header('Content-Type: application/json');
    echo json_encode($customers);
} else {
    // Handle the error, e.g., return an error response
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to fetch customer details.']);
}

// Close the database connection
$pdo = null;
?>
