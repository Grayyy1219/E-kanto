<link rel="stylesheet" href="css/search.css">
<link rel="stylesheet" href="css/header.css">
<header>
    <a href="admin.php" class="ahead">
        <img src="Image\left-arrow.png" width="22">
        <h4>Go Back</h4>
    </a>
</header>
<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $genre = $_POST["genre"];

    $sql = "SELECT * FROM books WHERE ";
    $conditions = [];
    $params = [];

    if (!empty($title)) {
        $conditions[] = "Title LIKE ?";
        $params[] = "%$title%";
    }

    if (!empty($author)) {
        $conditions[] = "Author LIKE ?";
        $params[] = "%$author%";
    }

    if (!empty($genre)) {
        $conditions[] = "Genre LIKE ?";
        $params[] = "%$genre%";
    }

    if (!empty($conditions)) {
        $sql .= implode(" AND ", $conditions);
    } else {
        // No search criteria provided
        die("Please provide at least one search criteria.");
    }

    $stmt = $con->prepare($sql);

    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {
        if ($result->num_rows > 0) {
            echo "<h3>Search Results:</h3>";
            echo "<table border='1'>";
            echo "<tr><th>BookID</th><th>Title</th><th>Author</th><th>Publisher</th><th>Genre</th><th>BookImage</th><th>Forsale</th><th>Price</th><th>Solds</th><th>Quantity</th><th>Sell</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['BookID']}</td>";
                echo "<td>{$row['Title']}</td>";
                echo "<td>{$row['Author']}</td>";
                echo "<td>{$row['Publisher']}</td>";
                echo "<td>{$row['Genre']}</td>";
                echo "<td>{$row['BookImage']}</td>";
                echo "<td>{$row['Forsale']}</td>";
                echo "<td>{$row['Price']}</td>";
                echo "<td>{$row['Solds']}</td>";
                echo "<td>{$row['Quantity']}</td>";
                echo "<td><button onclick='openPopup({$row['BookID']}, {$row['Price']})'>Buy</button></td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No results found.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!-- Add the JavaScript for the popup -->
<script>
    function openPopup(bookId, price) {
        var quantity = prompt("Enter Quantity:", "1");

        if (quantity !== null) {
            // User clicked OK in the prompt
            window.location.href = `purchaseItems3.php?selectedItems=${bookId}&Quantity=${quantity}&Price=${price}`;
        }
        // If user clicked Cancel, do nothing
    }
</script>