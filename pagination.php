<?php
// Get total number of records for the current category
$totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products WHERE Category = '$categoryName'"));
$totalPages = ceil($totalRecords / $imagesPerPage);

echo "<div id='pagination-container_$categoryName' class='pageno'><p><b>Page:</b></p>";

for ($x = 1; $x <= $totalPages; $x++) {
    $selectedClass = ($x == $_GET['page']) ? 'selected-page' : '';
    echo "<a href='?page=$x&category=$categoryName' class='$selectedClass'>$x</a>";
}

echo "</div>";
?>
