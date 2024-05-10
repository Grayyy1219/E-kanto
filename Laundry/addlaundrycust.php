<?php
// Connect to the database
$cons = mysqli_connect("localhost", "root", "");

// Check connection
if ($cons->connect_error) {
    die("Connection failed: " . $cons->connect_error);
}
mysqli_select_db($cons, "laundry_db");

// Function to add a new laundry customer
function addLaundryCustomer($firstName, $lastName, $contact, $address) {
    global $cons;

     // Validate contact number (11 digits)
     if (strlen($contact) !== 11 || !ctype_digit($contact)) {
        echo "Error: Contact number must be exactly 11 digits.";
        return;
    }

    $sql = "INSERT INTO laundry_customers (first_name, last_name, contact, address) VALUES ('$firstName', '$lastName', '$contact', '$address')";
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
    addLaundryCustomer($firstName, $lastName, $contact, $address);
}

// Close connection
$cons->close();
?>
