<?php
$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "<div class='shop'>";

    while ($row = mysqli_fetch_assoc($result)) {
        $categoryName = mysqli_real_escape_string($conn, $row['CategoryName']);
        $categoryImg = mysqli_real_escape_string($conn, $row['Image']);

        $countQuery = "SELECT COUNT(*) FROM products WHERE category = '$categoryName'";
        $countResult = mysqli_query($conn, $countQuery);

        if ($countResult) {
            $totalRecords = mysqli_fetch_row($countResult)[0];
                echo "  <a href='category.php?category=$categoryName'><div class='itemcard'>
               <img src='$categoryImg' height='100'>
                <p>$totalRecords Items/s available</p>
                <p><strong>$categoryName</strong></p>
            </div></a>";
        } else {
            echo "Error fetching book count for $categoryName: " . mysqli_error($conn);
        }
    }

    echo "</div></div>";
} else {
    echo "Error fetching genres from the database: " . mysqli_error($conn);
}
?>