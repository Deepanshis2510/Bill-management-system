<?php
// Include database connection
include '../config/db_connection.php';
session_start(); // Start the session

// Fetch pending bills using PDO with a JOIN to get the party name from master_data
$sql = "SELECT bills.*, master_data.party_name 
        FROM bills 
        LEFT JOIN master_data ON bills.party_id = master_data.id 
        WHERE bills.status = 'Pending'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pendingBills = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bill Review Portal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: white;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color:  #2c3e50;;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            transition: background-color 0.3s;
        }

        th {
            background-color: #2c3e50;;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #e1f5fe;
        }

        .uploaded-bill {
            margin-top: 10px;
            font-size: 0.9em;
            color: #2980b9;
        }

        button {
    color: white;
    border: none;
    padding: 4px 15px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.forward-button {
    background-color: #2ecc71; /* Green for forward */
}

.reject-button {
    background-color: #e74c3c; /* Red for reject */
}


.view-button {
    background-color: #3498db; /* Blue for view details */
}

        button:hover {
            filter: brightness(85%);
        }

        .action-buttons {
    display: flex;
    gap: 10px; /* Controls space between buttons */
    justify-content: flex-start; /* Align buttons to the left */
    align-items: center; /* Vertically align buttons */
}

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color:  #2c3e50;;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 85%;
        }
        .back-button:hover {
            background-color:  #2c3e50;;
        }
    </style>
</head>
<body>
    <h1>Pending Bills for Review</h1>
    <div class="back-button-container">
            <a href="../admin_interface.php" class="back-button">← Back</a>
        </div>
    
    <?php if (count($pendingBills) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Bill No</th>
                    <th>Party Name</th>
                    <th>Un-Registered Party Name</th>
                    <th>GST No</th>
                    <th>PAN NO</th>
                    <th>Bill Date</th>
                    <th>Taxable Amount</th>
                    <th>Tax Amount</th>
                    <th>Invoice Amount</th>
                    <th>View Bill</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingBills as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['bill_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['party_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['manual_party_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['gst_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['pan_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['bill_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['taxable_amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['tax_amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['invoice_amount']); ?></td>
                    <td class="uploaded-bill">
                        <a href="<?php echo htmlspecialchars($row['bill_upload']); ?>" target="_blank">View Document</a>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="view-button" onclick="viewBillDetails(<?php echo $row['id']; ?>)">View Details</button>
                            <form method="post" action="process_bill.php" style="display:inline;">
                                <input type="hidden" name="bill_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="forward to approval" class="forward-button">Forward For Approval</button>
                                <button type="button" onclick="rejectBill(<?php echo $row['id']; ?>)" class="reject-button">Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php if (!empty($row['bill_upload'])): ?>
                <tr>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending bills found.</p>
    <?php endif; ?>

    <script>
        // View bill details function
        function viewBillDetails(billId) {
            window.location.href = 'admin_view_bill.php?id=' + billId;
        }

        // Reject bill function
        function rejectBill(billId) {
            var comment = prompt("Please enter your comment for rejection:");
            if (comment !== null) {
                // Create a form dynamically to submit the rejection with comment
                var form = document.createElement("form");
                form.method = "post";
                form.action = "process_bill.php";
                
                // Create input elements for bill_id and comment
                var billIdInput = document.createElement("input");
                billIdInput.type = "hidden";
                billIdInput.name = "bill_id";
                billIdInput.value = billId;

                var commentInput = document.createElement("input");
                commentInput.type = "hidden";
                commentInput.name = "comment"; // Adding comment
                commentInput.value = comment;

                // Append inputs to the form
                form.appendChild(billIdInput);
                form.appendChild(commentInput);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Function to show alert if there's an alert message in the session
        window.onload = function() {
            <?php if (isset($_SESSION['alert_message'])): ?>
                alert("<?php echo $_SESSION['alert_message']; ?>");
                <?php unset($_SESSION['alert_message']); // Clear the session variable ?>
            <?php endif; ?>
        }
    </script>
</body>
</html>
