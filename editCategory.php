<?php
include 'connect_db.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['id'];
    $newName = $_POST['newName'];
    $newImage = $_POST['newImage'];

    // Perform necessary validation and sanitization

    // Update the category in the database
    $stmt = $con->prepare("UPDATE categories SET CategoryName = ?, Image = ? WHERE CategoryID = ?");
    $stmt->bind_param("ssi", $newName, $newImage, $categoryId);
    $stmt->execute();
    $stmt->close();

    // Provide a response (you can customize this based on your needs)
    echo json_encode(['success' => true, 'message' => 'Category edited successfully']);
} else {
    // Handle other request methods if needed
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
