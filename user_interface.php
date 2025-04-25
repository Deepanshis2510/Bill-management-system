<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Fetching total counts from the database
include './config/db_connection.php'; // Include your database connection   

// Queries for bills count (approved, pending, rejected, forwarded)
$query_approved_monthly = "SELECT COUNT(*) AS total_approved 
                           FROM bills 
                           WHERE status = 'Final Approved' 
                           AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')";
$query_pending = "SELECT COUNT(*) AS total_pending FROM bills WHERE status = 'pending'";
$query_rejected = "SELECT COUNT(*) AS total_rejected FROM bills WHERE status = 'rejected'";
$query_forwarded = "SELECT COUNT(*) AS total_forwarded FROM bills WHERE status = 'Forward To Final Approved'";

// Prepare and execute the queries
$stmt_approved_monthly = $pdo->prepare($query_approved_monthly);
$stmt_approved_monthly->execute();
$total_approved_monthly = $stmt_approved_monthly->fetch(PDO::FETCH_ASSOC)['total_approved'];

$stmt_pending = $pdo->prepare($query_pending);  
$stmt_pending->execute();
$total_pending = $stmt_pending->fetch(PDO::FETCH_ASSOC)['total_pending'];

$stmt_rejected = $pdo->prepare($query_rejected);
$stmt_rejected->execute();
$total_rejected = $stmt_rejected->fetch(PDO::FETCH_ASSOC)['total_rejected'];

$stmt_forwarded = $pdo->prepare($query_forwarded);
$stmt_forwarded->execute();
$total_forwarded = $stmt_forwarded->fetch(PDO::FETCH_ASSOC)['total_forwarded'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Interface</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, sans-serif;
    }

    .container {
        display: flex;
    }

    .sidebar {
        width: 250px;
        background-color: #2c3e50;
        color: white;
        padding: 20px;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }

    .company-logo img {
        width: 60%;
        height: auto;
        margin-bottom: 10px;
    }

    .nav-links {
        list-style-type: none;
        font-size: 20px;
    }

    .nav-links li {
        margin: 15px 0;
    }

    .nav-links a {
        text-decoration: none;
        color: white;
        padding: 10px;
        display: block;
        transition: background 0.3s, transform 0.2s; /* Added transform transition */
    }

    .nav-links a:hover {
        background-color: #34495e;
        transform: scale(1.05); /* Zoom in effect on hover */
    }

    .content {
        margin-left: 250px;
        padding: 20px;
        flex-grow: 1;
    }

    .statistics {
        display: flex;
        flex-wrap: wrap; /* Allow wrapping of boxes */
        gap: 20px;
        margin-bottom: 20px;
        margin-top: 3%;
    }

    .stat-box {
        flex: 1 1 calc(50% - 20px); /* Two boxes per row */
        padding: 20px;
        background-color: #ecf0f1;
        border-radius: 10px;
        text-align: center;
        transition: transform 0.2s;
    }

    .stat-box:hover {
        transform: scale(1.05);
    }

    .stat-box h2 {
        margin-bottom: 10px;
        font-size: 30px;
        color: #2c3e50;
    }

    .stat-box p {
        font-size: 30px;
        color: #7f8c8d;
        padding: 13px;
    }

    .chart-container {
        display: flex;
        justify-content: center; /* Center-aligns the charts */
        margin-top: 20px;
        width: 100%;
    }

    .chart {
        width: 60%; /* Increase the chart width to make it larger */
        max-width: 600px; /* Optional max width for better responsiveness */
        height: 500px; /* Increased height */
    }

    .user-info {
        position: absolute;
        top: 0;
        right: 20px;
        padding: 10px;
        border-radius: 5px;
        text-align: right;
        font-size: 18px;
    }

    .user-info p {
        margin: 5px 0;
        color: #2c3e50;
    }
    footer {
            text-align: center;
            margin: 25px 0;
            font-size: 16px;
            color: #666;
        }
        .logo-image {
    /* White background */
    padding: 6px;           /* Optional padding around the image */
    border-radius: 1px;      /* Optional rounded corners */
    display: block;          /* Ensures proper layout in block elements */
    margin: 0 auto;          /* Centers the image if needed */
}
</style>

</head>
<body>
<div class="container">
        <nav class="sidebar">
            <div class="company-logo">
            <img src="./image/logo.png" alt="Company Logo" class="logo-image">

            </div>
            <ul class="nav-links">

                <li><a href="#home">Home</a></li>
                <li><a href="entry_bill.php">Add Bills</a></li>
                <li><a href="entry_cheque.php">Add Cheque</a></li>
                <li><a href="master_data.php">Add Master Data</a></li>
                <li><a href="./api/user_master_data.php">Master Data Details</a></li>
                <li><a href="add_bank.php">Add Bank</a></li>
                <li><a href="department.php">Add Department</a></li>
                <li><a href="department_list.php">Check Department</a></li>
                <li><a href="./api/report.php">Report Bills</a></li>
                <li><a href="./api/rejected_bill_user.php">Rejected Bills</a></li>
                <li><a href="./api/report_cheque.php">Report Cheque</a></li>
                <li><a href="./api/logout.php">Logout</a></li>
            </ul>
        </nav>

        <main class="content">
            <h1>Welcome to the User Dashboard</h1>
            
            <!-- Display logged-in user's info -->
            <div class="user-info">
                <p><strong>Username:</strong> <?php echo $_SESSION['username']; ?></p>
                <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
            </div>

            <!-- Statistics Section -->
            <div class="statistics">
                <div class="stat-box">
                    <h2>Total Approved Bills (Monthly)</h2>
                    <p><?php echo $total_approved_monthly; ?></p>
                </div>
                <div class="stat-box">
                    <h2>Total Pending Bills</h2>
                    <p><?php echo $total_pending; ?></p>
                </div>
                <div class="stat-box">
                    <h2>Total Forward to Approval Bills</h2>
                    <p><?php echo $total_forwarded; ?></p>
                </div>
                <div class="stat-box">
                    <h2>Total Rejected Bills</h2>
                    <p><?php echo $total_rejected; ?></p>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart">
                    <canvas id="myBarChart"></canvas>
                </div>
            </div>
        </main>
    </div>
    <script>
        // Data for the charts
        const labels = ['Approved', 'Pending', 'Forwarded', 'Rejected'];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Bills Status',
                data: [<?php echo $total_approved_monthly; ?>, <?php echo $total_pending; ?>, <?php echo $total_forwarded; ?>, <?php echo $total_rejected; ?>],
                backgroundColor: [
                    'rgba(46, 204, 113, 0.7)', // Approved
                    'rgba(241, 196, 15, 0.7)', // Pending
                    'rgba(52, 152, 219, 0.7)', // Forwarded
                    'rgba(231, 76, 60, 0.7)'   // Rejected
                ],
                borderColor: [
                    'rgba(46, 204, 113, 1)',   // Approved
                    'rgba(241, 196, 15, 1)',   // Pending
                    'rgba(52, 152, 219, 1)',    // Forwarded
                    'rgba(231, 76, 60, 1)'     // Rejected
                ],
                borderWidth: 1
            }]
        };

    

        const configBar = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Bills Status Count'
                    }
                }
            },
        };

        const myBarChart = new Chart(document.getElementById('myBarChart'), configBar);
    </script>
     <footer>&copy; <?php echo date("Y"); ?> Managed By WebClouds.in. All rights reserved.</footer>
</body>
</html>
