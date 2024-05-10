<?php
if (!isset($_GET['page'])) {
    $_GET['page'] = 1;
}

$genresQuery = $con->query("SELECT BookGenre FROM genre");
$genres = $genresQuery->fetch_all(MYSQLI_ASSOC);
$imagesPerPage = 4;
$currentPage = $_GET['page'];

echo "<form action='' method='post' enctype='multipart/form-data'>";

foreach ($genres as $genre) {
    $genreName = $genre['BookGenre'];

    echo "<section class='sgenre'><a name='$genreName'></a><div class='div-5'>
        <div class='div-6 color'>$genreName</div>
        <div class='div-7'>
            <div class='div-8'></div>
        </div>
    </div>";

    echo "<div class='container' id='container_$genreName'>";

    $startIndex = ($currentPage - 1) * $imagesPerPage;
    $stmt = $con->prepare("SELECT * FROM books WHERE Genre = ? ORDER BY Title LIMIT ?, ?");
    $stmt->bind_param("sii", $genreName, $startIndex, $imagesPerPage);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $BookID = $row["BookID"];
        $Title = $row["Title"];
        $Author = $row["Author"];
        $Publisher = $row["Publisher"];
        $Genre = $row["Genre"];
        $BookImage = $row["BookImage"];
        $Forsale = $row["Forsale"];
        $Price = $row["Price"];
        $Solds = $row["Solds"];
        $Quantity = $row["Quantity"];

        $shortenedTitle = (strlen($Title) > 78) ? substr($Title, 0, 78) . '...' : $Title;

        echo "
    <div class='product'>
        <img src='$BookImage' width='200' height='200'>
        <div class='product-info'>
            <h2 class='product-title'>$shortenedTitle</h2>
            <p class='book-author'>$Author</p>";

        if ($Forsale != 0) {
            echo "
            <p class='book-solds'>$Solds Solds</p>
            <h3 class='book-price'>PHP $Price</h3>
            <input type='submit' formaction='bookpage.php?Title=" . urlencode($Title) . "&Author=$Author&Publisher=".urlencode($Publisher)."&Genre=$Genre&BookImage=$BookImage&Forsale=$Forsale&Price=$Price&Solds=$Solds&Quantity=$Quantity&BookID=$BookID' class='book-buy-now' value='Buy Now'></div>
    </div>";
        } else {
            echo "
            <p class='book-solds'>$Solds Borrowed</p>
            <input type='submit' formaction='bookpage.php?Title=" . urlencode($Title) . "&Author=$Author&Publisher=".urlencode($Publisher)."&Genre=$Genre&BookImage=$BookImage&Forsale=$Forsale&Price=$Price&Solds=$Solds&Quantity=$Quantity&BookID=$BookID' class='book-buy-now' value='Borrow'></div>
    </div>";
        }
    }

    echo "</div>";

    $totalRecords = mysqli_num_rows(mysqli_query($con, "SELECT * FROM books WHERE Genre = '$genreName'"));
    $totalPages = ceil($totalRecords / $imagesPerPage);

    echo "<div id='pagination-container_$genreName' class='pageno'><p><b>Page:</b></p>";

    for ($x = 1; $x <= $totalPages; $x++) {
        $selectedClass = ($x == $currentPage) ? 'selected-page' : '';
        echo "<a href='javascript:void(0);' onclick='loadPage($x, \"$genreName\")' class='$selectedClass'>$x</a>";
    }

    echo "</div> </section>";
    $stmt->close();
}
?>
<script src="jquery-3.6.4.min.js"></script>
<script>
    function loadPage(page, genre) {
        $.ajax({
            url: '?page=' + page + '&genre=' + genre + '#Browse',
            type: 'GET',
            success: function(data) {
                $('#pagination-container_' + genre).html($(data).find('#pagination-container_' + genre).html());
                $('#container_' + genre).html($(data).find('#container_' + genre).html());
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + status + " - " + error);
            }
        });
    }
</script>