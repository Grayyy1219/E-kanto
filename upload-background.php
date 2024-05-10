<?php
// Check connection
include('connect_db.php');

// Fetch the background image file path from the database
$sql = "SELECT filename FROM background_images ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $backgroundImagePath = 'background-uploads/' . $row['filename'];
} else {
    $backgroundImagePath = '';  // Set a default background image path or handle this case as needed
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload-background'])) {
    handleUpload('background-image', $conn);
}

// Function to handle background image upload
function handleUpload($inputName, $conn) {
    $targetDir = 'background-uploads/';
    $targetFile = $targetDir . basename($_FILES[$inputName]['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an actual image or fake image
    $check = getimagesize($_FILES[$inputName]['tmp_name']);
    if ($check === false) {
        echo 'File is not an image.';
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES[$inputName]['size'] > 50000000000) {
        echo 'Sorry, your file is too large.';
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
        echo 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo 'Sorry, your file was not uploaded.';
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
            // Get the filename
            $filename = basename($_FILES[$inputName]['name']);

            // Insert the uploaded filename into the database
            $sql = "INSERT INTO background_images (filename) VALUES ('$filename')";

            if ($conn->query($sql) === TRUE) {
                echo 'The file ' . htmlspecialchars($filename) . ' has been uploaded and added to the database.';
                // Update $backgroundImagePath with the new path
                $backgroundImagePath = 'background-uploads/' . $filename;

                // Redirect to Admin Panel after successful upload
                header('Location: Admin_Panel.php');
                exit();
            } else {
                echo 'Sorry, there was an error inserting the record into the database.';
            }
        } else {
            echo 'Sorry, there was an error uploading your file.';
        }
    }
}
?>
