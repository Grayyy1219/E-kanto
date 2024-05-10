<?php
// Include the database connection file
include('connect_db.php');

// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if not logged in
    header("Location: Sign-In.html");
    exit();
}

// Retrieve user details from the database
$user_id = $_SESSION["user_id"];
$query = "SELECT * FROM customer_info WHERE customer_id = $user_id";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch user details as an associative array
    $userDetails = mysqli_fetch_assoc($result);
} else {
    // Handle the query error
    die("Database query error: " . mysqli_error($conn));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $lastName = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $middleName = mysqli_real_escape_string($conn, $_POST["middle_name"]);
    $emailAddress = mysqli_real_escape_string($conn, $_POST["email_address"]);
    $contactNumber = mysqli_real_escape_string($conn, $_POST["contact_number"]);

    // Update the customer information in the database
    $updateQuery = "UPDATE customer_info SET
        first_name = '$firstName',
        last_name = '$lastName',
        middle_name = '$middleName',
        email_address = '$emailAddress',
        contact_number = '$contactNumber'
        WHERE customer_id = $user_id";

    $updateResult = mysqli_query($conn, $updateQuery);

    // Check if the update was successful
    if ($updateResult) {
        // Redirect with success message
        header("Location: account-settings.php?success=true");
        exit();
    } else {
        // Handle the query error
        die("Database query error: " . mysqli_error($conn));
    }
}
?>
