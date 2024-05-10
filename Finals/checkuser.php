<?php
include("connect.php");

if (isset($_POST['submit'])) {
    $enteredUsername = $_POST['user'];
    $enteredPassword = $_POST['pass'];

    // Use prepared statement to protect against SQL injection
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $enteredUsername);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $hashedPassword = $row['password'];

        // Check if the entered password matches the stored hashed password or non-hashed password
        if (password_verify($enteredPassword, $hashedPassword) || $enteredPassword == $hashedPassword) {
            // Your existing code for updating the current user and redirecting
            $username = $row["username"];
            $firstName = $row['FName'];
            $lastName = $row['LName'];
            $email = $row['email'];
            $address = $row['address'];
            $phones = $row['phone'];
            $profile = mysqli_real_escape_string($con, $row['profile']);

            $updateQuery = mysqli_query($con, "UPDATE currentuser SET FName = '$firstName', LName = '$lastName', username = '$username', Email = '$email', address ='$address', phone = '$phones', profile = '$profile' WHERE UserId = 1");

            if ($updateQuery) {
                if ($row['admin'] == 1) {
                    echo '<script>alert("Successfully logged in as admin");</script>';
                    echo '<script>window.location.href = "admin.php";</script>';
                } else {
                    echo '<script>alert("Successfully logged in");</script>';
                    echo '<script>window.location.href = "Landingpage.php";</script>';
                }
                exit();
            } else {
                echo '<script>alert("Error on login, try again later");</script>';
            }
        } else {
            // Password is incorrect
            echo '<script>alert("Incorrect Password");</script>';
        }
    } else {
        // User not found
        echo '<script>alert("User not found or invalid credentials");</script>';
    }

    // Redirect to Landingpage.php for all cases of login failure
    echo '<script>window.location.href = "Landingpage.php";</script>';
    exit();
}
?>