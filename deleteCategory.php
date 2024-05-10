<?php
include('connect_db.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON data from the request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Check if categoryIds are provided
    if (isset($requestData['categoryIds']) && is_array($requestData['categoryIds'])) {
        // Initialize an empty array to store sanitized category IDs
        $categoryIds = [];

        // Iterate over categoryIds and sanitize each element
        foreach ($requestData['categoryIds'] as $categoryId) {
            $sanitizedId = intval($categoryId);
            // Ensure the ID is positive before adding it to the array
            if ($sanitizedId > 0) {
                $categoryIds[] = $sanitizedId;
            }
        }

        // Check if there are valid category IDs to delete
        if (empty($categoryIds)) {
            echo json_encode(['success' => false, 'message' => 'No valid Category IDs provided']);
            exit;
        }

        // Construct a SQL query to delete categories with the specified IDs
        $sql = "DELETE FROM categories WHERE CategoryID IN (" . implode(',', $categoryIds) . ")";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // If the query is successful, send a success response
            echo json_encode(['success' => true, 'message' => 'Categories deleted successfully']);
        } else {
            // If there is an error in the query, send an error response
            echo json_encode(['success' => false, 'message' => 'Error deleting categories: ' . mysqli_error($conn)]);
        }
    } else {
        // If categoryIds are not provided, send an error response
        echo json_encode(['success' => false, 'message' => 'Category IDs not provided']);
    }
} else {
    // If the request method is not POST, send an error response
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

// Close the database connection
mysqli_close($conn);
?>
