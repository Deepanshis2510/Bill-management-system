<?php
// Include database connection
require_once './config/db_connection.php';

// Fetch all banks
$query = "SELECT * FROM banks";
$stmt = $pdo->query($query);
$banks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Add a new bank
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_bank'])) {
    $bank_name = htmlspecialchars($_POST['bank_name']);

    if (!empty($bank_name)) {
        $insertQuery = "INSERT INTO banks (bank_name) VALUES (:bank_name)";
        $stmt = $pdo->prepare($insertQuery);
        $stmt->bindParam(':bank_name', $bank_name);
        $stmt->execute();
        header("Location: manage_banks.php"); // Refresh the page to reflect the changes
        exit;
    }
}

// Delete a bank
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    $deleteQuery = "DELETE FROM banks WHERE id = :id";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->bindParam(':id', $deleteId);
    $stmt->execute();
    header("Location: manage_banks.php"); // Refresh the page to reflect the changes
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Banks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        button {
            padding: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #c0392b;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        input[type="text"] {
            padding: 8px;
            font-size: 14px;
            width: 200px;
            margin-right: 10px;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #34495e;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Manage Banks</h2>

        <!-- Table displaying all banks -->
        <table>
            <tr>

                <th>Bank Name</th>
                <th>Action</th>
            </tr>
            <?php foreach ($banks as $bank): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bank['bank_name']); ?></td>
                    <td><a href="?delete_id=<?php echo $bank['id']; ?>"><button>Delete</button></a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Form to add a new bank -->
        <form action="manage_banks.php" method="POST">
            <label for="bank_name">Add New Bank: </label>
            <input type="text" id="bank_name" name="bank_name" required>
            <button type="submit" name="add_bank">Add Bank</button>
        </form>

        <!-- Back button -->
        <a href="admin_interface.php" class="back-button">← Back</a>
    </div>

</body>
</html>
