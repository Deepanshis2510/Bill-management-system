<?php
// Start the session
session_start();

// Include the database connection
include './config/db_connection.php';

// Handle the deletion of a department
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare the delete query
    $delete_query = "DELETE FROM department WHERE id = ?";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute([$delete_id]);

    // Redirect back to the department list page
    header("Location: department_list.php");
    exit;
}

// Fetch all departments
$query = "SELECT * FROM department";
$stmt = $pdo->prepare($query);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department List</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .back-button {
            margin-bottom: 20px;
            text-align: left;
        }

        .back-button a {
            display: inline-block;
            background-color: #2c3e50;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button a:hover {
            background-color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        a {
            color: #2c3e50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        a.delete {
            color: red;
        }

        a.delete:hover {
            color: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <div class="back-button">
            <a href="user_interface.php">Back</a>
        </div>

        <h1>Department List</h1>

        <!-- Department Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($departments)): ?>
                    <?php foreach ($departments as $department): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($department['id']); ?></td>
                            <td><?php echo htmlspecialchars($department['department_name']); ?></td>
                            <td>
                                <a href="department_list.php?delete_id=<?php echo $department['id']; ?>" 
                                    onclick="return confirm('Are you sure you want to delete this department?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No departments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
