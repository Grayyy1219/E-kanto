<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_select_db($conn, "admin_db");

// Check if a file is uploaded
if (isset($_FILES['admin-profile-picture']) && $_FILES['admin-profile-picture']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['admin-profile-picture'];

    // Specify the directory to save the uploaded file
    $uploadDirectory = 'adUploads/';

    // Generate a unique filename to avoid overwriting existing files
    $filename = uniqid() . '_' . $file['name'];
    $targetPath = $uploadDirectory . $filename;

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // File upload successful
        $profilePicturePath = $targetPath;
    } else {
        // File upload failed
        $response = array('status' => 'error', 'message' => 'File upload failed.');
        echo json_encode($response);
        exit;
    }
} else {
    // No file uploaded or an error occurred
    $profilePicturePath = ''; // Set a default value or handle accordingly
}

// Get other admin details from the POST request
$name = $_POST['admin-name'];
$email = $_POST['admin-email'];
$age = $_POST['admin-age'];
$contact = $_POST['admin-contact'];

// If no new profile picture is uploaded, use the old one
if (empty($profilePicturePath)) {
    // Retrieve the old profile picture path from the database or set a default path
    // For example, assuming you have a session or database variable $oldProfilePicturePath
    $profilePicturePath = $oldProfilePicturePath; // Update this line accordingly
}

// Prepare and execute the SQL query
$sql = "INSERT INTO admin_details (name, email, age, contact, profilePicture) VALUES ('$name', '$email', $age, '$contact', '$profilePicturePath')";

if ($conn->query($sql) === TRUE) {
    $response = array('status' => 'success', 'message' => 'Admin details saved successfully.');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Error saving admin details: ' . $conn->error);
    echo json_encode($response);
}

// Close the database connection
$conn->close();
?>
