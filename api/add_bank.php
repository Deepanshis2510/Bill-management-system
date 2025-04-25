<?php
// backend/add_bank.php
require_once '../config/db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the bank name from the form
    $bank_name = $_POST['bank_name'];

    // Check if the bank name is not empty
    if (!empty($bank_name)) {
        try {
            // Prepare the SQL statement to insert the bank name into the database
            $stmt = $pdo->prepare("INSERT INTO banks (bank_name) VALUES (:bank_name)");

            // Bind parameters
            $stmt->bindParam(':bank_name', $bank_name);

            // Execute the statement
            $stmt->execute();

            // Redirect with a success message
            echo "<script>alert('Bank added successfully!'); window.location.href = '../add_bank.php';</script>";
        } catch (PDOException $e) {
            // Catch any error and display it
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = '../add_bank.php';</script>";
        }
    } else {
        // Display an error if the bank name is empty
        echo "<script>alert('Bank name cannot be empty!'); window.location.href = '../add_bank.php';</script>";
    }
} else {
    echo "Invalid request method.";
}
?>
