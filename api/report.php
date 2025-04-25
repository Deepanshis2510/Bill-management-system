<?php
session_start(); // Start session to access user_id
include '../config/db_connection.php'; // Include database connection file

// Initialize filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Build the SQL query with filters
$sql = "SELECT bills.*, master_data.party_name 
        FROM bills 
        LEFT JOIN master_data ON bills.party_id = master_data.id
        WHERE 1=1";

// Apply filters based on user input
if (!empty($status_filter)) {
    $sql .= " AND bills.status = :status";
}
if (!empty($from_date)) {
    $sql .= " AND bills.bill_date >= :from_date";
}
if (!empty($to_date)) {
    $sql .= " AND bills.bill_date <= :to_date";
}

// Prepare and execute the query
$stmt = $pdo->prepare($sql);

if (!empty($status_filter)) {
    $stmt->bindParam(':status', $status_filter);
}
if (!empty($from_date)) {
    $stmt->bindParam(':from_date', $from_date);
}
if (!empty($to_date)) {
    $stmt->bindParam(':to_date', $to_date);
}

$stmt->execute();
$bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle CSV download
if (isset($_GET['download_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=bills.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Bill No',  'party type','Party Name', ' Un Party Name', 'GST No', 'PAN No',  'Un PAN No', 'Bill Type', 'Bill Date',
    'Taxable Amount', 'Tax Amount', 'Invoice Amount', 'Data Entry By',  'Review & Check By',  'Final Approved By', 'Status', 'Descrption']);
    
    foreach ($bills as $bill) {
        $formatted_date = date('d/m/Y', strtotime($bill['bill_date']));
        fputcsv($output, [
            $bill['id'], 
            $bill['bill_no'], 
            $bill['party_type'], 
            $bill['party_name'], 
            $bill['manual_party_name'],
            $bill['gst_no'], 
            $bill['pan_no'],
            $bill['manual_pan_no'],
            $bill['bill_type'],
            $bill['bill_date'],
            $bill['taxable_amount'], 
            $bill['tax_amount'], 
            $bill['invoice_amount'],
            $bill['authorized_person'],  
            $bill['review'], 
            $bill['final_approved_by'], 
            $bill['status'],
            $bill['description']
        ]);
    }
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bills</title>
    <link rel="stylesheet" href="styles.css">
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
            background-color: #2c3e50;;
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
        .view-btn {
            background-color: #2980b9;
            color: #ffffff;
            padding: 8px 12px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .view-btn:hover {
            background-color: #2ecc71;
            color: #fff;
            transition: background-color 0.2s ease-in-out;
        }
        .filter-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-container form {
            display: flex;
            align-items: center;
        }

        .filter-container input[type="date"], 
        .filter-container select {
            padding: 8px;
            margin-right: 10px;
            font-size: 1em;
        }

        .back-button {
            background-color:  #2c3e50;;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color:  #2c3e50;;
        }

        .download-btn {
            background-color:  #2c3e50;;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .download-btn:hover {
            background-color: #2c3e50;;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bills Details</h1>

        <!-- Filter Form -->
        <div class="filter-container">
            <form method="GET" action="report.php">
                <label for="status">Status:</label>
                <select name="status" id="status">
                    <option value="">All</option>
                    <option value="pending" <?= ($status_filter == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="rejected" <?= ($status_filter == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                    <option value="Final Approved" <?= ($status_filter == 'Final_approved') ? 'selected' : ''; ?>>Final Approved</option>
                </select>

                <label for="from_date">From Date:</label>
                <input type="date" name="from_date" id="from_date" value="<?= htmlspecialchars($from_date); ?>">

                <label for="to_date">To Date:</label>
                <input type="date" name="to_date" id="to_date" value="<?= htmlspecialchars($to_date); ?>">

                <button type="submit">Apply Filters</button>
            </form>

            <form method="GET" action="report.php">
                <input type="hidden" name="status" value="<?= htmlspecialchars($status_filter); ?>">
                <input type="hidden" name="from_date" value="<?= htmlspecialchars($from_date); ?>">
                <input type="hidden" name="to_date" value="<?= htmlspecialchars($to_date); ?>">
                <button type="submit" name="download_csv" class="download-btn">Download CSV</button>
            </form>

            <a href="../user_interface.php" class="back-button">Back</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bill No</th>
                    <th>Party Name</th>
                    <th>GST No</th>
                    <th>Bill Date</th>
                    <th>Taxable Amount</th>
                    <th>Tax Amount</th>
                    <th>Invoice Amount</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bills)): ?>
                    <tr>
                        <td colspan="10" class="no-bills">No bills found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bills as $bill): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bill['id']); ?></td>
                            <td><?php echo htmlspecialchars($bill['bill_no']); ?></td>
                            <td><?php echo htmlspecialchars($bill['party_name']); ?></td>
                            <td><?php echo htmlspecialchars($bill['gst_no']); ?></td>
                            <td><?php echo htmlspecialchars($bill['bill_date']); ?></td>
                            <td><?php echo htmlspecialchars($bill['taxable_amount']); ?></td>
                            <td><?php echo htmlspecialchars($bill['tax_amount']); ?></td>
                            <td><?php echo htmlspecialchars($bill['invoice_amount']); ?></td>
                            <td><?php echo htmlspecialchars($bill['status']); ?></td>
                            <td><a href="view_bill_details.php?id=<?php echo $bill['id']; ?>">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
