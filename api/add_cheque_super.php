<?php
// Include the database connection file
include '../config/db_connection.php'; // Ensure the path is correct

// Full path to the uploads directory (relative path)
$target_dir = "./uploads/"; 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $cheque_number = $_POST['cheque_number'];
    $cheque_to = $_POST['cheque_to'];
    $date = $_POST['date'];
    $bank = $_POST['bank'];
    $comments = $_POST['comments'];

    // Handle file upload
    if (isset($_FILES["cheque_file"]) && $_FILES["cheque_file"]["error"] == 0) {
        $file_name = basename($_FILES["cheque_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type (only allow JPG, PNG, PDF)
        $allowed_types = array("jpg", "jpeg", "png", "pdf");
        if (in_array($file_type, $allowed_types)) {
            // Move file to the target directory
            if (move_uploaded_file($_FILES["cheque_file"]["tmp_name"], $target_file)) {
                try {
                    // Prepare SQL statement with an additional field for the file path
                    $sql = "INSERT INTO cheques (cheque_number, cheque_to, date, bank, comments, file_path) 
                            VALUES (:cheque_number, :cheque_to, :date, :bank, :comments, :file_path)";

                    // Prepare the statement
                    $stmt = $pdo->prepare($sql);

                    // Bind parameters
                    $stmt->bindParam(':cheque_number', $cheque_number);
                    $stmt->bindParam(':cheque_to', $cheque_to);
                    $stmt->bindParam(':date', $date);
                    $stmt->bindParam(':bank', $bank);
                    $stmt->bindParam(':comments', $comments);
                    $stmt->bindParam(':file_path', $target_file); // Save the file path in the database

                    // Execute the query
                    $stmt->execute();

                    // Success message
                    echo "<script>alert('Cheque and file uploaded successfully!'); window.location.href='../entry_cheque_super.php';</script>";
                } catch (PDOException $e) {
                    // Handle error
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and PDF files are allowed.";
        }
    } else {
        echo "Please upload a cheque file.";
    }
}

// Close connection (not strictly necessary with PDO)
$pdo = null;
?>
