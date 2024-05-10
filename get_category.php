<?php
include 'connect_db.php'; // Include your database connection script

// Fetch all categories from the database
$result = $conn->query("SELECT * FROM categories");
$categories = $result->fetch_all(MYSQLI_ASSOC);

// Provide the categories as a JSON response
header('Content-Type: application/json'); // Set the response content type to JSON
echo json_encode($categories);
?>
