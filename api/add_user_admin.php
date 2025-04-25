<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User/Admin/Super Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }
        .button-group button {
            padding: 10px;
            flex: 1;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .add-button {
            background-color: #28a745;
        }
        .add-button:hover {
            background-color: #218838;
        }
        .back-button {
            background-color: #007bff;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                alert('User added successfully!');
            }
        };
    </script>
</head>
<body>

<div class="form-container">
    <h2>Add User/Admin/Super Admin</h2>
    <form action="add_user.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
                <option value="super_admin">Super Admin</option>
            </select>
        </div>
        <div class="form-group button-group">
            <button type="submit" class="add-button">Add</button>
            <button type="button" class="back-button" onclick="window.history.back();">Back</button>
        </div>
    </form>
</div>

</body>
</html>
