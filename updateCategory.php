<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['category_id'];
    $newImage = $_FILES['new_image'];

    // Check if the file was uploaded without errors
    if ($newImage['error'] === UPLOAD_ERR_OK) {
        // Specify the target directory for storing images
        $targetDir = 'imagesCategory/';
        // Create a unique filename for the uploaded image
        $targetFile = $targetDir . uniqid() . '_' . basename($newImage['name']);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($newImage['tmp_name'], $targetFile)) {
            // Update the category's image in the database
            $conn = mysqli_connect("localhost", "root", "", "admin_db");
            if ($conn->connect_error) {
                $response = array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error);
                echo json_encode($response);
                exit;
            }

            $updateQuery = "UPDATE categories SET image='$targetFile' WHERE id=$categoryId";

            if ($conn->query($updateQuery) === TRUE) {
                $response = array('status' => 'success', 'message' => 'Category image updated successfully');
                echo json_encode($response);
            } else {
                $response = array('status' => 'error', 'message' => 'Error updating category image: ' . $conn->error);
                echo json_encode($response);
            }

            $conn->close();
        } else {
            $response = array('status' => 'error', 'message' => 'Error moving uploaded file to destination');
            echo json_encode($response);
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Error uploading file: ' . $newImage['error']);
        echo json_encode($response);
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    echo json_encode($response);
}
?>
