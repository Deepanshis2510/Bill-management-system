<?php
// edit_bill.php (backend for form submission)
session_start();
include '../config/db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bill_id = $_GET['id'];

    // Fetch the current bill data
    try {
        $query = "SELECT * FROM bills WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$bill_id]);
        $existing_bill = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing_bill) {
            echo "Bill not found.";
            exit();
        }
    } catch (PDOException $e) {
        die("Error fetching bill details: " . $e->getMessage());
    }

    // Sanitize and retrieve form input values
    $taxable_amount = $_POST['taxable_amount'];
    $tax_amount = $_POST['tax_amount'];
    $invoice_amount = $_POST['invoice_amount'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    $authorized_person = $_POST['authorized_person'];

    // Handle file upload if a new document is uploaded
    $bill_upload = null;
    if (isset($_FILES['bill_upload']) && $_FILES['bill_upload']['error'] === 0) {
        $bill_upload = 'uploads/' . basename($_FILES['bill_upload']['name']);
        move_uploaded_file($_FILES['bill_upload']['tmp_name'], $bill_upload);
    } else {
        // Keep the existing file if no new file is uploaded
        $bill_upload = $existing_bill['bill_upload'];
    }

    try {
        // Build the SQL update query
        $query = "UPDATE bills SET taxable_amount = ?, tax_amount = ?, invoice_amount = ?, status = ?, description = ?, authorized_person = ?, bill_upload = ? WHERE id = ?";
        $params = [
            $taxable_amount ?: $existing_bill['taxable_amount'],
            $tax_amount ?: $existing_bill['tax_amount'],
            $invoice_amount ?: $existing_bill['invoice_amount'],
            $status ?: $existing_bill['status'],
            $description ?: $existing_bill['description'],
            $authorized_person ?: $existing_bill['authorized_person'],
            $bill_upload, // Always use the updated file path (new or existing)
            $bill_id
        ];

        // Prepare and execute the update query
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // Redirect back to the report page after successful update
        header('Location: report.php');
        exit();
    } catch (PDOException $e) {
        die("Error updating bill: " . $e->getMessage());
    }
} else {
    echo "Invalid request.";
    exit();
}
