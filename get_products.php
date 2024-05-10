<?php
include('connect_db.php');

header('Content-Type: application/json');

// Construct a SQL query to select all products
$sql = "SELECT * FROM products";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    // Fetch the result as an associative array
    $productsData = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Send the JSON response
    echo json_encode($productsData);
} else {
    // If there is an error in the query, send an error response
    echo json_encode(['error' => 'Error fetching products: ' . mysqli_error($conn)]);
}

// Close the database connection
mysqli_close($conn);
?>
