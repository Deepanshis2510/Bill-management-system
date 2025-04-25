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
    /* Your existing styles */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #eef2f3;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 30px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .container:hover {
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
    }

    h1 {
        text-align: center;
        color: white;
        margin-bottom: 20px;
        font-size: 28px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 15px;
        text-align: left;
    }

    th {
        background-color: #2c3e50;;
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #d1e7dd;
    }

    a {
        text-decoration: none;
        color: #3498db;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }

    .no-cheques {
        text-align: center;
        color: #888;
        font-style: italic;
        padding: 20px;
    }

    @media (max-width: 768px) {
        .container {
            width: 95%;
        }

        h1 {
            font-size: 24px;
        }
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
        margin-left: 80%;
    }

    .back-button:hover {
        background-color: #2c3e50;;
    }

    .review-button {
        display: inline-block;
        padding: 10px 15px;
        background-color: #28a745;
        color: white;
        text-align: center;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .review-button:hover {
        background-color: #218838;
    }

    .review-form {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .close-btn {
        color: red;
        float: right;
        cursor: pointer;
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
                    <!-- <th>ID</th> -->
                    <th>Date</th>
                    <th>Cheque Number</th>
                    <th>Cheque To</th>
                    <th>Drawn From Bank</th>
                    <th>Cheque Comment</th>
                    <th>Review</th>
                    <th>Cheque Image</th> <!-- Ensure Cheque Image column is correct -->
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cheques)): ?>
                    <tr>
                        <td colspan="8" class="no-cheques">No cheques found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cheques as $cheque): ?>
                        <tr>
                            <!-- <td><?php echo htmlspecialchars($cheque['id']); ?></td> -->
                            <td><?php echo htmlspecialchars($cheque['date']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['cheque_number']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['cheque_to']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['bank']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['comments']); ?></td>
                            <td><?php echo htmlspecialchars($cheque['cheque_comment']); ?></td> <!-- Cheque comment aligned properly -->
                            <td>
                                <?php if (!empty($cheque['file_path'])): ?>
                                    <a href="<?php echo htmlspecialchars($cheque['file_path']); ?>" target="_blank">
                                        View Cheque
                                    </a>
                                <?php else: ?>
                                    No image available
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="review-button" onclick="openReviewForm(<?php echo $cheque['id']; ?>)">Review</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Review Form Modal -->
    <div id="reviewForm" class="review-form">
        <span class="close-btn" onclick="closeReviewForm()">×</span>
        <h3>Review Cheque</h3>
        <form action="submit_review.php" method="POST">
            <input type="hidden" name="cheque_id" id="chequeId">
            <textarea name="review_comment" rows="5" placeholder="Enter your review..."></textarea>
            <br>
            <button type="submit" class="review-button">Submit Review</button>
        </form>
    </div>

    <script>
        function openReviewForm(chequeId) {
            document.getElementById('chequeId').value = chequeId;
            document.getElementById('reviewForm').style.display = 'block';
        }

        function closeReviewForm() {
            document.getElementById('reviewForm').style.display = 'none';
        }
    </script>
</body>
</html>
