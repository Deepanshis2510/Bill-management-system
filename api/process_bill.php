<?php
// Include database connection
include '../config/db_connection.php';
session_start(); // Start the session

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the action and bill ID from the POST request
    $action = $_POST['action'] ?? '';
    $billId = $_POST['bill_id'] ?? '';

    if ($action == 'forward to approval' && !empty($billId)) {
        // Prepare SQL to update the status to 'Forward to Final Approved'
        $sql = "UPDATE bills SET status = 'Forward to Final Approved' WHERE id = :bill_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':bill_id', $billId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Set session variable for alert
            $_SESSION['alert_message'] = "Bill forwarded for approval successfully.";
            header("Location: pending_bill.php"); // Redirect to the desired page
            exit;
        } else {
            // Error handling
            echo "Error updating record: " . $stmt->errorInfo()[2];
        }
    }

    // Handle rejection with comment
    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        // Prepare SQL to update the status and save the rejection comment
        $sql = "UPDATE bills SET status = 'Rejected', rejection_comment = :comment WHERE id = :bill_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':bill_id', $billId, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Success message for rejection
            header("Location: pending_bill.php?message=Bill rejected successfully."); // Change to your desired redirect page
            exit;
        } else {
            // Error handling
            echo "Error updating record: " . $stmt->errorInfo()[2];
        }
    }
}
?>
