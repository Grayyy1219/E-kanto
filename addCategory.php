<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('connect_db.php');

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['new_name'];

    $targetDirectory = 'imagesCategory/';
    $targetFile = $targetDirectory . basename($_FILES['new_image']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (file_exists($targetFile)) {
        $response['status'] = 'error';
        $response['message'] = 'File already exists.';
        $uploadOk = 0;
    }

    if ($_FILES['new_image']['size'] > 50000000) {
        $response['status'] = 'error';
        $response['message'] = 'File is too large.';
        $uploadOk = 0;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedExtensions)) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid file format.';
        $uploadOk = 0;
    }

    if ($uploadOk === 0) {
        $response['status'] = 'error';
        $response['message'] = 'File not uploaded.';
    } else {
        if (move_uploaded_file($_FILES['new_image']['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO categories (CategoryName, image) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "ss", $newName, $targetFile);

            if (mysqli_stmt_execute($stmt)) {
                $response['status'] = 'success';
                $response['message'] = 'Category added successfully.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error adding category to the database.';
            }

            mysqli_stmt_close($stmt);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error uploading file. Check file permissions and target directory.';
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

mysqli_close($conn);

echo json_encode($response);
?>
