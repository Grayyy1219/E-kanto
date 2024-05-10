<link rel="stylesheet" href="trend.css">
<?php
include 'connect_db.php';
 echo "<form action='' method='post' enctype='multipart/form-data' class='formtrend'>";
$x = 1;

$query = "SELECT * FROM products ORDER BY Solds ASC LIMIT 5";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0):
    ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php
        $ItemID = $row['ItemID'];
        $ItemName = $row['ItemName'];
        $category = $row['Category'];
        $ItemImage = $row['ItemImage'];
        $Price = $row['Price'];
        $Solds = $row['Solds'];
        $Quantity = $row['Quantity'];
        $shortenedTitle = (strlen($row['ItemName']) > 25) ? substr($row['ItemName'], 0, 25) . '...' : $row['ItemName'];
        ?>
        <?php echo "<div class='itemcard'>
            <div><b>Top $x Item</b></div>
                <a href=''><img src='{$ItemImage}' width='100'></a>
                <p><strong style='font-size: small;'>{$shortenedTitle}</strong></p>
                <br><br>
                <p style='font-size: small'>{$Solds} Solds!</p><br>
                <h4>₱{$Price}</h4>
                <form action='' id='myForm' method='post' enctype='multipart/form-data'>
                <div class='div-282' onclick=\"submitForm('itempage.php?Itemname=$ItemName&Category=$category&ItemImage=$ItemImage&Price=$Price&Solds=$Solds&Quantity=$Quantity&ItemID=$ItemID')\">
                </form>
        </div>
            </div>";
        $x++ ?>

    <?php endwhile; ?>
<?php
else:
    echo "Error fetching the most sold items from the database: " . mysqli_error($conn);
endif;
?>