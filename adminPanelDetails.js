// Declare adminData globally
let adminData = {};
let welcomeMessage; // Declare welcomeMessage globally
let adminProfilePictureInput; // Declare adminProfilePictureInput globally
let profilePicture; // Declare profilePicture globally
let adminNameInput;
let adminEmailInput;
let adminAgeInput;
let adminContactInput;

function setAdminInfo() {
    // DOM elements
    welcomeMessage = document.getElementById("welcome-message"); // Assign to the global variable
    profilePicture = document.getElementById("profile-picture");
    adminNameInput = document.getElementById("admin-name");
    adminEmailInput = document.getElementById("admin-email");
    adminAgeInput = document.getElementById("admin-age");
    adminContactInput = document.getElementById("admin-contact");
    adminProfilePictureInput = document.getElementById("admin-profile-picture"); // Move this declaration to the global scope

    // Fetch admin details directly from the PHP script
    fetchAdminDetails()
        .then(data => {
            if (data) {
                updateAdminInfo(data);
            }
        })
        .catch(error => {
            console.error('Error fetching admin details:', error);
        });
}

function fetchAdminDetails() {
    return fetch('get_admin_details.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(`Error fetching admin details: ${data.error}`);
            }
            return data;
        });
}

function updateAdminInfo(data) {
    // Update welcomeMessage based on adminDetails
    welcomeMessage.textContent = `Welcome, ${data.name}!`;

    adminData = {
        name: data.name,
        email: data.email,
        age: data.age,
        contact: data.contact,
        profilePicture: data.profilePicture
    };

    welcomeMessage.textContent = `Welcome, ${adminData.name}!`;
    profilePicture.src = adminData.profilePicture;
    adminNameInput.value = adminData.name;
    adminEmailInput.value = adminData.email;
    adminAgeInput.value = adminData.age;
    adminContactInput.value = adminData.contact;

    // Handle the file input differently (display file name, etc.)
    if (adminData.profilePicture) {
        // Assuming you have an element to display the file name
        const fileNameDisplay = document.getElementById("profile-picture-file-name");
        if (fileNameDisplay) {
            fileNameDisplay.textContent = adminData.profilePicture; // Display the file name or any other information
        }
    }
}

function saveAdminDetails() {
    const profilePictureInput = document.getElementById("admin-profile-picture");

    // Validate form inputs
    if (!validateFormInputs()) {
        return;
    }

    const formData = new FormData();
    formData.append("admin-name", adminNameInput.value);
    formData.append("admin-email", adminEmailInput.value);
    formData.append("admin-age", adminAgeInput.value);
    formData.append("admin-contact", adminContactInput.value);
    formData.append("admin-profile-picture", profilePictureInput.files[0]);

    // Perform the AJAX request to save admin details
    fetch('save_admin_details.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                console.log('Admin details saved:', data.message);
                // Optionally, update the displayed admin details immediately
                setAdminInfo();
            } else {
                console.error('Error saving admin details:', data.message);
            }
        })
        .catch(error => {
            console.error('Error saving admin details:', error);
        });
}

function validateFormInputs() {
    // Add form validation logic here if needed
    return true; // Placeholder, replace with actual validation
}

// Call setAdminInfo() when the page loads
document.addEventListener('DOMContentLoaded', setAdminInfo);
