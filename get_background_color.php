<?php
// Include the database connection
include('connect_db.php');

// Fetch the background color from the database
$sql = "SELECT background_color FROM background_color_settings ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $backgroundColor = $row['background_color'];
    echo $backgroundColor;
} else {
    echo '#f0f0f0'; // Default background color
}

// Close the database connection
$conn->close();
?>
