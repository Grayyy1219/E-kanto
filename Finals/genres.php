<?php
$query = "SELECT * FROM genre";
$result = mysqli_query($con, $query);

if ($result) {
    echo "<div class='colitem'><div class='items'>";

    while ($row = mysqli_fetch_assoc($result)) {
        $genreName = mysqli_real_escape_string($con, $row['BookGenre']);

        $countQuery = "SELECT COUNT(*) FROM books WHERE Genre = '$genreName'";
        $countResult = mysqli_query($con, $countQuery);

        if ($countResult) {
            $totalRecords = mysqli_fetch_row($countResult)[0];

            echo "<a href='#$genreName'>
                    <div class='itembox'>
                        <h3>$genreName</h3>
                        <p>$totalRecords Book/s available</p>
                        <h5>Browse all</h5>
                    </div>
                </a>";
        } else {
            echo "Error fetching book count for $genreName: " . mysqli_error($con);
        }
    }

    echo "</div></div>";
} else {
    echo "Error fetching genres from the database: " . mysqli_error($con);
}
