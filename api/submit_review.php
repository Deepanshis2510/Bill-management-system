<?php
// submit_review.php
include '../config/db_connection.php';
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get cheque ID and review comment
    $chequeId = isset($_POST['cheque_id']) ? $_POST['cheque_id'] : null;
    $reviewComment = isset($_POST['review_comment']) ? $_POST['review_comment'] : '';

    if ($chequeId && !empty($reviewComment)) {
        try {
            // Update the cheque with the review comment
            $sql = "UPDATE cheques SET cheque_comment = :review_comment WHERE id = :cheque_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['review_comment' => $reviewComment, 'cheque_id' => $chequeId]);

            $_SESSION['success_message'] = 'Review added successfully!';
            header('Location: report_cheque_admin.php');
            exit();
        } catch (Exception $e) {
            error_log('Error while adding review: ' . $e->getMessage());
            $_SESSION['error_message'] = 'Failed to add review!';
            header('Location: report_cheque_admin.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Invalid input. Please provide a review.';
        header('Location: report_cheque_admin.php');
        exit();
    }
}
?>
