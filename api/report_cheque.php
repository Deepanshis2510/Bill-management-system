<?php
// view_cheques.php
session_start(); // Ensure session is started
include '../config/db_connection.php'; // Include your database connection file

// Fetch all cheques from the cheques table
$query = "SELECT * FROM cheques"; // Query to fetch all cheques data
$stmt = $pdo->prepare($query);
$stmt->execute();

// Check if there are any cheques
$cheques = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debugging: Output the number of cheques fetched
error_log("Number of cheques fetched: " . count($cheques));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cheques</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #eef2f3; /* Light background color for the body */
        color: #333; /* Default text color */
        margin: 0; /* Remove default margin */
        padding: 0; /* Remove default padding */
    }

    .container {
        width: 80%;
        margin: 30px auto; /* Center container with some margin on top */
        padding: 20px;
        background-color: #ffffff; /* White background for content */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        transition: all 0.3s; /* Smooth transition for hover effects */
    }

    .container:hover {
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2); /* Darker shadow on hover */
    }

    h1 {
        text-align: center;
        color: #2c3e50; /* Darker text for headings */
        margin-bottom: 20px;
        font-size: 28px; /* Larger font size */
    }

    table {
        width: 100%;
        border-collapse: collapse; /* Collapse borders */
        margin-bottom: 20px;
    }

    table, th, td {
        border: 1px solid #ddd; /* Light border */
    }

    th, td {
        padding: 15px; /* Increased padding */
        text-align: left;
    }

    th {
        background-color:  #2c3e50; /* Blue background for headers */
        color: #ffffff; /* White text for headers */
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2; /* Light grey for even rows */
    }

    tr:hover {
        background-color: #d1e7dd; /* Light green background on hover */
    }

    a {
        text-decoration: none;
        color: #3498db; /* Link color */
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline; /* Underline on hover */
    }

    .no-cheques {
        text-align: center;
        color: #888;
        font-style: italic;
        padding: 20px; /* Padding for the no cheques message */
    }

    @media (max-width: 768px) {
        .container {
            width: 95%; /* Full width on smaller screens */
        }

        h1 {
            font-size: 24px; /* Adjust heading size for mobile */
        }
    }
    .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color:  #2c3e50;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 88%;
        }
        .back-button:hover {
            background-color:  #2c3e50;
        }
</style>

</head>
<body>
    <div class="container">
    <div class="back-button-container">
            <a href="javascript:history.back()" class="back-button">← Back</a>
        </div>
        <h1>Cheques Details</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Cheque Number</th>
                    <th>Cheque To</th>
                    <th>Drawn From Bank</th>
                    <th>Details</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cheques)): ?>
                    <tr>
                        <td colspan="7" class="no-cheques">No cheques found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cheques as $cheque): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cheque['id']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['date']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['cheque_number']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['cheque_to']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['bank']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['comments']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['cheque_comment']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
