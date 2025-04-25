<?php
session_start();
include '../config/db_connection.php'; // Include the DB connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Use Composer autoloader

$mail = new PHPMailer(true);

try {
    // Enable verbose debug output
    $mail->SMTPDebug = 2; // Set to 3 or 4 for more verbosity

    // Step 1: Get client name (username) from GET request
    if (isset($_GET['username'])) {
        $username = $_GET['username'];

        // Step 2: Fetch booking details from the database based on client name (username)
        $query = "SELECT users.email, bookings.meeting_room, bookings.date, bookings.start_time, bookings.end_time 
                  FROM bookings 
                  INNER JOIN users ON bookings.user_id = users.id
                  WHERE users.username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username); // Bind the client name (username)
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the booking details
            $row = $result->fetch_assoc();
            $recipientEmail = $row['email'];  // Fetch email from database
            $meetingRoom = $row['meeting_room'];
            $date = $row['date'];
            $startTime = $row['start_time'];
            $endTime = $row['end_time'];

            // Step 3: Send the cancellation email using PHPMailer
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'meeting@myoffiz.in';
            $mail->Password   = 'pnwz xakc yzam fawl';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('meeting@myoffiz.in', 'Webclouds');
            $mail->addCC('community@myoffiz.in');
            $mail->addAddress($recipientEmail, $username);  // Send to user email

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Booking Cancellation - Meeting Room';
            $mail->Body    = "
                <h1>Booking Cancellation</h1>
                <p>Dear $username,</p>
                <p>Your booking has been canceled. Here are the details:</p>
                <ul>
                    <li><strong>Meeting Room:</strong> $meetingRoom</li>
                    <li><strong>Date:</strong> $date</li>
                    <li><strong>Start Time:</strong> $startTime</li>
                    <li><strong>End Time:</strong> $endTime</li>
                </ul>
                <p>Thank you.</p>
            ";
            $mail->AltBody = "Your booking has been canceled: Meeting Room: $meetingRoom, Date: $date, Time: $startTime - $endTime";

            // Send the email
            if ($mail->send()) {
                echo json_encode(['success' => true, 'message' => 'Cancellation email has been sent successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to send cancellation email.']);
            }

        } else {
            echo json_encode(['success' => false, 'message' => 'Booking details not found for the provided client name.']);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Client name not provided.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
}
?>
