<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bank</title>
    <style>
        /* styles.css */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 25%;
            margin: 80px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            color: #34495e;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            padding: 12px;
            background-color: #2c3e50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #34495e;
        }

        button:active {
            background-color: #1a242f;
        }

        .back-button {
            padding: 12px;
            background-color: #e74c3c;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #c0392b;
        }

        .back-button:active {
            background-color: #992d22;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Bank Name</h2>
        <form action="./api/add_bank.php" method="POST">
            <label for="bank_name">Bank Name:</label>
            <input type="text" id="bank_name" name="bank_name" required>
            <button type="submit">Add Bank</button>
        </form>
        <a href="user_interface.php">
            <button type="button" class="back-button">Back</button>
        </a>
    </div>
</body>
</html>
