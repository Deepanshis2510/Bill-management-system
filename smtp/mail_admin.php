<?php
session_start();
include '../config/db_connection.php'; // Include the DB connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Use Composer autoloader instead of PHPMAILERAutoload.php

$mail = new PHPMailer(true);

try {
    // Step 1: Get booking details from the POST request
    if (isset($_POST['booking_id'], $_POST['username'], $_POST['email'])) {
        $bookingId = $_POST['booking_id'];
        $username = $_POST['username'];
        $recipientEmail = $_POST['email'];
        $meetingRoom = $_POST['meeting_room'];
        $date = $_POST['date'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];

        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;
        $mail->Username   = 'meeting@myoffiz.in';  // Your email
        $mail->Password   = 'pnwz xakc yzam fawl';        // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('meeting@myoffiz.in', 'Webclouds');
        $mail->addCC('community@myoffiz.in');
        $mail->addAddress($recipientEmail, $username);  // Send to user email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation - Meeting Room';
        $mail->Body    = "
            <h1>Booking Confirmation</h1>
            <p>Dear $username,</p>
            <p>Thank you for booking the meeting room. Here are the details:</p>
            <ul>
                <li><strong>Meeting Room:</strong> $meetingRoom</li>
                <li><strong>Date:</strong> $date</li>
                <li><strong>Start Time:</strong> $startTime</li>
                <li><strong>End Time:</strong> $endTime</li>
            </ul>
            <p>We look forward to seeing you!</p>
        ";
        $mail->AltBody = "Booking Confirmation: Meeting Room: $meetingRoom, Date: $date, Time: $startTime - $endTime";

        // Send the email
        $mail->send();
        
        // JavaScript for popup and redirection after clicking OK
        echo '<script type="text/javascript">
            alert("Confirmation email has been sent successfully.");
            window.location.href = "admin_page.php"; // Redirect to user dashboard
        </script>';
        
    } else {
        echo '<script type="text/javascript">
            alert("Booking details not provided.");
            window.location.href = "admin_page.php"; // Redirect to user dashboard even on failure
        </script>';
    }
} catch (Exception $e) {
    echo '<script type="text/javascript">
        alert("Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '");
        window.location.href = "../index/admin_page.php"; // Redirect to user dashboard even on error
    </script>';
}
?>
