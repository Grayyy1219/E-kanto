<link rel="stylesheet" href="css/stylemain.css">
<?php
session_start();
include 'connect_db.php';
include 'query.php';
$UserID = $_SESSION['user_id'];
// Check if the delete button is clicked
if (isset($_POST['deleteSelected'])) {
    $customerId = $UserID;

    // Delete selected items from the cart
    if (!empty($_POST['selectedItems'])) {
        $selectedItems = implode(',', $_POST['selectedItems']);
        $deleteItemsQuery = "DELETE FROM cart WHERE cart_id IN ($selectedItems) AND customer_id = $customerId";
        $result = mysqli_query($conn, $deleteItemsQuery);

        if (!$result) {
            echo "Error deleting items: " . mysqli_error($conn);
        } else {
            echo '<div style="text-align: center;">';
            echo '    <img src="image/yescart.png" alt="Success" style="width: 200px; height: 200px; margin-top:50px">';
            echo '    <p style="font-weight: bold; font-size: 90px; color: green; margin-top:10px">Deleted successfully</p>';
            echo '    <a href="cart.php" style="text-decoration: none; color: #333; font-weight: bold;font-size: 30px;margin-top:10px">Cart</a>';
            echo '</div>';
        }
    } else {
        echo '<div style="text-align: center;">';
        echo '    <img src="image/no.png" alt="Error" style="width: 200px; height: 200px;margin-top:50px">';
        echo '    <p style="font-weight: bold; font-size: 90px; color: red;margin-top:10px">No items selected to delete</p>';
        echo '    <a href="cart.php" style="text-decoration: none; color: #333; font-weight: bold;font-size: 30px;margin-top:10px">Cart</a>';
        echo '</div>';
    }
}


?>