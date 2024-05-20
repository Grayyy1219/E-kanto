

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Search Results</title>
    <link rel="stylesheet" href="css/swiftieshopper.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="trend.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="page.css">
</head>

<body>
    <!-- Include your header -->
    <?php include 'header.php'; ?>

    <!-- PHP code for fetching search results and pagination -->
    <?php
    include('connect_db.php');

    // Set default values
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $imagesPerPage = 6;

    if (isset($_GET['query'])) {
        $searchQuery = $conn->real_escape_string($_GET['query']);
        $startIndex = ($currentPage - 1) * $imagesPerPage;

        // SQL query to search for items in the database
        $sql = "SELECT * FROM products WHERE ItemName LIKE '%$searchQuery%' OR Category LIKE '%$searchQuery%' LIMIT $startIndex, $imagesPerPage";
        $result = $conn->query($sql);

        echo '<div class="search-results-container">';
        echo '<h1>Search Results for "' . htmlspecialchars($searchQuery) . '"</h1>';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Output each item
                // Modify the onclick attribute of the "Add to cart" button
                echo "<div class='itemcard2'>
                        <a href=''><img src='{$row["ItemImage"]}' width='200'></a>
                        <p><strong>{$row["ItemName"]}</strong></p>
                        <br><br>
                        <p style='font-size: small'>Stocks {$row["Quantity"]}</p><br>
                        <h4>₱{$row["Price"]}</h4>
                        <div class='div-282' onclick=\"addToCart('{$row["ItemID"]}', '{$row["ItemName"]}', '{$row["Category"]}', '{$row["ItemImage"]}', '{$row["Price"]}', '{$row["Solds"]}', '{$row["Quantity"]}')\">
                            <input type='button' style='all:unset' class='div-29' value='Add to cart'>
                        </div>
                    </div>";
            }
        } else {
            echo '<p>No results found.</p>';
        }

        echo '</div>'; // Close search-results-container

        // Pagination logic
    } else {
        // If no search query is provided, redirect to the homepage or show an error
        header("Location: Landing-Page.php");
        exit;
    }
    ?>

    <!-- Script for adding item to cart -->
    <script>
        function addToCart(ItemID, Itemname, Category, ItemImage, Price, Solds, Quantity) {
            // Redirect to item page with parameters
            window.location.href = `itempage.php?ItemID=${ItemID}&Itemname=${encodeURIComponent(Itemname)}&Category=${encodeURIComponent(Category)}&ItemImage=${encodeURIComponent(ItemImage)}&Price=${Price}&Solds=${Solds}&Quantity=${Quantity}`;
        }
    </script>
</body>

</html>
