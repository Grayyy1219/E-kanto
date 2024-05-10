<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Search</title>
    <link rel="stylesheet" href="css/header.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            form {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
     <header >
        <a href="admin.php" class="ahead">
        <img src="Image\left-arrow.png" width="22">
            <h4>Go Back</h4>
        </a>
     </header>

    <h2>Book Search</h2>

    <form method="post" action="search.php">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title">

        <label for="author">Author:</label>
        <input type="text" name="author" id="author">

        <label for="genre">Genre:</label>
        <input type="text" name="genre" id="genre">

        <input type="submit" value="Search">
    </form>

</body>

</html>