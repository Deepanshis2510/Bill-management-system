<?php
// Include database connection
include '../config/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $role = $_POST['role'];

    try {
        if ($role === 'user') {
            // Insert into users table
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        } elseif ($role === 'admin' || $role === 'super_admin') {
            // Insert into admins table (for both admin and super_admin)
            $stmt = $pdo->prepare("INSERT INTO admins (username, email, password, role) VALUES (:username, :email, :password, :role)");
            $stmt->bindParam(':role', $role);
        }

        // Bind common parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Execute statement
        if ($stmt->execute()) {
            // Redirect back with success message
            header("Location: add_user_admin.php?success=1");
            exit(); // Stop further execution
        } else {
            echo "Error adding user.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
