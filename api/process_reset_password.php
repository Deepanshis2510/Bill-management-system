<?php
require '../config/db_connection.php'; // Include your database connection
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($_SESSION['otp'] == $otp) {
        $email = $_SESSION['email'];
        $role = $_SESSION['role']; // Assume you store the role in session during OTP generation

        try {
            // Determine table based on role
            $table = ($role === 'user') ? 'users' : 'admins';

            // Update password
            $query = "UPDATE $table SET password = :password WHERE email = :email";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':password', $new_password, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Password updated successfully
                echo "<script>
                    alert('Your password has been changed successfully!');
                    window.location.href = '../index.php'; // Redirect to login page or desired location
                </script>";
                session_destroy(); // Clear session after successful password reset
            } else {
                echo "<script>
                    alert('Error updating password. Please try again.');
                    window.history.back(); // Redirect back to the previous page
                </script>";
            }
        } catch (PDOException $e) {
            echo "<script>
                alert('Database error: " . $e->getMessage() . "');
                window.history.back(); // Redirect back to the previous page
            </script>";
        }
    } else {
        echo "<script>
            alert('Invalid OTP. Please try again.');
            window.history.back(); // Redirect back to the previous page
        </script>";
    }
}
?>
