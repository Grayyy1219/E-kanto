<?php
include('connect_db.php'); // Include your database connection file

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productId = $_GET['productId'];

    // Fetch product details from the database based on $productId
    $stmt = $pdo->prepare("SELECT * FROM products WHERE ItemID = ?");
    $stmt->execute([$productId]);
    $productDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($productDetails);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
