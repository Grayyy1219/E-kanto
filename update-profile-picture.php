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

// Handle profile picture update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was selected
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
        // Define upload directory and file name
        $uploadDir = "profile_pictures/";
        $fileName = basename($_FILES["profile_picture"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow only certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (in_array(strtolower($fileType), $allowedFormats)) {
            // Upload the file to the server
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
                // Update the user's profile picture in the database
                $updateQuery = "UPDATE customer_info SET profile_picture = '$targetFilePath' WHERE customer_id = $user_id";
                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    // Redirect to account settings with success message
                    header("Location: account-settings.php?success=true");
                    exit();
                } else {
                    // Handle the update error
                    die("Update query error: " . mysqli_error($conn));
                }
            } else {
                // Handle file upload error
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            // Handle invalid file format
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    } else {
        // Handle no file selected error
        echo "Please select a file to upload.";
    }
}
?>
