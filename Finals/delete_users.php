<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_ids"])) {
    // Sanitize the input to prevent SQL injection
    $userIds = explode(',', $_POST["user_ids"]);

    // Perform the delete operation
    foreach ($userIds as $userId) {
        // Use prepared statement or at least escape the user ID
        $userId = mysqli_real_escape_string($con, $userId);

        $query = "DELETE FROM users WHERE UserID = '$userId'";
        $result = mysqli_query($con, $query);
    }

    if ($result) {
        echo "Users deleted successfully";
    } else {
        echo "Error deleting users: " . mysqli_error($con);
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "Invalid request";
}

mysqli_close($con);
?>