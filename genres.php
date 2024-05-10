<link rel="stylesheet" href="css/genre.css">
<?php
$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "<div class='colitem'><div class='items'>";

    while ($row = mysqli_fetch_assoc($result)) {
        $genreName = mysqli_real_escape_string($conn, $row['CategoryID']);
        $CategoryName = mysqli_real_escape_string($conn, $row['CategoryName']);

        $countQuery = "SELECT COUNT(*) FROM products WHERE CategoryID = '$genreName'";
        $countResult = mysqli_query($conn, $countQuery);

        if ($countResult) {
            $totalRecords = mysqli_fetch_row($countResult)[0];

            echo "<a href='#$genreName'>
                    <div class='itembox'>
                        <h3>$CategoryName</h3>
                        <p>$totalRecords Products/s available</p>
                        <h5>Browse all</h5>
                    </div>
                </a>";
        } else {
            echo "Error fetching book count for $genreName: " . mysqli_error($conn);
        }
    }

    echo "</div></div>";
} else {
    echo "Error fetching genres from the database: " . mysqli_error($conn);
}
