<?php
// fetch_party_names.php
include '../config/db_connection.php'; // Include your PDO connection

try {
    // Prepare the SQL query using PDO
    $sql = "SELECT id, party_name AS name FROM master_data "; // Adjust the table name and fields as per your structure
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // Fetch all the results as an associative array
    $parties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the party names as JSON
    echo json_encode($parties);
} catch (PDOException $e) {
    // Handle any error that occurred during the query execution
    echo json_encode(['error' => $e->getMessage()]);
}
?>
