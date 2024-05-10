<?php
include('connect_db.php'); // Include your database connection file
header('Content-Type: application/json');

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Check if the 'productIds' key exists in the JSON data
    if (isset($requestData['productIds']) && is_array($requestData['productIds'])) {
        $deletedProductIds = $requestData['productIds'];

        try {
            // Implement your logic to delete products with the specified IDs
            foreach ($deletedProductIds as $productId) {
                $stmt = $pdo->prepare("DELETE FROM products WHERE ItemID = ?");
                $stmt->execute([$productId]);
            }

            // Respond with a success message or any other relevant information
            $response = ['success' => true, 'message' => 'Products deleted successfully'];
            echo json_encode($response);
        } catch (PDOException $e) {
            // Handle database-related errors
            $response = ['success' => false, 'message' => 'Error deleting products: ' . $e->getMessage()];
            echo json_encode($response);
        }
    } else {
        // Respond with an error message if the 'productIds' key is missing or not an array
        $response = ['success' => false, 'message' => 'Invalid request data'];
        echo json_encode($response);
    }
} else {
    // Respond with an error message for non-POST requests
    $response = ['success' => false, 'message' => 'Invalid request method'];
    echo json_encode($response);
}
?>
