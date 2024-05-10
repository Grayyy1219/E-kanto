<?php
include('connect_db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if all required fields are present in the POST data
        $requiredFields = ['productId', 'productName', 'category', 'price', 'quantity'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        $productId = $_POST['productId'];
        $productName = $_POST['productName'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        // Check if the file field is set in the FILES data
        $imagePath = isset($_FILES['itemImage']) ? handleImageUpload($_FILES['itemImage'], getExistingImagePath($productId)) : getExistingImagePath($productId);

        $stmt = $pdo->prepare("UPDATE products SET ItemName=?, Category=?, Price=?, Quantity=?, ItemImage=? WHERE ItemID=?");
        $stmt->execute([$productName, $category, $price, $quantity, $imagePath, $productId]);

        $response = ['success' => true, 'message' => 'Product updated successfully'];
        echo json_encode($response);
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => 'Error updating product: ' . $e->getMessage()];
        echo json_encode($response);
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request method'];
    echo json_encode($response);
}

// Function to handle image upload and return the image path
function handleImageUpload($file, $existingImagePath)
{
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Process image upload if a new image is provided
        $targetDirectory = 'productUploads/';
        $targetPath = $targetDirectory . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $targetPath);

        // Delete the existing image file if it's different from the new one
        if ($existingImagePath !== $targetPath && file_exists($existingImagePath)) {
            unlink($existingImagePath);
        }

        return $targetPath;
    } else {
        // Use the existing image path if no new image is uploaded
        return $existingImagePath;
    }
}

// Function to get the existing image path for a product
function getExistingImagePath($productId)
{
    global $pdo; // Add this line to access the $pdo variable inside the function

    // Your logic to retrieve the existing image path from the database based on $productId goes here
    // Example:
    $stmt = $pdo->prepare("SELECT ItemImage FROM products WHERE ItemID=?");
    $stmt->execute([$productId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the existing image path if found, otherwise an empty string
    return $result ? $result['ItemImage'] : '';
}
?>
