<?php
// Include your database connection file
include('../config/db_connection.php');

// Fetch rejected bills using PDO and join with master_data to get party_name
$query = "SELECT m.party_name, b.manual_party_name, b.party_type, b.tax_amount, b.bill_no, b.taxable_amount, b.invoice_amount, b.bill_date AS date, b.rejection_comment, b.status 
          FROM bills b
          LEFT JOIN master_data m ON b.party_id = m.id
          WHERE b.status = 'Rejected'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rejectedBills = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to create CSV file for download
function arrayToCsvDownload($array, $filename = "bills.csv", $delimiter = ",") {
    $f = fopen('php://memory', 'w'); 
    fputcsv($f, array('Party Name', 'Manual Party Name', 'Party Type', 'Date', 'Bill No', 'Tax Amount', 'Taxable Amount', 'Invoice Amount', 'Rejection Comment', 'Status'), $delimiter);

    foreach ($array as $line) {
        $line['date'] = date("d/m/y", strtotime($line['date']));
        fputcsv($f, $line, $delimiter);
    }

    fseek($f, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment;filename="'.$filename.'";');
    fpassthru($f);
    exit;
}

// Handle CSV download request
if (isset($_POST['download_csv'])) {
    arrayToCsvDownload($rejectedBills, "rejected_bills.csv");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejected Bills</title>
    <style>
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
            background-color: #2c3e50;
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

        tr:hover {
            background-color: #bdc3c7;
            transition: background-color 0.2s ease-in-out;
        }

        tr:nth-child(even) td {
            background-color: #e8eff7;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #34495e;
        }

        .btn-back {
            margin-right: auto;
        }

        /* Action buttons container */
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Rejected Bills</h1>

        <!-- Action buttons (Back and CSV download) -->
        <div class="action-buttons">
            <button class="btn btn-back" onclick="window.history.back();">Back</button>
            <form method="post">
                <button type="submit" name="download_csv" class="btn">Download CSV</button>
            </form>
        </div>

        <!-- Rejected Bills Table -->
        <table>
            <thead>
                <tr>
                    <th>Party Name</th>
                    <th>(Un-reg) Party Name</th>
                    <th>Party Type</th>
                    <th>Date</th>
                    <th>Bill No</th>
                    <th>Tax Amount</th>
                    <th>Taxable Amount</th>
                    <th>Invoice Amount</th>
                    <th>Rejection Comment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rejectedBills)): ?>
                    <?php foreach ($rejectedBills as $bill): ?>
                        <tr>
                            <td><?php echo $bill['party_name']; ?></td>
                            <td><?php echo $bill['manual_party_name']; ?></td>
                            <td><?php echo $bill['party_type']; ?></td>
                            <td><?php echo date("d/m/y", strtotime($bill['date'])); ?></td>
                            <td><?php echo $bill['bill_no']; ?></td>
                            <td><?php echo $bill['tax_amount']; ?></td>
                            <td><?php echo $bill['taxable_amount']; ?></td>
                            <td><?php echo $bill['invoice_amount']; ?></td>
                            <td><?php echo $bill['rejection_comment']; ?></td>
                            <td><?php echo $bill['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="no-bills">No rejected bills found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
