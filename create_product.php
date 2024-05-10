<?php
include('connect_db.php'); // Include your database connection file

header('Content-Type: application/json');

try {
    // Check if it's a POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the form data
        $productName = $_POST['productName'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        // File upload handling (assuming 'itemImage' is the file input name)
        $uploadDir = 'productUploads/'; // Specify your upload directory
        $uploadedFile = $uploadDir . basename($_FILES['itemImage']['name']);

        if (move_uploaded_file($_FILES['itemImage']['tmp_name'], $uploadedFile)) {
            // File uploaded successfully, proceed with database insertion
            $stmt = $pdo->prepare("INSERT INTO products (ItemName, Category, Price, Quantity, ItemImage) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$productName, $category, $price, $quantity, $uploadedFile]);

            // Respond with a success message
            $response = ['success' => true, 'message' => 'Product created successfully'];
            echo json_encode($response);
        } else {
            // Failed to upload the file
            $response = ['success' => false, 'message' => 'Error uploading the file'];
            echo json_encode($response);
        }
    } else {
        // Respond with an error message for non-POST requests
        $response = ['success' => false, 'message' => 'Invalid request method'];
        echo json_encode($response);
    }
} catch (PDOException $e) {
    // Handle database-related errors
    $response = ['success' => false, 'message' => 'Error creating product: ' . $e->getMessage()];
    echo json_encode($response);
}
?>
