<?php
include 'connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $customerId = $_GET['customerId'];

    // Adjust the SQL query based on your database schema
    $sql = "SELECT * FROM customer_info WHERE customer_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $customerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if a row was returned
        if ($row = mysqli_fetch_assoc($result)) {
            // Return the customer details as JSON
            header('Content-Type: application/json');
            echo json_encode($row);
        } else {
            // Customer not found
            http_response_code(404);
            echo json_encode(array('status' => 'error', 'message' => 'Customer not found'));
        }

        mysqli_stmt_close($stmt);
    } else {
        // Failed to prepare the SQL statement
        http_response_code(500);
        echo json_encode(array('status' => 'error', 'message' => 'Failed to prepare the SQL statement'));
    }

    mysqli_close($conn);
} else {
    // Method Not Allowed
    http_response_code(405);
    echo json_encode(array('status' => 'error', 'message' => 'Method Not Allowed'));
}
?>
