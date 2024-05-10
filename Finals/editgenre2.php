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

    function getGenreDetails($con, $GenreID)
    {
        $sql = "SELECT * FROM genre WHERE GenreID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $GenreID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    function updateGenreDetails($con, $GenreID, $title)
    {
        $sql = "UPDATE genre SET BookGenre=? WHERE GenreID=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $title, $GenreID);
        $stmt->execute();

        if ($stmt->errno) {
            echo "Error updating record: " . $stmt->error;
        } else {
            echo "<script>alert('Genre updated successfully!');</script>";
            echo "<script>window.location.href = 'editgenre.php';</script>";
        }

        $stmt->close();
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $GenreID = isset($_POST["GenreID"]) ? $_POST["GenreID"] : '';
        $title = isset($_POST["title"]) ? $_POST["title"] : '';

        // Additional validation can be added here for other form fields.

        updateGenreDetails($con, $GenreID, $title);
    }

    $GenreID = isset($_GET["GenreID"]) ? $_GET["GenreID"] : '';
    $GenreDetails = getGenreDetails($con, $GenreID);

    if ($GenreDetails) {
        ?>
        <section>
            <div class="wrapper" id="w3">
                <h2 style="font-size: 30px;">Edit Book Genre</h2><br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="GenreID" value="<?php echo htmlspecialchars($GenreDetails['GenreID']); ?>">
                    Title: <input type="text" name="title" value="<?php echo htmlspecialchars($GenreDetails['BookGenre']); ?>"><br>
                    <input type="submit" value="Update">
                </form>
            </div>
        </section>
        <?php
    } else {
        echo "Genre not found";
    }
    ?>
</body>

</html>
