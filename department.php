<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Entry Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: white;
            padding: 35px;
            border-radius: 7px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="submit"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color:  #2c3e50;;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color:  #2c3e50;;
        }
        .alert {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color:  #2c3e50; ;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 70%;
        }
        .back-button:hover {
            background-color: #2980b9 #2c3e50;;
        }
    </style>
</head>
<body>

<div class="form-container">
<div class="back-button-container">
            <a href="user_interface.php" class="back-button">← Back</a>
        </div>
    <h2>Enter Department Name</h2>
    <?php
    // Check if there is a success message
    if (isset($_GET['success'])) {
        echo '<div class="alert">Department added successfully!</div>';
    }
    ?>
    <form action="./api/submit_department.php" method="post">
        <label for="departmentName">Department Name:</label>
        <input type="text" id="departmentName" name="departmentName" required>

        <input type="submit" value="Submit">
    </form>
</div>

</body>
</html>
