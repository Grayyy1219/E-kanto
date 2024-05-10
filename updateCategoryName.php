<?php
include('connect_db.php');

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if category ID and new name are provided
    if (isset($_POST['category_id'], $_POST['new_name'])) {
        // Sanitize and escape the input (consider using prepared statements for better security)
        $categoryId = intval($_POST['category_id']);
        $newName = mysqli_real_escape_string($conn, $_POST['new_name']);

        // Construct a SQL query to update the category name
        $sql = "UPDATE categories SET CategoryName = '$newName' WHERE CategoryID = $categoryId";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // If the query is successful, send a success response
            echo json_encode(['success' => true, 'message' => 'Category name updated successfully']);
        } else {
            // If there is an error in the query, send an error response
            echo json_encode(['success' => false, 'message' => 'Error updating category name: ' . mysqli_error($conn)]);
        }
    } else {
        // If category ID or new name is not provided, send an error response
        echo json_encode(['success' => false, 'message' => 'Category ID or new name not provided']);
    }
} else {
    // If the request method is not POST, send an error response
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

// Close the database connection
mysqli_close($conn);
?>
