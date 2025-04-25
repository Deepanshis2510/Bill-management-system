<?php
// Include your database connection file
require_once '../config/db_connection.php'; // Adjust the path if necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $party_name = $_POST['party_name'];
    $party_email= $_POST['party_email'];
    $party_phn = $_POST['party_phn'];
    $party_type = $_POST['party_type'];
    $gst_no = isset($_POST['gst_no']) ? $_POST['gst_no'] : null;
    $pan_no = $_POST['pan_no'];

    // File upload logic
    $uploads_dir = '../uploads';
    if (!file_exists($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }

    // Initialize certificate paths
    $certificate_1_path = null;
    $certificate_2_path = null;
    $certificate_3_path = null;
    $certificate_4_path = null;
    $certificate_5_path = null;
    $certificate_6_path = null;

    // Handle file uploads for certificate 1 to 6
    if (isset($_FILES['certificate_1']) && $_FILES['certificate_1']['error'] === UPLOAD_ERR_OK) {
        $certificate_1_path = $uploads_dir . '/' . basename($_FILES['certificate_1']['name']);
        move_uploaded_file($_FILES['certificate_1']['tmp_name'], $certificate_1_path);
    }

    if (isset($_FILES['certificate_2']) && $_FILES['certificate_2']['error'] === UPLOAD_ERR_OK) {
        $certificate_2_path = $uploads_dir . '/' . basename($_FILES['certificate_2']['name']);
        move_uploaded_file($_FILES['certificate_2']['tmp_name'], $certificate_2_path);
    }

    if (isset($_FILES['certificate_3']) && $_FILES['certificate_3']['error'] === UPLOAD_ERR_OK) {
        $certificate_3_path = $uploads_dir . '/' . basename($_FILES['certificate_3']['name']);
        move_uploaded_file($_FILES['certificate_3']['tmp_name'], $certificate_3_path);
    }

    if (isset($_FILES['certificate_4']) && $_FILES['certificate_4']['error'] === UPLOAD_ERR_OK) {
        $certificate_4_path = $uploads_dir . '/' . basename($_FILES['certificate_4']['name']);
        move_uploaded_file($_FILES['certificate_4']['tmp_name'], $certificate_4_path);
    }

    if (isset($_FILES['certificate_5']) && $_FILES['certificate_5']['error'] === UPLOAD_ERR_OK) {
        $certificate_5_path = $uploads_dir . '/' . basename($_FILES['certificate_5']['name']);
        move_uploaded_file($_FILES['certificate_5']['tmp_name'], $certificate_5_path);
    }

    if (isset($_FILES['certificate_6']) && $_FILES['certificate_6']['error'] === UPLOAD_ERR_OK) {
        $certificate_6_path = $uploads_dir . '/' . basename($_FILES['certificate_6']['name']);
        move_uploaded_file($_FILES['certificate_6']['tmp_name'], $certificate_6_path);
    }

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("
            INSERT INTO master_data (party_name,party_email,party_phn, party_type, gst_no, pan_no, certificate_1, certificate_2, certificate_3, certificate_4, certificate_5, certificate_6)
            VALUES (:party_name, :party_email, :party_phn, :party_type, :gst_no, :pan_no, :certificate_1, :certificate_2, :certificate_3, :certificate_4, :certificate_5, :certificate_6)
        ");

        // Bind parameters
        $stmt->bindParam(':party_name', $party_name);
        $stmt->bindParam(':party_email', $party_email);
        $stmt->bindParam(':party_phn', $party_phn);
        $stmt->bindParam(':party_type', $party_type);
        $stmt->bindParam(':gst_no', $gst_no);
        $stmt->bindParam(':pan_no', $pan_no);
        $stmt->bindParam(':certificate_1', $certificate_1_path);
        $stmt->bindParam(':certificate_2', $certificate_2_path);
        $stmt->bindParam(':certificate_3', $certificate_3_path);
        $stmt->bindParam(':certificate_4', $certificate_4_path);
        $stmt->bindParam(':certificate_5', $certificate_5_path);
        $stmt->bindParam(':certificate_6', $certificate_6_path);

        // Execute the statement
        $stmt->execute();

        // Output success message as a JavaScript alert
        echo "<script>alert('Data added successfully!'); window.location.href = '../user_interface.php';</script>";
    } catch (PDOException $e) {
        // Output error message as a JavaScript alert
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = '../user_interface.php';</script>";
    }
} else {
    echo "Invalid request method.";
}
?>
