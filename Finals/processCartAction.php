<?php
include 'connect.php';
include 'query.php';
session_start();

if (!empty($_POST['selectedItems']) && is_array($_POST['selectedItems'])) {
    $_SESSION['selectedItems'] = implode(',', $_POST['selectedItems']);
    header('Location: purchaseItems.php');
    exit();
} elseif (!empty($_POST['selectedItems'])) {
    $_SESSION['selectedItems'] = implode(',', $_POST['selectedItems']);
    header('Location: purchaseItems.php');
    exit();
} else {
    // Display an error message or handle the case when no items are selected
    exit('<script>alert("Please select an item to proceed to checkout."); window.history.back();</script>');
}
?>