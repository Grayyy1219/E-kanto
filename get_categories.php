<?php
include('connect_db.php');
header('Content-Type: application/json');

try {
    // Fetch category names from the database
    $stmt = $pdo->query("SELECT CategoryName FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Respond with the fetched category names
    echo json_encode(['categories' => $categories]);
} catch (PDOException $e) {
    // Handle database-related errors
    $response = ['success' => false, 'message' => 'Error fetching categories: ' . $e->getMessage()];
    echo json_encode($response);
}
?>
