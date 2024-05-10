<?php
include 'connect_db.php';

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle the DELETE request here
    // For example, you can access data from the request body using file_get_contents('php://input')
    
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Function to delete a customer by ID
    function deleteCustomerById($customerId, $conn) {
        // Implement the actual deletion logic based on the provided customer ID
        // Use prepared statements to prevent SQL injection
        $sql = "DELETE FROM customer_info WHERE customer_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $customerId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // Log the SQL statement for debugging
        error_log($sql);
    }
    
    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    try {
        // Iterate over customer IDs and delete each customer
        foreach ($requestData['customerIds'] as $customerId) {
            deleteCustomerById($customerId, $conn);
        }

        // If successful, return a success response
        $response = array('status' => 'success', 'message' => 'Customers deleted successfully');
    } catch (Exception $e) {
        // If an error occurs, set appropriate HTTP status code and return a JSON error response
        http_response_code(500);
        $response = array('status' => 'error', 'message' => 'Internal Server Error');
    } finally {
        // Close the database connection
        mysqli_close($conn);
    }

    // Set the Content-Type header to indicate JSON response
    header('Content-Type: application/json');
    // Send the JSON response indicating success or error
    echo json_encode($response);
} else {
    // If the request method is not DELETE, return a 405 Method Not Allowed response
    http_response_code(405);
    echo json_encode(array('status' => 'error', 'message' => 'Method Not Allowed'));
}
?>
