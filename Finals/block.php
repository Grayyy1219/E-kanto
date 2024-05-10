<?php
include("connect.php");
include("query.php");

if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    $sql = "UPDATE users SET block = 1 WHERE UserID IN ($userid)";

    // Execute the query
    if ($con->query($sql) === TRUE) {
        $successMessage = "User successfully blocked.";
        echo "<script>
                alert('$successMessage');
                window.location.href = 'blockuser.php';
              </script>";
        exit();
    } else {
        // Display an alert if the query fails
        $errorMessage = "Error blocking user: " . $con->error;
        echo "<script>
                alert('Error: $errorMessage');
                window.location.href = 'blockuser.php';
              </script>";
        exit();
    }
} else {
    // Display an alert if the user ID is not set
    $noUserIdMessage = "User ID not provided.";
    echo "<script>
            alert('$noUserIdMessage');
            window.location.href = 'blockuser.php';
          </script>";
    exit();
}
?>