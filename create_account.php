<?php
include 'connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];

    // Check if the profilePicture field is set in the $_FILES array
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['size'] > 0) {
        $profilePicture = $_FILES['profilePicture']['name'];

        // Move the uploaded file to a designated folder
        $uploadDir = 'profile_pictures/'; // Change this to your desired folder
        $uploadPath = $uploadDir . basename($profilePicture);
        move_uploaded_file($_FILES['profilePicture']['tmp_name'], $uploadPath);
    } else {
        $profilePicture = null;
    }

    $email = $_POST['email'];
    $contactNumber = $_POST['contactNumber'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO customer_info (last_name, first_name, middle_name, profile_picture, email_address, contact_number, password)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssss", $lastName, $firstName, $middleName, $profilePicture, $email, $contactNumber, $password);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            // Redirect to a success page
            header('Location: Admin_Panel.php');
            exit;
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to execute the SQL statement'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to prepare the SQL statement'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('status' => 'error', 'message' => 'Method Not Allowed'));
}
?>
