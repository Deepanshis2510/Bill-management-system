<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Fetching total counts from the database
include './config/db_connection.php'; // Include your database connection

// Query to get the total number of bills for the current month
$query_approved_monthly = "SELECT COUNT(*) AS total_approved 
                           FROM bills 
                           WHERE status = 'Final Approved' 
                           AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')";

// Query to get the total number of pending bills
$query_pending = "SELECT COUNT(*) AS total_pending FROM bills WHERE status = 'forward to final approved'";

// Query to get the total number of rejected bills
$query_rejected = "SELECT COUNT(*) AS total_rejected FROM bills WHERE status = 'rejected'";

// Prepare and execute the monthly approved bills query
$stmt_approved_monthly = $pdo->prepare($query_approved_monthly);
$stmt_approved_monthly->execute();
$total_approved_monthly = $stmt_approved_monthly->fetch(PDO::FETCH_ASSOC)['total_approved'];

// Prepare and execute the pending bills query
$stmt_pending = $pdo->prepare($query_pending);
$stmt_pending->execute();
$total_pending = $stmt_pending->fetch(PDO::FETCH_ASSOC)['total_pending'];

// Prepare and execute the rejected bills query
$stmt_rejected = $pdo->prepare($query_rejected);
$stmt_rejected->execute();
$total_rejected = $stmt_rejected->fetch(PDO::FETCH_ASSOC)['total_rejected'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Interface</title>
    <link rel="stylesheet" href="styles.css">
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
    gap: 10px;
    margin-bottom: 20px;
    margin-top: 3%;
    justify-content: space-around; /* Space them out evenly */
}

.stat-box {
    flex: 1 1 30%; /* Set each box to take 30% of the row */
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
        gap: 20px;
        margin-top: 20px;
        width: 60%;
        margin-left:20%;
    }

    .chart {
        width: 60%; /* Increase the chart width to make it larger */
        max-width: 200px; /* Optional max width for better responsiveness */
        height: 200px; /* Increased height */
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
    padding: 10px;           /* Optional padding around the image */
    border-radius: 5px;      /* Optional rounded corners */
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
                <li><a href="./api/final_report_super.php">Pending Report</a></li>
                <li><a href="./api/super_report_cheque.php">Report Cheque</a></li>
                <li><a href="./api/super_report_bill.php">Report Bill</a></li>
                 <li><a href="entry_cheque_super.php">Add Cheque</a></li>
                <li><a href="./api/logout.php">Logout</a></li>
            </ul>
        </nav>

        <main class="content">
            <h1>Welcome to the SuperAdmin Dashboard</h1>

            <!-- Username and Email Display -->
            <div class="user-info">
                <p>Username: <?php echo $_SESSION['username']; ?></p>
                <p>Email: <?php echo $_SESSION['email']; ?></p>
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
                    <h2>Total Rejected Bills</h2>
                    <p><?php echo $total_rejected; ?></p>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="chart-section">
                <div class="chart-box chart-container">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </main>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie Chart
    

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Approved', 'Pending', 'Rejected'],
                datasets: [{
                    label: 'Bills',
                    data: [<?php echo $total_approved_monthly; ?>, <?php echo $total_pending; ?>, <?php echo $total_rejected; ?>],
                    backgroundColor: ['#27ae60', '#f39c12', '#e74c3c'],
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
     <footer>&copy; <?php echo date("Y"); ?> Managed By WebClouds.in. All rights reserved.</footer>
</body>
</html>
