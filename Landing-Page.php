<?php
session_start();
include('connect_db.php');

// Fetch the background color from the database
$sql = "SELECT background_color FROM background_color_settings ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

// Set a default background color in case there is no result
$backgroundColor = '#f0f0f0';

if ($result !== false && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Check if $row is not null before accessing its properties
    if ($row !== null && isset($row['background_color'])) {
        $backgroundColor = $row['background_color'];
    }
}

// Fetch the background image file path from the database
$sql = "SELECT filename FROM background_images ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $backgroundImagePath = 'background-uploads/' . $row['filename'];
} else {
    $backgroundImagePath = '';  // Set a default background image path or handle this case as needed
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-kanto</title>
    <link rel="stylesheet" href="E-kanto.css">
    <style>
        body {
            background-color: <?php echo $backgroundColor; ?>;
            /* Apply the fetched background color */}
        </style>
</head>

<body>
    <div class="background-container" style="background-image: url('<?php echo $backgroundImagePath; ?>');
    background-size: 100%;
    background-repeat: no-repeat;">
        <div class="header-container">
            <?php
            $sql = "SELECT filename FROM logos WHERE id = 1"; // Assuming the logo has an ID of 1
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $logoFilePath = 'logo-uploads/' . $row['filename'];
            } else {
                $logoFilePath = 'default-logo.png';  // Set a default logo path or handle this case as needed
            }
            ?>
            <div class="logo-container">
                <img class="logo" src="<?php echo $logoFilePath; ?>" alt="E-kanto logo">
                <h1>E-Kanto</h1>
            </div>
            <nav class="navbar">
            <form action="search.php" method="get" class="search-bar">
                    <input type="text" name="query" placeholder="Search...">
                    <button type="submit">Search</button>
                </form>
                <?php
                if (isset($_SESSION["user_id"])) {
                    $user_id = $_SESSION["user_id"];
                    $query = "SELECT * FROM customer_info WHERE customer_id = $user_id";
                    $result = mysqli_query($conn, $query);

                    // Check if the query was successful
                    if ($result) {
                        // Fetch user details as an associative array
                        $userDetails = mysqli_fetch_assoc($result);
                    } else {
                        // Handle the query error
                        die("Database query error: " . mysqli_error($conn));
                    }
                    $profilePicture = isset($userDetails['profile_picture']) ? $userDetails['profile_picture'] : 'default-profile.png';

                    echo '<a href="account-settings.php">';
                    echo '<img class="accIcon" src="' . $profilePicture . '" alt="Account Icon">';
                    echo '</a>';
                    echo '<a href="cart.php">';
                    echo '<img class="cart-image" src="shopping-cart.png" alt="Cart Icon">';
                    echo '<span class="cart-counter">0</span>';
                    echo '</a>';
                    echo '<a href="logout.php">Logout</a>';
                } else {
                    echo '<a href="Sign-In.html">Sign In</a>';
                    echo '<a href="Sign-Up.html">Sign Up</a>';
                }
                ?>
            </nav>
        </div>
    </div>

    <div class="image-container" id="landingPage">
        <div class="slideshow-container">
            <div class="mySlides fade">
                <div img class="numbertext"></div>
                <img class="imgSlide" src="salelarge1.avif">
            </div>

            <div class="mySlides fade">
                <div class="numbertext"></div>
                <img class="imgSlide" src="salelarge2.avif">
            </div>

            <div class="mySlides fade">
                <div class="numbertext"></div>
                <img class="imgSlide" src="salelarge3.jpg">
            </div>
            <br>
            <div style="text-align:center">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </div>

    <?php include('categories.php'); ?>
    <div class="trend"><?php include('trend.php'); ?></div>
    <footer>
        <a href="" class="link">Conditions of Use</a>
        <a href="" class="link">Privacy Notice</a>
        <a href="" class="link">Help</a>
        <br>© 1996-2023, E-kanto or its affiliate
    </footer>

    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1;
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            setTimeout(showSlides, 3000); // Change image every 2 seconds
        }
    </script>
</body>

</html>
