<?php
// Include the database connection file
include('connect_db.php');

// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if not logged in
    header("Location: Sign-In.html");
    exit();
}

// Retrieve user details from the database
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - E-Kanto</title>
    <link rel="stylesheet" href="E-kanto.css">
</head>

<body>
    <form action="landing-page.php">
        <button class="goback" type="submit">Go Back</button>
    </form>
    <div class="account-settings-container">
        <!-- Display circular profile picture -->
        <div class="profile-picture-container">
            <?php
            // Use the user's current profile picture or a default image
            $profilePicture = isset($userDetails['profile_picture']) ? $userDetails['profile_picture'] : '"C:\xampp\htdocs\E-kanto\default-profile.png"';
            ?>
            <img class="profile-picture" src="<?php echo "$profilePicture"; ?>" alt="Profile Picture" width='250'>
        </div>

        <h2>Account Settings</h2>
        <?php
        // Display a message if the form is submitted successfully
        if (isset($_GET["success"]) && $_GET["success"] == "true") {
            echo "<p>Account updated successfully!</p>";
        }
        ?>

        <!-- Section: Changing Profile Picture -->
        <h3>Changing Profile Picture</h3>
        <form action="update-profile-picture.php" method="post" enctype="multipart/form-data">
            <label for="profile_picture">Upload new profile picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
            <button type="submit">Update Profile Picture</button>
        </form>

        <!-- Section: Changing Password -->
        <h3>Changing Password</h3>
        <form action="update-password.php" method="post">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_new_password">Confirm New Password:</label>
            <input type="password" id="confirm_new_password" name="confirm_new_password" required>

            <button type="submit">Update Password</button>
        </form>

        <!-- Section: Viewing and Editing Customer Details -->
        <h3>Customer Details</h3>
        <form action="update-account-information.php" method="post">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo isset($userDetails['first_name']) ? $userDetails['first_name'] : ''; ?>">

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo isset($userDetails['last_name']) ? $userDetails['last_name'] : ''; ?>">

            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name" value="<?php echo isset($userDetails['middle_name']) ? $userDetails['middle_name'] : ''; ?>">

            <label for="email_address">Email Address:</label>
            <input type="email" id="email_address" name="email_address" value="<?php echo isset($userDetails['email_address']) ? $userDetails['email_address'] : ''; ?>">

            <label for="contact_number">Contact Number:</label>
            <input type="tel" id="contact_number" name="contact_number" value="<?php echo isset($userDetails['contact_number']) ? $userDetails['contact_number'] : ''; ?>">

            <!-- Add other fields as needed -->

            <button type="submit">Update Account Information</button>
        </form>
        <h3>Order History</h3>
<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Quantity</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Retrieve user's orders from the database
        $customer_id = $_SESSION["user_id"]; // Assuming the customer_id is stored in the session
        $query = "SELECT * FROM orders WHERE customer_id = $customer_id ORDER BY order_date DESC";
        $result = mysqli_query($conn, $query);

        // Check if there are any orders
        if (mysqli_num_rows($result) > 0) {
            // Loop through each order and display its details
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['order_id'] . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td>" . $row['order_quantity'] . "</td>";
                echo "<td>" . $row['total_amount'] . "</td>";
                echo "</tr>";
            }
        } else {
            // Display a message if no orders found
            echo "<tr><td colspan='4'>No orders found.</td></tr>";
        }
        ?>
    </tbody>
</table>
    </div>
   

</body>

</html>