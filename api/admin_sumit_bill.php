<?php
require '../config/db_connection.php'; // Include the database connection file

$successMessage = ""; // Initialize a success message variable
$redirectUrl = "entry_bill.php"; // Set the URL to redirect to

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $party_type = $_POST['party_type'] ?? null;

    // Check whether the party is registered or non-registered
    if ($party_type == 'non-registered') {
        $manual_party_name = $_POST['manual_party_name'] ?? null; // Store manual party name
        $party_id = null; // No party_id for non-registered parties
        $manual_pan_no = $_POST['manual_pan_no'] ?? null; // Store manual PAN number for non-registered parties
    } else {
        $party_id = $_POST['party_name'] ?? null; // Store party_id if registered
        $manual_party_name = null; // No manual party name for registered parties
        $manual_pan_no = null; // No manual PAN number for registered parties
    }
    
    $gst_no = $_POST['gst_no'] ?? null;
    $pan_no = $_POST['pan_no'] ?? null; // You can keep this for registered parties
    $bill_type = $_POST['bill_type'] ?? null;
    $bill_date = $_POST['bill_date'] ?? null;
    $bill_no = $_POST['bill_no'] ?? null;
    $taxable_amount = $_POST['taxable_amount'] ?? null;
    $tax_amount = $_POST['tax_amount'] ?? null;
    $invoice_amount = $_POST['invoice_amount'] ?? null;
    $department_id = $_POST['department_id'] ?? null; // Ensure this matches the form input
    $description = $_POST['description'] ?? null;
    $authorized_person = $_POST['authorized_person'] ?? null;
    
    // Handle file upload
    $uploadDir = 'uploads/'; // Directory to save uploaded files
    $uploadFile = $uploadDir . basename($_FILES['bill_upload']['name']);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    // Check if file is a valid format
    if ($fileType != 'jpg' && $fileType != 'jpeg' && $fileType != 'pdf') {
        echo "Sorry, only JPG, JPEG & PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Your file was not uploaded.";
    } else {
        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['bill_upload']['tmp_name'], $uploadFile)) {
            // Prepare and bind
            $stmt = $pdo->prepare("
                INSERT INTO bills 
                (party_type, party_id, manual_party_name, gst_no, pan_no, manual_pan_no, bill_type, bill_date, bill_no, taxable_amount, tax_amount, invoice_amount, department_id, description, authorized_person, bill_upload) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            if ($stmt->execute([$party_type, $party_id, $manual_party_name, $gst_no, $pan_no, $manual_pan_no, $bill_type, $bill_date, $bill_no, $taxable_amount, $tax_amount, $invoice_amount, $department_id, $description, $authorized_person, $uploadFile])) {
                $successMessage = "New record created successfully"; // Set success message
            } else {
                echo "Error: " . implode(", ", $stmt->errorInfo()); // Output detailed error information
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Close connection
$pdo = null; // Close PDO connection

// Check if there's a success message to display    
if (!empty($successMessage)) {
    // Use JavaScript to show alert and redirect
    echo "<script type='text/javascript'>
            alert('$successMessage');
            window.location.href = 'admin_entry_bill.php'; // Redirect to entry_bill.php
        </script>";
}
?>
