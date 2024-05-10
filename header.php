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
            /* Apply the fetched background color */
        }
    </style>
</head>

<body>
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
               <?php echo"<img class='logo' src='$logoFilePath' alt='E-kanto logo'>"; ?>
                <h1>E-Kanto</h1>
            </div>
            <nav class="navbar">
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
</body>

</html>
