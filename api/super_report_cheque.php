<?php
session_start(); 
include '../config/db_connection.php'; 

// Initialize filter variables with empty values
$filter_date = isset($_POST['filter_date']) ? $_POST['filter_date'] : '';
$filter_cheque_number = isset($_POST['filter_cheque_number']) ? $_POST['filter_cheque_number'] : '';

// Build the query with filters
$query = "SELECT * FROM cheques WHERE 1=1";
$params = [];

if (!empty($filter_date)) {
    $query .= " AND date = :filter_date";
    $params[':filter_date'] = $filter_date;
}

if (!empty($filter_cheque_number)) {
    $query .= " AND cheque_number LIKE :filter_cheque_number";
    $params[':filter_cheque_number'] = '%' . $filter_cheque_number . '%';
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$cheques = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If download button is clicked, generate CSV file
if (isset($_POST['download_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="cheques_report.csv"');
    $output = fopen('php://output', 'w');
    
    // Add column headings
    fputcsv($output, ['ID', 'Date', 'Cheque Number', 'Cheque To', 'Drawn From Bank', 'Details', 'Review']);
    
    // Output each row of cheques data
    foreach ($cheques as $cheque) {
        fputcsv($output, $cheque);
    }
    
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cheques</title>
    <link rel="stylesheet" href="styles.css">
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
        background-color: #2c3e50; /* Blue background for headers */
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

    /* Styles for the filter form */
    .filter-form {
        margin-bottom: 20px; /* Spacing below the form */
        display: flex; /* Flex layout for horizontal alignment */
        flex-wrap: wrap; /* Wrap items on smaller screens */
        gap: 15px; /* Spacing between elements */
        justify-content: center; /* Center the filter form */
    }

    .filter-form div {
        flex: 1; /* Allow flex items to grow */
        min-width: 200px; /* Minimum width for each filter */
    }

    .filter-form label {
        display: block; /* Labels on their own line */
        margin-bottom: 5px; /* Space below label */
    }

    .filter-form input[type="date"],
    .filter-form input[type="text"],
    .filter-form button {
        padding: 10px; /* Padding for inputs and buttons */
        border: 1px solid #ccc; /* Light border */
        border-radius: 4px; /* Rounded corners */
        width: 100%; /* Full width for inputs */
        box-sizing: border-box; /* Include padding in width calculation */
    }

    .filter-form button {
        background-color:  #2c3e50; /* Button background color */
        color: white; /* Button text color */
        cursor: pointer; /* Pointer on hover */
        transition: background-color 0.3s ease; /* Smooth transition */
    }

    .filter-form button:hover {
        background-color:  #2c3e50; /* Darker on hover */
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-button-container">
            <a href="../superadmin_portal.php" class="back-button">← Back</a>
        </div>
        <h1>Cheques Details</h1>

        <!-- Filter Form -->
        <form method="POST" action="super_report_cheque.php" class="filter-form">
            <div>
                <label for="filter_date">Filter by Date:</label>
                <input type="date" id="filter_date" name="filter_date" value="<?php echo htmlspecialchars($filter_date); ?>">
            </div>
            <div>
                <label for="filter_cheque_number">Filter by Cheque Number:</label>
                <input type="text" id="filter_cheque_number" name="filter_cheque_number" value="<?php echo htmlspecialchars($filter_cheque_number); ?>" placeholder="Enter cheque number">
            </div>
            <div>
                <button type="submit">Filter</button>
                <button type="submit" name="download_csv">Download CSV</button>
            </div>
        </form>

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
