<?php
// Include the database connection file
include '../config/db_connection.php'; // Ensure this path is correct

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the department name from the form
    $departmentName = trim($_POST['departmentName']);

    // Prepare the SQL statement to insert the department
    $stmt = $pdo->prepare("INSERT INTO department (department_name) VALUES (:departmentName)");
    
    // Bind the parameter
    $stmt->bindParam(':departmentName', $departmentName, PDO::PARAM_STR);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to the form with a success message
        header("Location: ../department.php?success=1");
        exit();
    } else {
        // Handle the error (you can also redirect with an error message if desired)
        echo "Error adding department: " . $stmt->errorInfo()[2]; // Use errorInfo for PDO
    }
}
?>
