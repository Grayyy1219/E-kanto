<?php
// Connect to the database
$cons = mysqli_connect("localhost", "root", "");

// Check connection
if ($cons->connect_error) {
    die("Connection failed: " . $cons->connect_error);
}
mysqli_select_db($cons, "laundry_db");

// Function to add a new laundry customer
function addLaundryCustomer($firstName, $lastName, $contact, $address, $serviceType, $pickupPreference, $deliveryPreference) {
    global $cons;
    $sql = "INSERT INTO laundry_customers (first_name, last_name, contact, address, service_type, pickup_preference, delivery_preference) VALUES ('$firstName', '$lastName', '$contact', '$address', '$serviceType', '$pickupPreference', '$deliveryPreference')";
    if ($cons->query($sql) === TRUE) {
        echo "New laundry customer added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $cons->error;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $contact = $_POST["contact"];
    $address = $_POST["address"];
  

    // Add a new laundry customer
    addLaundryCustomer($firstName, $lastName, $contact, $address, $serviceType, $pickupPreference, $deliveryPreference);
}

// Close connection
$cons->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="laundry.css">
    <title>Laundry Customer Form</title>
</head>
<body>
    <h2>Add a New Laundry Customer</h2>
    <form method="post" action="addlaundrycust.php">
        First Name: <input type="text" name="first_name" required><br>
        Last Name: <input type="text" name="last_name" required><br>
        Contact: <input type="text" name="contact" required><br>
        Address: <textarea name="address" rows="3" required></textarea><br>
        <input type="submit" value="Add Customer">
    </form>
</body>
</html>
