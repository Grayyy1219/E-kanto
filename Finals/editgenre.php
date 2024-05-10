<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account Management</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/editgenre.css">
    <link rel="icon" href="Image/logo.ico">
</head>
<?php
include("connect.php");
include("query.php");

$query = "SELECT * FROM genre";

$result = mysqli_query($con, $query);
?>

<body>
    <?php include 'aheader.php'; ?>
    <section>
        <div class="wrapper" id="page">
            <h1 style="font-size: 50px">Book Genre Management</h1>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Genre</th>
                        <th>Action</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php $GenreID = $row['GenreID']; ?>
                        <tr id="user-row-<?= $GenreID ?>">
                            <td>
                                <?= $GenreID ?>
                            </td>
                            <td>
                                <?= $row['BookGenre'] ?>
                            </td>

                            <td>
                                <a href="editgenre2.php?GenreID=<?= $GenreID ?>"><button class="edit"
                                        type="submit">Edit</button></a>
                            </td>
                            <td style="min-width: 86px;">
                                <input type="checkbox" class="delete-checkbox" data-GenreID="<?= $GenreID ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div id="delete">
                <a href="addgenre3.php"><button class="Signup">Add Genre</button></a>
                <button class="delete" onclick="deleteSelectedRows()">Delete Selected</button>
            </div>
        </div>
    </section>

    <script>
        function handleBlockUnblock(GenreID, action) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var rowId = "user-row-" + GenreID;
                    document.getElementById(rowId).innerHTML = this.responseText;
                }
            };
            if (action === 'block') {
                xhttp.open("GET", "block.php?GenreID=" + GenreID, true);
            } else if (action === 'unblock') {
                xhttp.open("GET", "unblock.php?GenreID=" + GenreID, true);
            }
            xhttp.send();
        }

        function deleteSelectedRows() {
            var selectedCheckboxes = document.querySelectorAll('.delete-checkbox:checked');
            var selectedGenreIDs = Array.from(selectedCheckboxes).map(function (checkbox) {
                return checkbox.getAttribute('data-GenreID');
            });

            if (selectedGenreIDs.length > 0) {
                var confirmed = confirm("Are you sure you want to delete the selected users?");
                if (confirmed) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4) {
                            if (this.status == 200) {
                                location.reload();
                            } else {
                                // Handle errors here if needed
                                console.error('Error:', this.status, this.statusText);
                            }
                        }
                    };

                    var requestData = "genre_ids=" + encodeURIComponent(selectedGenreIDs.join(','));

                    xhttp.open("POST", "delete_genre.php");
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send(requestData);
                }
            } else {
                alert("Please select at least one user to delete.");
            }
        }
    </script>
</body>

</html>