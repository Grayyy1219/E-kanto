<link rel="stylesheet" href="page.css">
<form action="" id="myForm" method="post" enctype="multipart/form-data">
    <?php
    if (!isset($_GET['page'])) {
        $_GET['page'] = 1;
    }

    if (!isset($_GET['category'])) {
        exit("Category not set");
    }

    $category = $_GET['category'];

    $imagesPerPage = 3;
    $currentPage = $_GET['page'];

    $startIndex = ($currentPage - 1) * $imagesPerPage;
    $stmt = $conn->prepare("SELECT * FROM products WHERE Category = ? ORDER BY ItemName LIMIT ?, ?");
    $stmt->bind_param("sii", $category, $startIndex, $imagesPerPage);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $ItemID  = $row["ItemID"];
        $ItemName = $row["ItemName"];
        $category = $row["Category"];
        $ItemImage = $row["ItemImage"];
        $Price = $row["Price"];
        $Solds = $row["Solds"];
        $Quantity = $row["Quantity"];

        // Truncate long titles
        $shortenedTitle = (strlen($ItemName) > 78) ? substr($ItemName, 0, 78) . '...' : $ItemName;

        // HTML output
        echo "<div class='itemcard'>
                <a href=''><img src='{$ItemImage}' width='200'></a>
                <p><strong>{$shortenedTitle}</strong></p>
                <br><br>
                <p style='font-size: small'>Stocks {$Quantity}</p><br>
                <h4>₱{$Price}</h4>
               <div class='div-282' onclick=\"submitForm('itempage.php?Itemname=$ItemName&Category=$category&ItemImage=$ItemImage&Price=$Price&Solds=$Solds&Quantity=$Quantity&ItemID=$ItemID')\">
            <input type='submit' style='all:unset' class='div-29' value='Add to cart'>
        </div>
            </div>";
    }
    $totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products WHERE Category = '$category' "));
    $totalPages = ceil($totalRecords / $imagesPerPage);


    echo "</div>";

    echo "</br></br><div id='pagination-container_category' class='pageno'><b>Page: </b>";

    for ($i = 1; $i <= $totalPages; $i++) {
        // Add the active class to the current page
        $activeClass = ($i == $currentPage) ? 'active' : '';

        // Generate pagination links
        echo "<a class='pagination-link $activeClass' href='?category=$category&page=$i'>$i</a>";
    }
    echo "</div>";
    ?>
    <script>
        function submitForm(action) {
            // Trigger form submission
            document.getElementById("myForm").action = action;
            document.getElementById("myForm").submit();
        }
    </script>

</form>
</div>