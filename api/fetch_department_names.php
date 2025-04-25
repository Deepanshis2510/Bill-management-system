<?php
// Include database connection
include '../config/db_connection.php'; // Make sure this path is correct

// SQL query to fetch department names
$sql = "SELECT id, department_name AS name FROM department"; // Replace 'department' with your actual table name

try {
    $stmt = $pdo->query($sql);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the departments array in JSON format
    echo json_encode($departments);
} catch (PDOException $e) {
    // Handle the error
    echo json_encode(['error' => $e->getMessage()]);
}
?>
