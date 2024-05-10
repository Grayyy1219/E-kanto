<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book Information</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/editbook.css">
    <style>
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <?php
    include("connect.php");
    include("query.php");
    $genreQuery = "SELECT DISTINCT BookGenre FROM genre";
    $genreResult = mysqli_query($con, $genreQuery);
    $genres = [];
    while ($genreRow = mysqli_fetch_assoc($genreResult)) {
        $genres[] = $genreRow['BookGenre'];
    }
    ?>
    <header>
        <a href="javascript:history.go(-1);" class="ahead">
            <img src="Image\left-arrow.png" width="22">
            <h4>Go Back</h4>
        </a>
    </header>
    <section>
        <div class="wrapper" id="w3">
            <h2 style="font-size: 30px;">Edit Book Information</h2><br>
            <form method="post" action="process_add_book.php" enctype="multipart/form-data">
                <div class="weditimg" style="width: unset">
                    <img id='profileImage' style='width: unset' src=''>
                    <label class="btn-upload-img">
                        Upload Book Cover <input type="file" id="img" name="bookImage" accept="image/*">
                    </label>
                </div>
                Title: <input type="text" name="title"><br>
                Author: <input type="text" name="author"><br>
                Publisher: <input type="text" name="publisher"><br>
                Genre: <select name="genre" id="genre">
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?= $genre ?>" <?= $genre ?>>
                            <?= $genre ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                For Sale: <select id="for_sale" name="for_sale" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
                Price: <input type="text" name="price"><br>
                Quantity: <input type="text" name="quantity"><br>
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
</body>

</html>