<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book Information</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/editbook.css">
</head>

<body>
    <header>
        <a href="javascript:history.go(-1);" class="ahead">
            <img src="Image\left-arrow.png" width="22">
            <h4>Go Back</h4>
        </a>
    </header>

    <?php
    include 'connect.php';

    function getBookDetails($con, $bookId)
    {
        $sql = "SELECT * FROM books WHERE BookID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    function updateBookDetails($con, $bookId, $title, $author, $publisher, $genre, $bookImage, $forsale, $price, $solds)
    {
        $sql = "UPDATE books SET Title=?, Author=?, Publisher=?, Genre=?, BookImage=?, Forsale=?, Price=?, Solds=? WHERE BookID=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssiidi", $title, $author, $publisher, $genre, $bookImage, $forsale, $price, $solds, $bookId);
        $stmt->execute();

        if ($stmt->errno) {
            echo "Error updating record: " . $stmt->error;
        } else {
            echo "<script>alert('Book updated successfully!');</script>";
            echo "<script>window.location.href = 'admin.php#inventory';</script>";
        }

        $stmt->close();
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $bookId = isset($_POST["bookId"]) ? $_POST["bookId"] : '';
        $title = isset($_POST["title"]) ? $_POST["title"] : '';
        $author = isset($_POST["author"]) ? $_POST["author"] : '';
        $publisher = isset($_POST["publisher"]) ? $_POST["publisher"] : '';
        $genre = isset($_POST["genre"]) ? $_POST["genre"] : '';
        $forsale = isset($_POST["forsale"]) ? $_POST["forsale"] : '';
        $price = isset($_POST["price"]) ? $_POST["price"] : '';
        $solds = isset($_POST["solds"]) ? $_POST["solds"] : '';

        if (isset($_FILES['bookImage']) && $_FILES['bookImage']['size'] > 0) {
            $name = $_FILES['bookImage']['name'];
            $tmp_name = $_FILES['bookImage']['tmp_name'];
            $location = "upload/books/$name";
            if (move_uploaded_file($tmp_name, $location)) {
                $bookImage = $location;  // Update $bookImage with the new file path
            } else {
                echo "Error uploading file.";
                exit;
            }
        } else {
            // No new image uploaded, retain the previous image path
            $bookDetails = getBookDetails($con, $bookId);
            $bookImage = $bookDetails['BookImage'];
        }

        // Additional validation can be added here for other form fields.
    
        updateBookDetails($con, $bookId, $title, $author, $publisher, $genre, $bookImage, $forsale, $price, $solds);
    }

    $bookId = isset($_GET["bookId"]) ? $_GET["bookId"] : '';
    $bookDetails = getBookDetails($con, $bookId);

    if ($bookDetails) {
        $genreQuery = "SELECT DISTINCT BookGenre FROM genre";
        $genreResult = mysqli_query($con, $genreQuery);
        $genres = [];
        while ($genreRow = mysqli_fetch_assoc($genreResult)) {
            $genres[] = $genreRow['BookGenre'];
        }
        ?>
        <section>
            <div class="wrapper" id="w3">
                <h2 style="font-size: 30px;">Edit Book Information</h2><br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                    enctype="multipart/form-data">
                    <div class="weditimg" style="width: unset">
                        <?php
                        echo "<img id='profileImage' style='width: unset' src='" . htmlspecialchars($bookDetails['BookImage']) . "' alt=''>";
                        ?>
                        <label class="btn-upload-img">
                            Upload Book Cover <input type="file" id="img" name="bookImage" accept="image/*">
                        </label>
                    </div>
                    <input type="hidden" name="bookId" value="<?php echo htmlspecialchars($bookDetails['BookID']); ?>">
                    Title: <input type="text" name="title"
                        value="<?php echo htmlspecialchars($bookDetails['Title']); ?>"><br>
                    Author: <input type="text" name="author"
                        value="<?php echo htmlspecialchars($bookDetails['Author']); ?>"><br>
                    Publisher: <input type="text" name="publisher"
                        value="<?php echo htmlspecialchars($bookDetails['Publisher']); ?>"><br>
                    Genre: <select name="genre" id="genre">
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= htmlspecialchars($genre) ?>" <?php echo ($bookDetails['Genre'] == $genre) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($genre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br>
                    For Sale:
                    <select id="for_sale" name="forsale" required>
                        <option value="1" <?php echo ($bookDetails['Forsale'] == 1) ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($bookDetails['Forsale'] == 0) ? 'selected' : ''; ?>>No</option>
                    </select><br>

                    Price: <input type="text" name="price"
                        value="<?php echo htmlspecialchars($bookDetails['Price']); ?>"><br>
                    Solds: <input type="text" name="solds"
                        value="<?php echo htmlspecialchars($bookDetails['Solds']); ?>"><br>
                    <input type="submit" value="Update">
                </form>
            </div>
        </section>
        <script>
            document.getElementById('img').addEventListener('change', function (event) {
                const fileInput = event.target;
                const profileImage = document.getElementById('profileImage');

                const file = fileInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        profileImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
        <?php
    } else {
        echo "Book not found";
    }
    ?>
</body>

</html>