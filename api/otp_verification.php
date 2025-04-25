<?php
// Initialize session
session_start();

// Initialize the $error variable
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'];

    // Check if the entered OTP matches the one in session
    if ($entered_otp == $_SESSION['otp']) {
        // Log the user in based on their role
        if ($_SESSION['role'] == 'admin') {
            // Redirect to admin portal
            header("Location: ../admin_interface.php");
        } elseif ($_SESSION['role'] == 'super_admin') {
            // Redirect to super admin portal
            header("Location: ../superadmin_portal.php");
        } else {
            // Redirect to user interface (for regular users)
            header("Location: ../user_interface.php");
        }
        exit();
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe); /* Gradient background */
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px 20px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 50px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            font-size: 16px;
            transition: all 0.3s;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            width: 100%;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>OTP Verification</h2>

    <form action="otp_verification.php" method="post">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <input type="submit" value="Verify OTP">
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>
    </form>
</div>

</body>
</html>
