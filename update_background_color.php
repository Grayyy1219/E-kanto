<?php
// Include the database connection
include('connect_db.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-background-color'])) {
    updateBackgroundColor($_POST['background-color'], $conn);
}

// Function to update background color
function updateBackgroundColor($newColor, $conn) {
    // Update the background color in the database
    $sql = "UPDATE background_color_settings SET background_color = '$newColor' WHERE id = 1";

    if ($conn->query($sql) === TRUE) {
        echo 'Background color updated successfully.';
        
        // Redirect to Admin_Panel.php
        header('Location: Admin_Panel.php');
        exit();
    } else {
        echo 'Error updating background color: ' . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
