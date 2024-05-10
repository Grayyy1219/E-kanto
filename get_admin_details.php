<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_select_db($conn, "admin_db");

// Fetch admin details from the database
$sql = "SELECT name, email, age, contact, profilePicture FROM admin_details ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $adminDetails = array(
        'name' => $row['name'],
        'email' => $row['email'],
        'age' => $row['age'],
        'contact' => $row['contact'],
        'profilePicture' => $row['profilePicture']
    );

    echo json_encode($adminDetails);
} else {
    echo json_encode(array('error' => 'Admin details not found'));
}

// Close the database connection
$conn->close();
?>
