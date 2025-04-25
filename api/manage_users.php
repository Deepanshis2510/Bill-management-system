<?php
session_start();
include '../config/db_connection.php';

// Check if an admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle delete, disable, or activate actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    if (isset($_POST['delete'])) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $message = "User deleted successfully!";
    } elseif (isset($_POST['disable'])) {
        $query = "UPDATE users SET status = 'disabled' WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $message = "User disabled successfully!";
    } elseif (isset($_POST['activate'])) {
        $query = "UPDATE users SET status = 'active' WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $message = "User activated successfully!";
    }
}

// Fetch all users
$query = "SELECT id, username, email, status FROM users";
$stmt = $pdo->query($query);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f3f4f6;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #4caf50;
            color: white;
        }

        header h1 {
            margin: 0;
        }

        header a {
            text-decoration: none;
            color: white;
            background-color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        header a:hover {
            background-color: #555;
        }

        main {
            padding: 20px 30px;
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #4caf50;
            color: white;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        .btn-disable {
            background-color: #ff9800;
            color: white;
        }

        .btn-disable:hover {
            background-color: #fb8c00;
        }

        .btn-activate {
            background-color: #4caf50;
            color: white;
        }

        .btn-activate:hover {
            background-color: #43a047;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #888;
        }
    </style>
    <script>
        function confirmAction(action) {
            return confirm(`Are you sure you want to ${action}?`);
        }
    </script>
</head>
<body>

<header>
    <h1>Manage Users</h1>
    <a href="../admin_interface.php">Back</a>
</header>

<main>
    <?php if (isset($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['status']); ?></td>
                    <td>
                        <form method="POST" style="display: inline;" onsubmit="return confirmAction('delete this user')">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-delete">Delete</button>
                        </form>
                        <form method="POST" style="display: inline;" onsubmit="return confirmAction('disable this user')">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="disable" class="btn btn-disable">Disable</button>
                        </form>
                        <form method="POST" style="display: inline;" onsubmit="return confirmAction('activate this user')">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="activate" class="btn btn-activate">Activate</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<footer>
    © <?php echo date("Y"); ?> Manage By Webclouds
</footer>

</body>
</html>
