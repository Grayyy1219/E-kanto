<?php
include 'connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get customerId from the URL parameters
    $customerId = $_GET['customerId'];

    // Get the updated data from the request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Extract individual fields
    $firstName = $requestData['firstName'];
    $lastName = $requestData['lastName'];
    $middleName = $requestData['middleName'];
    $email = $requestData['email'];
    $contactNumber = $requestData['contactNumber'];
    // Add similar lines for other fields

    // Update the customer in the database
    $sql = "UPDATE customer_info 
            SET first_name = ?, last_name = ?, middle_name = ?, email_address = ?, contact_number = ? 
            WHERE customer_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssi", $firstName, $lastName, $middleName, $email, $contactNumber, $customerId);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Update successful
            echo json_encode(array('status' => 'success', 'message' => 'Customer updated successfully'));
        } else {
            // Update failed
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update customer'));
        }

        mysqli_stmt_close($stmt);
    } else {
        // Failed to prepare the SQL statement
        echo json_encode(array('status' => 'error', 'message' => 'Failed to prepare SQL statement'));
    }

    mysqli_close($conn);
} else {
    // Method Not Allowed
    http_response_code(405);
    echo json_encode(array('status' => 'error', 'message' => 'Method Not Allowed'));
}
?>
