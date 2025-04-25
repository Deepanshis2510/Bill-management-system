<?php
// edit_bill.php
session_start(); // Start session
include '../config/db_connection.php'; // Include the database connection

// Check if the bill ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid bill ID.";
    exit;
}

$bill_id = $_GET['id'];

// Fetch the bill details to populate the form
try {
    $query = "SELECT * FROM bills WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$bill_id]);
    $bill = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bill) {
        echo "Bill not found.";
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching bill details: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bill</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
         <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
            box-sizing: border-box; /* Ensures padding is included in width */
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Bill</h1>
        <form action="final_edit.php?id=<?php echo $bill_id; ?>" method="POST" enctype="multipart/form-data">
            <label for="bill_no">Bill No:</label>
            <input type="text" id="bill_no" name="bill_no" value="<?php echo htmlspecialchars($bill['bill_no']); ?>" readonly   required> <br>


            <label for="bill_date">Bill Date:</label>
            <input type="date" id="bill_date" name="bill_date" value="<?php echo htmlspecialchars($bill['bill_date']); ?>" readonly required><br>

            <label for="taxable_amount">Taxable Amount:</label>
            <input type="number" id="taxable_amount" name="taxable_amount" value="<?php echo htmlspecialchars($bill['taxable_amount']); ?>" required><br>

            <label for="tax_amount">Tax Amount:</label>
            <input type="number" id="tax_amount" name="tax_amount" value="<?php echo htmlspecialchars($bill['tax_amount']); ?>" required><br>

            <label for="invoice_amount">Invoice Amount:</label>
            <input type="number" id="invoice_amount" name="invoice_amount" value="<?php echo htmlspecialchars($bill['invoice_amount']); ?>" required><br>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="pending" <?php echo $bill['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option><readonly></readonly>;
            </select><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($bill['description']); ?></textarea><br>

            <label for="bill_upload">Upload Bill Document:</label>
            <input type="file" id="bill_upload" name="bill_upload"><br>

            <button type="submit" name="update">Update Bill</button>
        </form>
        <a href="report.php">Back to Bills</a>s
    </div>
</body>
</html>
