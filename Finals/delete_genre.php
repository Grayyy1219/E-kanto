<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["genre_ids"])) {
    // Sanitize the input to prevent SQL injection
    $genreIDs = explode(',', $_POST["genre_ids"]);

    // Perform the delete operation
    foreach ($genreIDs as $genreID) {
        // Use prepared statement or at least escape the user ID
        $genreID = mysqli_real_escape_string($con, $genreID);

        $query = "DELETE FROM genre WHERE GenreID = '$genreID'";
        $result = mysqli_query($con, $query);
    }

    if ($result) {
        echo "genre deleted successfully";
    } else {
        echo "Error deleting genre: " . mysqli_error($con);
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "Invalid request";
}

mysqli_close($con);
?>