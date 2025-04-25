<?php
require '../config/db_connection.php'; // Include your database connection
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    try {
        // Check if the email exists in either 'users' or 'admins' table
        $query = "SELECT 'user' AS role, id FROM users WHERE email = :email 
                  UNION 
                  SELECT role, id FROM admins WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $role = $result['role']; // 'user', 'admin', or 'super_admin'
            $id = $result['id']; // Fetch user/admin ID if needed later

            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            // Send OTP to the email using PHPMailer
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Update with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'meeting@myoffiz.in'; // Your email username
            $mail->Password = 'pnwz xakc yzam fawl'; // Your email password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('meeting@myoffiz.in', 'Bill Management System');
            $mail->addAddress($email);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body = "Your OTP for password reset is: $otp";

            if ($mail->send()) {
                header("Location: ../api/reset_password.php");
                exit;
            } else {
                echo "Failed to send OTP. Please try again.";
            }
        } else {
            echo "Email not found. Please try again.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>
