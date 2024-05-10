<?php
include("connect.php");
include("query.php");

// Fetch available genres from the database
$genreQuery = "SELECT DISTINCT BookGenre FROM genre";
$genreResult = mysqli_query($con, $genreQuery);
$genres = [];

while ($genreRow = mysqli_fetch_assoc($genreResult)) {
    $genres[] = $genreRow['BookGenre'];
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

$filterForSale = isset($_GET['filterForSale']) ? $_GET['filterForSale'] : '1,0';
$filterGenre = isset($_GET['filterGenre']) ? $_GET['filterGenre'] : 'all';

$query = "SELECT * FROM Books WHERE forsale IN ($filterForSale)";

// Include genre filter if it's not 'all'
if ($filterGenre !== 'all') {
    $query .= " AND genre = '$filterGenre'";
}

$query .= " LIMIT $offset, $items_per_page";

$result = mysqli_query($con, $query);
?>

<h1 style="font-size: 50px">Inventory Management</h1>

<form id="filter-form" action="#" method="get">
    <label for="filterForSale">For Sale:</label>
    <select name="filterForSale" id="filterForSale">
        <option value="1,0" <?= ($filterForSale == '1,0') ? 'selected' : '' ?>>All</option>
        <option value="1" <?= ($filterForSale == '1') ? 'selected' : '' ?>>For Sale</option>
        <option value="0" <?= ($filterForSale == '0') ? 'selected' : '' ?>>Not For Sale</option>
    </select>

    <label for="filterGenre">Genre:</label>
    <select name="filterGenre" id="filterGenre">
        <option value="all" <?= ($filterGenre == 'all') ? 'selected' : '' ?>>All Genres</option>
        <?php foreach ($genres as $genre): ?>
            <option value="<?= $genre ?>" <?= ($filterGenre == $genre) ? 'selected' : '' ?>>
                <?= $genre ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Apply Filter">
</form>
<div id="Add">
     <a href="editgenre.php">
        <button class="Add">Genre</button>
    </a>
     <a href="Landingpage.php">
        <button class="Add">Sell</button>
    </a>
    <a href="add_book_form.php">
        <button class="Add">Add Book</button>
    </a>
</div>


<table id="inventory-table">

    <tr>
        <th>BookID</th>
        <th>Product Name</th>
        <th>Cover</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Action</th>
        <th>Select</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php $bookId = $row['BookID'] ?>
        <tr>
            <td>
                <?= $bookId ?>
            </td>
            <td style="min-width: 680px;">
                <?= (strlen($row['Title']) > 45) ? rtrim(wordwrap($row['Title'], 45, '<br>', true), '<br>') : $row['Title']; ?>
            </td>
            <td><img class="product-image" src='<?= $row['BookImage'] ?>' alt='Product Image' style='height: 80px;'>
            </td>
            <td>
                <?= $row['Price'] ?>
            </td>
            <td>
                <button class="adjust" data-productid="<?= $bookId ?>" data-change="-1">-</button>
                <input type='text' class='quantity' id='quantity<?= $bookId ?>' value='<?= $row['Quantity'] ?>'>
                <button class="adjust" data-productid="<?= $bookId ?>" data-change="1">+</button><br>
                <button class="update" data-productid="<?= $bookId ?>">Update</button>
            </td>
            <td>
                <a href="editbook.php?bookId=<?= $bookId ?>"><button class="edit" type="submit">Edit</button></a>
            </td>
            <td style="min-width: 86px;">
                <input type="checkbox" class="delete-checkbox" data-productid="<?= $bookId ?>">
            </td>
        </tr>
    <?php endwhile; ?>

</table>
<div id="delete">
    <button class="delete" onclick="deleteSelectedRows()">Delete Selected</button>
</div>

<div class="pagination">
    <p><b>Page:</b></p>
    <?php

    $total_rows_query = "SELECT COUNT(*) as total FROM Books WHERE forsale IN ($filterForSale)";

    // Include genre filter in total rows query if it's not 'all'
    if ($filterGenre !== 'all') {
        $total_rows_query .= " AND genre = '$filterGenre'";
    }

    $total_result = mysqli_query($con, $total_rows_query);
    $total_rows = mysqli_fetch_assoc($total_result)['total'];

    $total_pages = ceil($total_rows / $items_per_page);

    for ($i = 1; $i <= $total_pages; $i++) {
        $selectedClass = ($i == $page) ? 'selected-page' : '';
        echo "<a class='pageno $selectedClass' href='javascript:void(0);' onclick='loadPage(\"$i\")'>$i</a> ";
    }
    ?>
</div>
<script src="jquery-3.6.4.min.js"></script>
<script>
    function loadPage(page) {
        var filterForSaleValue = $("#filterForSale").val();
        var filterGenreValue = $("#filterGenre").val();

        $.ajax({
            url: '?page=' + page + '&filterForSale=' + filterForSaleValue + '&filterGenre=' + filterGenreValue,
            type: 'GET',
            success: function (data) {
                var parsedData = $(data);
                $('#inventory-table').html(parsedData.find('#inventory-table').html());
                $('.pagination').html(parsedData.find('.pagination').html());
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + status + " - " + error);
            }
        });
    }
    $(document).ready(function ($) {
        $("#filter-form").submit(function (event) {
            event.preventDefault();
            loadFilteredData();
        });
        document.addEventListener("click", function (event) {
            if (event.target.classList.contains("adjust")) {
                adjustStock(event.target.dataset.productid, parseInt(event.target.dataset.change));
            } else if (event.target.classList.contains("update")) {
                addStock(event.target.dataset.productid);
            }
        });
        function adjustStock(productId, change) {
            var quantityField = document.getElementById("quantity" + productId);
            var currentQuantity = parseInt(quantityField.value);
            if (currentQuantity + change >= 0) {
                var newQuantity = currentQuantity + change;
                quantityField.value = newQuantity;
                updateStockInDatabase(productId, newQuantity);
            }
        }
        function addStock(productId) {
            var quantityField = document.getElementById("quantity" + productId);
            var quantityToAdd = parseInt(quantityField.value);
            updateStockInDatabase(productId, quantityToAdd, true);
        }
        function updateStockInDatabase(productId, newQuantity, showAlert) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        console.log(xhr.responseText);
                        if (showAlert) {
                            alert("Successfully updated stocks!");
                        }
                    } else {
                        console.error("Error updating stocks.");
                    }
                }
            };
            var params = "productId=" + productId + "&newQuantity=" + newQuantity;
            xhr.open("POST", "update_stock.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(params);
        }

        function loadFilteredData() {
            var filterForSaleValue = $("#filterForSale").val();
            var filterGenreValue = $("#filterGenre").val();

            $.ajax({
                url: '?page=1&filterForSale=' + filterForSaleValue + '&filterGenre=' + filterGenreValue,
                type: 'GET',
                success: function (data) {
                    var parsedData = $(data);
                    $('#inventory-table').html(parsedData.find('#inventory-table').html());
                    $('.pagination').html(parsedData.find('.pagination').html());
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error: " + status + " - " + error);
                }
            });
        }
        $(document).on("click", ".delete", function () {
            deleteSelectedRows();
        });

        function deleteSelectedRows() {
             console.log("deleteSelectedRows function called");
            var selectedProductIds = [];

            // Find all checkboxes that are checked
            $(".delete-checkbox:checked").each(function () {
                selectedProductIds.push($(this).attr("data-productid"));
            });

            if (selectedProductIds.length > 0) {
                // Call a PHP script to handle the deletion on the server side
                $.ajax({
                    url: "delete_books.php",
                    type: "POST",
                    data: { productIds: selectedProductIds },
                    success: function (data) {
                        // Remove the deleted rows from the HTML
                        $(".delete-checkbox:checked").closest("tr").remove();

                        alert("Selected books deleted successfully!");
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: " + status + " - " + error);
                    }
                });
            } else {
                alert("No books selected for deletion.");
            }
        }

    });
</script>