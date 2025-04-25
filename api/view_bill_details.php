<?php
// view_bill_details.php
session_start(); // Ensure you start the session to access user data
include '../config/db_connection.php'; // Include your database connection file

// Check if the bill ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid bill ID.";
    exit;
}

$bill_id = $_GET['id'];

try {
    // Prepare and execute the SQL statement
    $query = "SELECT * FROM bills WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$bill_id]);

    // Fetch the bill details
    $bill = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$bill) {
        echo "Bill not found.";
        exit;
    }
} catch (PDOException $e) {
    // Handle SQL error
    die("Error fetching bill details: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
    /* Container Styling */
    .container {
        width: 90%;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        font-family: 'Roboto', sans-serif;
    }

    h1 {
        text-align: center;
        color: #2c3e50;
        font-size: 2.2em;
        margin-bottom: 20px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    /* Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 15px;
        text-align: left;
        font-size: 1em;
    }

    th {
        background-color: #2980b9;
        color: #ffffff;
        font-weight: bold;
        text-transform: uppercase;
        border-bottom: 3px solid #ddd;
    }

    td {
        border-bottom: 1px solid #ddd;
        background-color: #ecf0f1;
        color: #34495e;
        font-size: 0.95em;
    }

    /* Add subtle row hover effect */
    tr:hover {
        background-color: #bdc3c7;
        transition: background-color 0.2s ease-in-out;
    }

    /* Row striping */
    tr:nth-child(even) td {
        background-color: #e8eff7;
    }

    /* Responsive table */
    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        th {
            text-align: right;
        }

        tr {
            margin-bottom: 15px;
            border-bottom: 2px solid #ddd;
        }

        td {
            text-align: left;
            padding-left: 50%;
            position: relative;
            padding: 10px 0;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 45%;
            padding-left: 10px;
            font-weight: bold;
            text-align: left;
        }
    }

    /* Link styling */
    a {
        text-decoration: none;
        color: #2980b9;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
        color: #2ecc71;
    }

    /* No bills found message */
    .no-bills {
        text-align: center;
        color: #e74c3c;
        font-size: 1.2em;
        font-style: italic;
    }

    /* Table action buttons */
    .edit-btn {
        background-color: #e74c3c;
        color: #ffffff;
        padding: 10px 15px;
        border-radius: 4px;
        text-align: center;
        font-weight: bold;
        border: none;
        cursor: pointer;
        display: inline-block;
    }

    .edit-btn:hover {
        background-color: #2ecc71;
        color: #fff;
        transition: background-color 0.2s ease-in-out;
    }

    .back-btn {
        background-color: #2980b9;
        color: #ffffff;
        padding: 10px 15px;
        border-radius: 4px;
        text-align: center;
        font-weight: bold;
        border: none;
        cursor: pointer;
        display: inline-block;
    }

    .back-btn:hover {
        background-color: #2ecc71;
        color: #fff;
        transition: background-color 0.2s ease-in-out;
    }
</style>
    
</head>
<body>
    <div class="container">
        <h1>Bill Details</h1>
        <p><strong>Bill No:</strong> <?php echo htmlspecialchars($bill['bill_no']); ?></p>
        <p><strong>Party Name:</strong> <?php echo htmlspecialchars($bill['party_id']); ?></p>
        <p><strong>GST no:</strong> <?php echo htmlspecialchars($bill['gst_no']); ?></p>
        <p><strong>PAN no:</strong> <?php echo htmlspecialchars($bill['pan_no']); ?></p>
        <p><strong>Bill Date:</strong> <?php echo htmlspecialchars($bill['bill_date']); ?></p>
        <p><strong>Taxable Amount:</strong> <?php echo htmlspecialchars($bill['taxable_amount']); ?></p>
        <p><strong>Tax Amount:</strong> <?php echo htmlspecialchars($bill['tax_amount']); ?></p>
        <p><strong>Invoice Amount:</strong> <?php echo htmlspecialchars($bill['invoice_amount']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($bill['status']);?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($bill['description'])); ?></p>
        <p><strong>Data Entry By:</strong> <?php echo htmlspecialchars($bill['authorized_person']); ?></p>
        <?php if (!empty($bill['bill_upload'])): ?>
            <p><strong>Uploaded Bill:</strong> <a href="<?php echo htmlspecialchars($bill['bill_upload']); ?>" target="_blank">View Document</a></p>
        <?php endif; ?>
        <p><strong>Admin Comment:</strong> <?php echo htmlspecialchars($bill['rejection_comment']); ?></p>

        <!-- Show Edit button only if status is 'rejected' -->
        <?php if (strtolower(trim($bill['status'])) == 'rejected'): ?>
    <a href="edit_bill.php?id=<?php echo $bill_id; ?>" class="edit-btn">Edit Bill</a>
<?php endif; ?>

        
        <!-- Back button -->
        <a href="report.php" class="back-btn">Back to Bills</a>
    </div>
</body>
</html>
