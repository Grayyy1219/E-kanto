<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book Genre</title>
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


    function addGenre($con, $Genre)
    {
        $sql = "INSERT INTO genre (BookGenre) VALUES (?);";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $Genre);

        try {
            $stmt->execute();
            echo "<script>alert('Genre added successfully!');</script>";
            echo "<script>window.location.href = 'editgenre.php';</script>";
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                echo "<script>alert('Genre already exists!');</script>";
            } else {
                echo "Error adding genre: " . $e->getMessage();
            }
        }

        $stmt->close();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Genre = isset($_POST["genre"]) ? $_POST["genre"] : '';
        addGenre($con, $Genre);
    }
        ?>
        <section>
            <div class="wrapper" id="w3">
                <h2 style="font-size: 30px;">Edit Book Genre</h2><br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    Genre: <input type="text" name="genre" value="" required><br>
                    <input type="submit" value="Add">
                </form>
            </div>
        </section>
</body>

</html>
