<script>
    function checkAdminPassword() {
        // Prompt the user for the admin password
        var enteredPassword = prompt("Enter Admin Password:");

        // Check if the entered password is correct
        if (enteredPassword === "yourAdminPassword") {
            return true; // Password is correct
        } else if (enteredPassword === null) {
            return "canceled"; // User canceled the prompt
        } else {
            return false; // Incorrect password
        }
    }

    // Example usage:
    var result = checkAdminPassword();

    if (result === true) {
        // Code to execute if the password is correct
        alert("Password is correct!");
    } else if (result === "canceled") {
        // Code to execute if the user canceled the prompt
        alert("Prompt canceled.");
    } else {
        // Code to execute if the password is incorrect
        alert("Incorrect password.");
    }
</script>