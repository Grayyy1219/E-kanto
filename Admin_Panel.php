<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Add your CSS stylesheets here -->
    <link rel="stylesheet" href="admin_Panel.css">

</head>

<body>
    <header>
        <h1>Admin Panel<div id="welcome-message"></div>
        </h1>
        <button onclick="logout()">Logout</button>
    </header>
    <nav>
        <ul>
            <li><a href="#" onclick="showSection('account-settings')">Account Settings</a></li>
            <li><a href="#" onclick="showSection('product-management')">Product Management</a></li>
            <li><a href="#" onclick="showSection('customer-page')">Customer Page</a></li>
            <li><a href="#" onclick="showSection('reports')">Reports</a></li>
            <li><a href="#" onclick="showSection('payment-history')">Payment History</a></li>
        </ul>
    </nav>
    <script>
        function showSection(sectionId) {
            var sections = document.querySelectorAll('.container main section');
            sections.forEach(function(section) {
                section.style.display = 'none';
            });

            var selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.style.display = 'block';
            }
        }
    </script>
    <div class="container">


        <main>
            <!-- Your main content goes here -->

            <section id="account-settings">
                <h2>Admin Details</h2>
                <!-- Add enctype="multipart/form-data" for file uploads -->
                <img id="profile-picture" alt="Profile Picture"> <!-- Add an img tag for the profile picture -->
                <p>Profile Picture</p>
                <form id="admin-details-form" enctype="multipart/form-data">
                    <label for="admin-name">Name:</label>
                    <input type="text" placeholder="not yet set" id="admin-name" name="admin-name">

                    <label for="admin-email">Email:</label>
                    <input type="email" placeholder="not yet set" id="admin-email" name="admin-email">

                    <label for="admin-age">Age:</label>
                    <input type="number" placeholder="not yet set" id="admin-age" name="admin-age">

                    <label for="admin-contact">Contact:</label>
                    <input type="text" placeholder="not yet set" id="admin-contact" name="admin-contact">

                    <!-- Add an input field for the profile picture -->
                    <label for="admin-profile-picture" id="admin-profile-picture-label">Choose Profile Picture</label>
                    <input type="file" id="admin-profile-picture" name="admin-profile-picture">


                    <button type="button" onclick="saveAdminDetails()">Save Details</button>
                </form>

                <h2>Change Password</h2>
                <form id="change-password-form">
                    <label for="current-password">Current Password:</label>
                    <input type="password" id="current-password" name="current-password">

                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new-password">

                    <label for="confirm-new-password">Confirm New Password:</label>
                    <input type="password" id="confirm-new-password" name="confirm-new-password">

                    <button type="button" onclick="changePassword()">Change Password</button>
                </form>
                <h2>Customers</h2>
                <ul id="customer-details"></ul>
                <script>
                    function changePassword() {
                        var currentPassword = document.getElementById("current-password").value;
                        var newPassword = document.getElementById("new-password").value;
                        var confirmNewPassword = document.getElementById("confirm-new-password").value;

                        // Perform client-side validation
                        if (newPassword !== confirmNewPassword) {
                            alert("New passwords do not match!");
                            return;
                        }

                        if (newPassword.length < 4) {
                            alert("New password must be at least 4 characters long.");
                            return;
                        }

                        // Make an AJAX request to the PHP script
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "updatePassAdmin.php", true);
                        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

                        var data = {
                            currentPassword: currentPassword,
                            newPassword: newPassword
                        };

                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                alert(xhr.responseText);
                            } else {
                                alert("Failed to change password. Please try again.");
                            }
                        };

                        xhr.onerror = function() {
                            alert("Failed to change password. Please try again.");
                        };

                        xhr.send(JSON.stringify(data));
                    }
                </script>
                <!-- Customer Table Section -->

                <!-- Customer Table Section -->
                <div id="account-settings">
                    <table id="customerTable">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="customer-details-body"></tbody>
                    </table>
                    <div id="summary"></div>
                    <button id="deleteButton">Delete Selected</button>
                    <button id="createButton">Create Customer</button>

                </div>

                <script>
                    // Add an event listener for the "Create" button
                    document.getElementById("createButton").addEventListener("click", function() {
                        window.location.href = "create_account.html";
                    });
                    // Add an event listener for the "Delete Selected" button
                    document.getElementById("deleteButton").addEventListener("click", function() {
                        confirmChanges("delete");
                    });

                    document.addEventListener("DOMContentLoaded", function() {
                        fetchCustomerDetails();
                    });

                    function fetchCustomerDetails() {
                        var accountSettingsSection = document.getElementById('account-settings');
                        var customerTable = accountSettingsSection.querySelector('#customerTable');
                        var customerListBody = accountSettingsSection.querySelector('#customer-details-body');
                        var summaryDiv = accountSettingsSection.querySelector('#summary');
                        // Example AJAX request in the JavaScript code
                        var xhr = new XMLHttpRequest();
                        xhr.open("GET", "getCustomers.php", true);

                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                try {
                                    // Check if the response text is not empty
                                    if (xhr.responseText.trim() !== "") {
                                        // Parse JSON response
                                        var customers = JSON.parse(xhr.responseText);

                                        // Display customer details
                                        displayCustomerDetails(customers);

                                        // Count the number of blocked and unblocked customers
                                        var totalCustomers = customers.length;
                                        var blockedCount = customers.filter(customer => customer.blocked).length;
                                        var unblockedCount = customers.length - blockedCount;

                                        // Display the summary
                                        var summaryElement = document.getElementById("summary");
                                        if (summaryElement) {
                                            summaryElement.innerHTML = "Total Customers: " + totalCustomers + ", Blocked: " + blockedCount + ", Unblocked: " + unblockedCount;
                                        }
                                    } else {
                                        console.error("Empty JSON response.");
                                        alert("Empty JSON response. Please try again.");
                                    }
                                } catch (error) {
                                    console.error("Error parsing JSON:", error);
                                    alert("Failed to parse JSON. Please try again.");
                                }
                            } else {
                                console.error("Failed to fetch customer details. Status:", xhr.status);
                                alert("Failed to fetch customer details. Please try again.");
                            }
                        };

                        xhr.onerror = function() {
                            console.error("Failed to fetch customer details. Please try again.");
                            alert("Failed to fetch customer details. Please try again.");
                        };

                        xhr.send();
                    }


                    function displayCustomerDetails(customers) {
                        var customerTable = document.getElementById("customerTable");
                        var customerListBody = document.getElementById("customer-details-body");
                        var summaryDiv = document.getElementById("summary");

                        // Clear existing rows in the table body, including the header
                        customerListBody.innerHTML = "";

                        // Iterate through each customer and create rows in the table
                        customers.forEach(function(customer) {
                            var row = customerTable.insertRow();

                            // Add a checkbox in the first cell
                            var checkboxCell = row.insertCell(0);
                            var checkbox = document.createElement("input");
                            checkbox.type = "checkbox";
                            checkbox.value = customer.customer_id;
                            checkbox.className = "delete-checkbox"; // Add a class for easier selection
                            checkboxCell.appendChild(checkbox);

                            // Display customer details in the remaining cells
                            var cellName = row.insertCell(1);
                            var cellEmail = row.insertCell(2);
                            var cellStatus = row.insertCell(3);
                            var cellActions = row.insertCell(4);

                            cellName.textContent = customer.first_name + " " + customer.last_name;
                            cellEmail.textContent = customer.email_address;
                            cellStatus.textContent = customer.blocked ? "Blocked" : "Unblocked";

                            // Create Block and Unblock buttons
                            var blockButton = document.createElement("button");
                            blockButton.textContent = "Block";
                            blockButton.addEventListener("click", function() {
                                updateDatabase(customer.customer_id, true, "block");
                            });

                            var unblockButton = document.createElement("button");
                            unblockButton.textContent = "Unblock";
                            unblockButton.addEventListener("click", function() {
                                updateDatabase(customer.customer_id, false, "unblock");
                            });

                            // Create Edit button
                            var editButton = document.createElement("button");
                            editButton.textContent = "Edit";
                            editButton.addEventListener("click", function() {
                                // Redirect to the edit account page with the customer ID
                                window.location.href = "edit_account.html?customerId=" + customer.customer_id;
                            });

                            // Append buttons to the cell
                            cellActions.appendChild(blockButton);
                            cellActions.appendChild(unblockButton);
                            cellActions.appendChild(editButton);
                        });



                        // Update the summary
                        var totalCustomers = customers.length;
                        var blockedCount = customers.filter(customer => customer.blocked).length;
                        var unblockedCount = totalCustomers - blockedCount;
                        summaryDiv.innerHTML = "Total Customers: " + totalCustomers + ", Blocked: " + blockedCount + ", Unblocked: " + unblockedCount;
                    }

                    function updateDatabase(customerId, isChecked, action) {
                        var data = new URLSearchParams();
                        data.append("customerId", customerId);
                        data.append("isChecked", isChecked ? "1" : "0");
                        data.append("action", action);

                        return fetch("blockunblock.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded",
                                },
                                body: data,
                            })
                            .then(function(response) {
                                if (!response.ok) {
                                    throw new Error("Network response was not ok");
                                }

                                if (response.headers.get("content-type").toLowerCase().includes("text/html")) {
                                    console.error("HTML response received:", response.statusText);
                                    throw new Error("HTML response received");
                                }

                                return response.text();
                            })
                            .then(function(text) {
                                console.log("Response text:", text);

                                try {
                                    var data = JSON.parse(text);
                                    console.log("Database updated successfully:", data);

                                    // Display a success message on the page
                                    var successMessage = document.createElement("div");
                                    successMessage.textContent = (isChecked ? "Blocked" : "Unblocked") + " complete for customer " + customerId;
                                    document.body.appendChild(successMessage);

                                    // Reload the page after a delay (you can adjust the delay as needed)
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000); // 2000 milliseconds (2 seconds)
                                } catch (e) {
                                    console.error("Error parsing JSON:", e);
                                    throw new Error("JSON parse error");
                                }
                            })
                            .catch(function(error) {
                                console.error("Error updating database:", error);
                                if (error.message === "HTML response received") {
                                    console.error("HTML response received. Check server-side code or network issues.");
                                }
                            });
                    }

                    // ...

                    function confirmChanges() {
                        var selectedCheckboxes = document.querySelectorAll('.delete-checkbox:checked');
                        var selectedCustomerIds = Array.from(selectedCheckboxes).map(function(checkbox) {
                            return checkbox.value;
                        });

                        if (selectedCustomerIds.length > 0) {
                            var confirmed = confirm("Are you sure you want to delete the selected customers?");
                            if (confirmed) {
                                // If the user confirms, proceed with the AJAX request
                                executeDeleteRequest(selectedCustomerIds);
                            }
                        } else {
                            alert("Please select at least one customer to delete.");
                        }
                    }

                    function executeDeleteRequest(customerIds) {
                        fetch("delete_customer.php", {
                                method: "DELETE",
                                headers: {
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify({
                                    customerIds: customerIds
                                }),
                            })
                            .then(function(response) {
                                if (!response.ok) {
                                    throw new Error("Network response was not ok");
                                }

                                return response.json();
                            })
                            .then(function(data) {
                                if (data.status === 'success') {
                                    location.reload();
                                } else {
                                    console.error('Error:', data.message);
                                    // Handle errors here if needed
                                }
                            })
                            .catch(function(error) {
                                console.error('Error:', error.message);
                                // Handle errors here if needed
                            });
                    }
                </script>
            </section>

            <!-- Section for Product Management -->
            <section id="product-management">
                <h2>Product Management</h2>
                <h3>Category Management</h3>
                <div id="categories-container"></div>
                <button id="create-category-button" onclick="handleNewCategory()">Create New Category</button>
                <button id="delete-selected-button" onclick="deleteSelectedCategories()">Delete Selected</button>
                <script>
                    //// Update the fetch URL in the handleNewCategory function
                    function removeExistingInputs() {
                        const existingInputs = document.querySelectorAll('.existing-input');
                        existingInputs.forEach(input => {
                            input.parentNode.removeChild(input);
                        });
                    }

                    function createInput(type, placeholder, value, idSuffix) {
                        const input = document.createElement('input');
                        input.type = type;
                        input.placeholder = placeholder;
                        input.value = value;

                        // Create a unique id by appending the suffix
                        const uniqueId = idSuffix ? `new-category-${idSuffix}-input` : '';
                        input.id = uniqueId;

                        return input;
                    }
                    const nameInput = createInput('text', 'Enter the name of the new category', null, 'name');
                    const imageFileInput = createInput('file', null, null, 'image');


                    function createButton(text, id, clickHandler) {
                        const button = document.createElement('button');
                        button.textContent = text;
                        button.id = id;
                        if (clickHandler) {
                            button.addEventListener('click', clickHandler);
                        }
                        return button;
                    }

                    function appendToBody(...elements) {
                        elements.forEach(element => {
                            document.body.appendChild(element);
                        });
                    }

                    function handleNewCategory() {
                        removeExistingInputs();

                        const nameInput = createInput('text', 'Enter the name of the new category', null, 'new-category-name-input');
                        const imageFileInput = createInput('file', null, null, 'new-category-image-input');
                        imageFileInput.accept = 'image/*';

                        const confirmButton = createButton('Confirm', 'new-category-confirm-button');

                        confirmButton.addEventListener('click', function() {
                            const newName = nameInput.value;
                            const file = imageFileInput.files[0];

                            if (newName && file) {
                                createCategory(newName, file);
                                removeExistingInputs(nameInput, imageFileInput, confirmButton);
                            } else {
                                alert('Please enter a name and select an image.');
                            }
                        });

                        appendToBody(nameInput, imageFileInput, confirmButton);
                    }

                    function createCategory(newName, newImage) {
                        const formData = new FormData();
                        formData.append('new_name', newName);
                        formData.append('new_image', newImage);

                        fetch('addCategory.php', {
                                method: 'POST',
                                body: formData,
                            })
                            .then(response => response.text())
                            .then(data => {
                                console.log('Raw Response:', data); // Log the raw response to inspect it

                                try {
                                    const jsonData = JSON.parse(data);
                                    if (jsonData.success) {
                                        console.log('Category added successfully:', jsonData.message);
                                        renderCategories();
                                    } else {
                                        console.error('Error adding category:', jsonData.message);
                                    }
                                } catch (error) {
                                    console.error('Error parsing JSON:', error);
                                }
                            })
                            .catch(error => {
                                console.error('Error adding category:', error);
                            });
                    }

                    function renderCategoriesAsTable(categoriesData) {
                        const categoriesContainer = document.getElementById('categories-container');
                        categoriesContainer.innerHTML = '';

                        if (categoriesData.length > 0) {
                            // Create a table element
                            const table = document.createElement('table');

                            // Create table header
                            const headerRow = table.insertRow();
                            const checkboxHeaderCell = document.createElement('th');
                            checkboxHeaderCell.textContent = 'Select';
                            headerRow.appendChild(checkboxHeaderCell);

                            for (const key in categoriesData[0]) {
                                const headerCell = document.createElement('th');
                                headerCell.textContent = key;
                                headerRow.appendChild(headerCell);
                            }

                            const editHeaderCell = document.createElement('th');
                            editHeaderCell.textContent = 'Edit';
                            headerRow.appendChild(editHeaderCell);

                            // Create table rows
                            categoriesData.forEach(category => {
                                const row = table.insertRow();

                                // Checkbox
                                const checkboxCell = row.insertCell();
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkboxCell.appendChild(checkbox);

                                // Category data cells
                                for (const key in category) {
                                    const cell = row.insertCell();
                                    cell.textContent = category[key];
                                }

                                const editCell = row.insertCell();
                                const editButton = createButton('Edit', 'edit-category-button', function() {
                                    handleEditCategory(row);
                                });
                                editCell.appendChild(editButton);
                            });

                            // Append the table to the container
                            categoriesContainer.appendChild(table);
                        } else {
                            categoriesContainer.textContent = 'No categories found.';
                        }
                    }

                    function deleteSelectedCategories() {
                        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                        const selectedCategories = [];

                        checkboxes.forEach(checkbox => {
                            console.log('Checkbox:', checkbox);
                            if (checkbox.checked) {
                                const categoryRow = checkbox.closest('tr');
                                console.log('Category Row:', categoryRow);

                                // Assuming Category ID is in the second cell (index 1) of the row
                                const categoryId = categoryRow.cells[1].textContent.trim();
                                console.log('Category ID:', categoryId);

                                if (categoryId) {
                                    selectedCategories.push(categoryId);
                                }
                            }
                        });


                        if (selectedCategories.length > 0) {
                            // Make sure you are sending the category IDs as an array in the request body
                            fetch('deleteCategory.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        categoryIds: selectedCategories
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        console.log('Categories deleted successfully:', data.message);
                                        // Add any additional logic or UI updates after successful deletion
                                    } else {
                                        console.error('Error deleting categories:', data.message);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error deleting categories:', error);
                                });
                        } else {
                            alert('Please select categories to delete.');
                        }
                    }




                    // Function to handle editing a category
                    function handleEditCategory(categoryRow) {
                        // Assuming the category ID is in the second cell (index 1) of the row
                        const categoryId = categoryRow.cells[1].textContent;

                        // Prompt the user to type a new name for the category
                        const newName = prompt('Enter the new name for the category:', categoryRow.cells[2].textContent);

                        // Check if the user entered a new name
                        if (newName !== null) {
                            console.log('Editing category with ID:', categoryId, 'to new name:', newName);
                            // Call the function to update the category name in the database
                            updateCategoryName(categoryId, newName);
                        } else {
                            alert('Operation canceled. No new name entered.');
                        }
                    }

                    // Function to update the category name in the database
                    function updateCategoryName(categoryId, newName) {
                        console.log('Updating category name in the database. Category ID:', categoryId, 'New Name:', newName);

                        const formData = new URLSearchParams();
                        formData.append('category_id', categoryId);
                        formData.append('new_name', newName);

                        fetch('updateCategoryName.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: formData.toString(),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Category name updated successfully:', data.message);
                                    // You can perform additional logic or UI updates after a successful update
                                } else {
                                    console.error('Error updating category name:', data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error updating category name:', error);
                            });
                    }






                    // Function to fetch and render categories
                    function fetchAndRenderCategories() {
                        fetch('get_category.php')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(categoriesData => {
                                renderCategoriesAsTable(categoriesData);
                            })
                            .catch(error => {
                                console.error('Error fetching categories:', error.message);
                            });
                    }

                    // Initial fetch and render
                    fetchAndRenderCategories()
                </script>

                <h2>Products</h2>
                <div id="products-container"></div>

                <script>
                    function fetchAndRenderProducts() {
                        fetch('get_products.php')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(productsData => {
                                renderProductsAsTable(productsData);
                            })
                            .catch(error => {
                                console.error('Error fetching products:', error.message);
                            });
                    }

                    function renderProductsAsTable(productsData) {
                        const productsContainer = document.getElementById('products-container');
                        productsContainer.innerHTML = '';

                        if (productsData.length > 0) {
                            const table = createTable(productsData);
                            appendTableHeader(table, productsData[0]);
                            appendTableRows(table, productsData);
                            productsContainer.appendChild(table);

                            const deleteButton = createButton('Delete Selected', 'delete-selected-button', handleDeleteSelected);
                            productsContainer.appendChild(deleteButton);
                        } else {
                            productsContainer.textContent = 'No products found.';
                        }
                    }

                    function createTable(productsData) {
                        const table = document.createElement('table');
                        table.id = 'products-table';
                        return table;
                    }

                    function appendTableHeader(table, sampleProduct) {
                        const headerRow = table.insertRow();

                        const checkboxHeaderCell = document.createElement('th');
                        checkboxHeaderCell.textContent = 'Select';
                        headerRow.appendChild(checkboxHeaderCell);

                        for (const key in sampleProduct) {
                            const headerCell = document.createElement('th');
                            headerCell.textContent = key;
                            headerRow.appendChild(headerCell);
                        }

                        // Add an additional "Actions" header for the Edit button
                        const actionsHeaderCell = document.createElement('th');
                        actionsHeaderCell.textContent = 'Actions';
                        headerRow.appendChild(actionsHeaderCell);
                    }

                    function appendTableRows(table, productsData) {
                        productsData.forEach(product => {
                            const row = table.insertRow();

                            const checkboxCell = row.insertCell();
                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkboxCell.appendChild(checkbox);

                            for (const key in product) {
                                const cell = row.insertCell();
                                cell.textContent = product[key];
                            }

                            // Add an "Edit" button to each row
                            const editCell = row.insertCell();
                            const editButton = createButton('Edit', 'edit-product-button', function() {
                                handleEditProduct(product);
                            });
                            editCell.appendChild(editButton);

                            const buyCell = row.insertCell();
                            const buyButton = document.createElement('input');
                            buyButton.type = 'submit';
                            buyButton.className = 'edit';
                            buyButton.value = 'Buy';
                            buyButton.onclick = function() {
                                openPopup(product.ItemID, product.Price);
                            };
                            buyCell.appendChild(buyButton);
                        });
                    }

                    function openPopup(bookId, price) {
                        var quantity = prompt("Enter Quantity:", "1");
                        if (quantity !== null) {
                            window.location.href = `purchaseItems3.php?selectedItems=${bookId}&Quantity=${quantity}&Price=${price}`;
                        }
                    }


                    function createButton(label, id, clickHandler) {
                        const button = document.createElement('button');
                        button.textContent = label;
                        button.id = id;
                        button.addEventListener('click', clickHandler);
                        return button;
                    }

                    function handleEditProduct(product) {
                        // Redirect to the page for editing the selected product
                        window.location.href = `Edit_Product.html?productId=${product.ItemID}`;
                    }

                    function handleDeleteSelected() {
                        const checkboxes = document.querySelectorAll('#products-container input[type="checkbox"]:checked');
                        if (checkboxes.length > 0) {
                            const selectedProductIds = Array.from(checkboxes).map(checkbox => {
                                const productId = checkbox.closest('tr').cells[1].textContent;
                                return productId;
                            });

                            fetch('delete_products.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        productIds: selectedProductIds
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! Status: ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('Delete response:', data);
                                    fetchAndRenderProducts(); // Refresh the table after deletion
                                })
                                .catch(error => {
                                    console.error('Error deleting products:', error.message);
                                });
                        }
                    }

                    fetchAndRenderProducts();
                </script>

                <button id="create-product-button" onclick="redirectToCreateProduct()">Create New Product</button>

                <script>
                    function redirectToCreateProduct() {
                        // Redirect to the page for creating new products
                        window.location.href = 'Create_Product.html';
                    }
                </script>



            </section>
            <script>
                function confirmAdminPassword() {
                    // Prompt the user for the admin password
                    var adminPassword = prompt("Please enter the admin password:");

                    if (adminPassword === null) {
                        return false; // User canceled
                    }

                    // Create a synchronous XMLHttpRequest
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "verify-admin-password.php", false); // Third parameter is set to false for synchronous request
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    // Send the admin password to the server for verification
                    xhr.send("adminPassword=" + encodeURIComponent(adminPassword));

                    if (xhr.status === 200) {
                        var result = xhr.responseText;
                        if (result === "success") {
                            alert("Update Successful.");
                            return true; // Password is correct, proceed
                        } else {
                            alert("Incorrect admin password.  Upload Cancelled.");
                            return false;
                        }
                    } else {
                        console.error('Server error: ' + xhr.status);
                        alert('An error occurred while verifying the admin password.  Upload cancelled.');
                        return false;
                    }
                }
            </script>
            <section id="customer-page">
                <h2>Logo Upload</h2>
                <form id="logo-form" enctype="multipart/form-data" action="upload-logo.php" method="post" onsubmit="return confirmAdminPassword()">
                    <!-- Upload Logo Section -->
                    <label for="logo-image">Upload Logo:</label>
                    <input type="file" id="logo-image" name="logo-image">
                    <button type="submit" name="upload-logo">Upload Logo</button>
                </form>

                <!-- Upload Background Picture Section -->
                <h2>Background Image Upload</h2>
                <form id="logo-form" enctype="multipart/form-data" action="upload-background.php" method="post" onsubmit="return confirmAdminPassword()">
                    <label for="background-image">Upload Background Picture:</label>
                    <input type="file" id="background-image" name="background-image">

                    <button type="submit" name="upload-background">Upload Background</button>
                </form>
                <h2>Background Color Update</h2>
                <form method="post" action="update_background_color.php" onsubmit="return confirmAdminPassword()">
                    <label for="background-color">New Background Color:</label>
                    <input type="color" id="background-color" name="background-color" value="#f0f0f0">
                    <button type="submit" name="update-background-color">Update Background Color</button>
                </form>
                <script>
                    // Function to fetch background color from PHP script
                    function getBackgroundColor(callback) {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                var color = xhr.responseText;
                                callback(color);
                            }
                        };

                        xhr.open('GET', 'get_background_color.php', true);
                        xhr.send();
                    }

                    // Function to update background color dynamically
                    function updateBackgroundColor() {
                        var newColor = document.getElementById('background-color').value;

                        // Make an AJAX request to update the background color in the database
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Background color updated successfully
                                document.body.style.backgroundColor = newColor;
                                alert('Background color updated successfully.');
                            } else if (xhr.readyState === 4 && xhr.status !== 200) {
                                // Error updating background color
                                alert('Error updating background color. Please try again.');
                            }
                        };

                        xhr.open('POST', 'update_background_color.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.send('background-color=' + encodeURIComponent(newColor));

                        // Prevent the form from submitting in the traditional way
                        return false;
                    }

                    // Fetch the initial background color and apply it on page load
                    getBackgroundColor(function(initialColor) {
                        document.body.style.backgroundColor = initialColor;
                    });
                </script>
                <!-- ... (existing content) -->
            </section>
            
            <section id="discount-management">
    <h2>Discount Management</h2>

    <div id="discount-form-container">
        <h3>Create New Discount Code</h3>
        <input type="text" id="discount-code-input" placeholder="Enter discount code">
        <input type="number" id="discount-percentage-input" placeholder="Enter discount percentage" min="1" max="100">
        <label for="discount-active-checkbox">Active</label>
        <input type="checkbox" id="discount-active-checkbox" checked>
        <button id="create-discount-button" onclick="handleCreateDiscount()">Create Discount Code</button>
    </div>

    <h3>Existing Discount Codes</h3>
    <div id="discounts-container"></div>
</section>


            <section id="reports">
                <h1>Fast Moving items</h1>
                <div class="reports">
                    <?php include 'atrend1.php'; ?>
                </div>
                <h1>Slow Moving items</h1>
                <div class="reports">
                    <?php include 'atrend2.php'; ?>
                </div>
                <a href="printreport.php">
                    <div class="button"><button>Print Report</button></div>
                </a>
            </section>

            <section id="payment-history">
                <h2>Payment History</h2>
                <form method="post" action="">
    <label for="filter_criteria">Select Filter Criteria:</label>
    <select name="filter_criteria" id="filter_criteria">
        <option value="customer_id">Customer ID</option>
        <option value="payment_date">Payment Date</option>
        <!-- Add more options as needed -->
    </select>
    <br>
    <label for="filter_value">Enter Filter Value:</label>
    <input type="text" name="filter_value" id="filter_value">
    <br>

    <!-- Date range inputs -->
    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" id="start_date">
    <br>
    <label for="end_date">End Date:</label>
    <input type="date" name="end_date" id="end_date">
    <br>

    <input type="submit" value="Filter">
</form>
<form action="paymentprint.php" method="get">
<input type="hidden" name="start_date" value="<?php echo $_POST['start_date']; ?>" >
<input type="hidden" name="end_date" value="<?php echo $_POST['end_date']; ?>" >
<input type="submit" name="submit" value="Generate">
</form>

<?php
// Include the database connection file
include 'connect_db.php';

// Default query without filtering
$sql = "SELECT payment_id, order_id, customer_id, payment_date, amount_paid, payment_mode FROM payment";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if keys are set before using them
    $filter_criteria = isset($_POST['filter_criteria']) ? mysqli_real_escape_string($conn, $_POST['filter_criteria']) : "";
    $filter_value = isset($_POST['filter_value']) ? mysqli_real_escape_string($conn, $_POST['filter_value']) : "";
    $start_date = isset($_POST['start_date']) ? mysqli_real_escape_string($conn, $_POST['start_date']) : "";
    $end_date = isset($_POST['end_date']) ? mysqli_real_escape_string($conn, $_POST['end_date']) : "";

    // Modify the query with a WHERE clause to filter results if criteria and value are provided
    if (!empty($filter_criteria) && !empty($filter_value)) {
        $sql .= " WHERE $filter_criteria LIKE '%$filter_value%'";

        // Add date range conditions if the payment_date is selected
        if ($filter_criteria === 'payment_date' && !empty($start_date) && !empty($end_date)) {
            $sql .= " AND payment_date BETWEEN '$start_date' AND '$end_date'";
        }
    } elseif (!empty($start_date) && !empty($end_date)) {
        // Only date filter is selected
        $sql .= " WHERE payment_date BETWEEN '$start_date' AND '$end_date'";
    }
}

$result = $conn->query($sql);

echo "<h2>Payment Records</h2>";

echo "<table class='tab'>
        <tr class='tr'>
            <th class='th'>Payment ID</th>
            <th class='th'>Order ID</th>
            <th class='th'>Customer ID</th>
            <th class='th'>Payment Date</th>
            <th class='th'>Amount Paid</th>
            <th class='th'>Payment Mode</th>
        </tr>";

if ($result !== false && $result->num_rows > 0) {
    // Output data from each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td class='td'>{$row['payment_id']}</td>
                <td class='td'>{$row['order_id']}</td>
                <td class='td'>{$row['customer_id']}</td>
                <td class='td'>{$row['payment_date']}</td>
                <td class='td'>{$row['amount_paid']}</td>
                <td class='td'>{$row['payment_mode']}</td>
              </tr>";
    }
} else {
    echo "<tr class='tr'><td class='td' colspan='6'>No records found</td></tr>";
}

echo "</table>";

// Close the database connection
$conn->close();
?>
            </section>
        </main>
    </div>
    <footer>
        <a href="" class="link">Conditions of Use</a>
        <a href="" class="link">Privacy Notice</a>
        <a href="" class="link">Help</a>
        <br>© 1996-2023, E-kanto or its affiliate
    </footer>

    <!-- Add your JavaScript scripts here -->
    <script src="adminPanelDetails.js" defer></script>
    <script>
        function logout() {
            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Make an AJAX request to the PHP script
            xhr.open("GET", "Adlogout.php", true);

            xhr.onload = function() {
                console.log(xhr.responseText); // Log the response to the console
                if (xhr.status === 200) {
                    // Parse JSON response
                    try {
                        var response = JSON.parse(xhr.responseText);
                        alert(response.message);
                        if (response.message === "Logout successful") {
                            // Redirect to the login page or perform any other action after logout
                            window.location.href = "admin_login.html";
                        }
                    } catch (error) {
                        console.error("Error parsing JSON response:", error);
                    }
                } else {
                    alert("Failed to logout. Please try again.");
                }
            };

            xhr.onerror = function() {
                alert("Failed to logout. Please try again.");
            };

            xhr.send();
        }
    </script>
</body>

</html>