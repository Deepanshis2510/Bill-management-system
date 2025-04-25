<?php
// Include the database connection file
include '../config/db_connection.php'; // Ensure the path is correct

// Initialize session
session_start();

// Initialize the $error variable to avoid warnings
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        try {
            // Prepare SQL statement to fetch user
            $sql = "SELECT * FROM users WHERE username = :username AND email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Fetch the user
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password if user exists
            if ($user && password_verify($password, $user['password'])) {
                // Store user data in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Role-based redirection
                if ($user['role'] == 'admin') {
                    // Redirect to admin portal
                    header("Location: admin_portal.php");
                } elseif ($user['role'] == 'super_admin') {
                    // Redirect to super admin portal
                    header("Location: superadmin_portal.php");
                } else {
                    // Redirect to user interface (for regular users)
                    header("Location: ../user_interface.php");
                }
                exit();
            } else {
                $error = "Invalid username, email, or password.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
