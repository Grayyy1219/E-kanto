<?php
include 'connectdb.php'; // Include your database connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryID = $_POST['category_id'];
    $newName = $_POST['new_name'];
    $newImage = $_POST['new_image'];

    $updateQuery = "UPDATE categories SET name='$newName', image='$newImage' WHERE id=$categoryID";
    $conn->query($updateQuery);
}

// Fetch categories
$result = $conn->query("SELECT * FROM categories");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="category">';
        echo '<img src="' . $row["image"] . '" alt="' . $row["name"] . '">';
        echo '<span>' . $row["name"] . '</span>';
        echo '<form method="post" action="landing_page.php">';
        echo '<input type="hidden" name="category_id" value="' . $row["id"] . '">';
        echo '<label>New Name: <input type="text" name="new_name"></label>';
        echo '<label>New Image URL: <input type="text" name="new_image"></label>';
        echo '<input type="submit" value="Update">';
        echo '</form>';
        echo '</div>';
    }
} else {
    echo 'No categories found.';
}

$conn->close();
?>
