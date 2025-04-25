<?php
// Include database connection
include '../config/db_connection.php';
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get action type
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Get bill ID
    $billId = isset($_POST['bill_id']) ? $_POST['bill_id'] : null;

    if ($action === 'final_approve' && $billId) {
        try {
            // Prepare and execute the query to update the bill status to 'Final Approved'
            $sql = "UPDATE bills SET status = :status WHERE id = :bill_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['status' => 'Final Approved', 'bill_id' => $billId]);

            // Set a session message to confirm the action
            $_SESSION['alert_message'] = "Bill No. $billId has been successfully approved.";
        } catch (PDOException $e) {
            // Handle error (you can log it and show a user-friendly message)
            $_SESSION['alert_message'] = "Error approving bill: " . $e->getMessage();
        }

    } elseif (isset($_POST['comment']) && $billId) { // Ensure the rejection comment is available
        // Get rejection comment
        $comment = $_POST['comment'];

        try {
            // Prepare and execute the query to update the bill status to 'Rejected' with the comment
            $sql = "UPDATE bills SET status = :status, superadmin_comment = :comment WHERE id = :bill_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['status' => 'Rejected', 'comment' => $comment, 'bill_id' => $billId]);

            // Set a session message to confirm the action
            $_SESSION['alert_message'] = "Bill No. $billId has been rejected with comment: '$comment'.";
        } catch (PDOException $e) {
            // Handle error (you can log it and show a user-friendly message)
            $_SESSION['alert_message'] = "Error rejecting bill: " . $e->getMessage();
        }
    }

    // Redirect back to the admin bill review portal
    header("Location: final_report_super.php");
    exit();
} else {
    // Handle the case where the request method is not POST
    $_SESSION['alert_message'] = "Invalid request.";
    header("Location: final_report_review.php");
    exit();
}
