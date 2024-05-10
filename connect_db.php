<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_select_db($conn, "customer_db");
try {
    // Database configuration
    $dsn = 'mysql:host=localhost;dbname=customer_db';
    $username = 'root';
    $password = '';

    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If you need to set character set
    $pdo->exec("SET NAMES 'utf8mb4'");

    // Additional code (queries, etc.) can go here

    // Close the connection when done (not necessary for PDO)

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
