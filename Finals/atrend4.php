<h2>Least Sold Books</h2>
<?php
include 'query.php';
echo "<form action='' method='post' enctype='multipart/form-data' class='formtrend'>";
$x = 1;

$query = "SELECT * FROM books WHERE Forsale = 1 ORDER BY Solds ASC, BookID DESC LIMIT 5";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0):
    ?>
    <div class='books'>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
            $Forsale = $row['Forsale'];
            $Title = $row['Title'];
            $Author = $row['Author'];
            $Publisher = $row['Publisher'];
            $Genre = $row['Genre'];
            $BookImage = $row['BookImage'];
            $Price = $row['Price'];
            $Solds = $row['Solds'];
            $Quantity = $row['Quantity'];
            $shortenedTitle = (strlen($row['Title']) > 78) ? substr($row['Title'], 0, 78) . '...' : $row['Title'];
            ?>
            <div class='book'>
                <img class='book-image' src='<?php echo $row['BookImage']; ?>'>
                <h2 class='book-title'>
                    <?php echo "$shortenedTitle";
                    $x++; ?>
                </h2>
                <p class='book-author'>
                    <?php echo $row['Author']; ?>
                </p>
                <p class='book-author'>
                    <?php if ($Forsale != 0) {
                        echo "Solds";
                    } else {
                        echo "Borrowed";
                    }
                    ?>
                    <?php echo $row['Solds']; ?>
                </p>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
else:
    echo "Error fetching the most sold books from the database: " . mysqli_error($con);
endif;
echo "</form>";
?>