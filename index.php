<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #2c3e50, white);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            max-width: 500px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            backdrop-filter: blur(10px);
        }
        h2 {
            color:#2c3e50;
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .welcome-message {
            font-size: 20px;
            color: #333;
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 15px 20px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 50px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            font-size: 16px;
            transition: all 0.3s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #007bff;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }
        input[type="submit"] {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 15px 20px;
            width: 100%;
            border-radius: 50px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }
        input[type="submit"]:hover {
            background-color:#2c3e50;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .input-icon {
            position: relative;
            margin-bottom: 25px;
        }
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        input[type="text"], input[type="email"], input[type="password"] {
            padding-left: 45px;
        }
        .input-icon i {
            font-size: 18px;
        }
        .container {
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
              opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .logo-image {
            padding: 10px;
            border-radius: 5px;
            display: block;
            margin: 0 auto 20px;
            width: 100px;
        }
        /* Popup styles */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .popup h3 {
            margin-bottom: 20px;
        }
        .popup button {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
        }
        .popup button:hover {
            background-color: #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #333;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container">
    <img src="./image/logo.png" alt="Company Logo" class="logo-image">
    <h2>Login</h2>
    <p class="welcome-message"><b>Welcome to Bill Management System</b></p>

    <form action="./smtp/send_otp.php" method="post">
        <div class="input-icon">
            <i class="fas fa-user"></i>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div class="input-icon">
            <i class="fas fa-envelope"></i>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="input-icon">
            <i class="fas fa-lock"></i>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        
        <input type="submit" value="Login">
    </form>

    <!-- Footer Links -->
    <div class="footer">
        <a href="javascript:void(0);" onclick="openPopup('terms')">Terms and Conditions</a> |
        <a href="javascript:void(0);" onclick="openPopup('contact')">Contact Us</a> |
        <a href="./api/forget_password.php">Forget Password?</a>
    </div>

</div>

<!-- Popup for Terms and Conditions -->
<div class="popup" id="terms-popup">
    <div class="popup-content">
    <p>By using the [Billing Management System], you agree to provide accurate account details and maintain the security of your login credentials. You are responsible for all activities under your account and must comply with all applicable laws. Payment for services must be made promptly as per generated invoices. We do not guarantee uninterrupted service and may temporarily suspend access for maintenance. We reserve the right to modify these terms at any time, and continued use of the system constitutes acceptance of the changes. For any inquiries, contact us at webcloudsinfo@gmail.com.</p>
        <button onclick="closePopup()">Exit</button>
    </div>
</div>

<!-- Popup for Contact Us -->
<div class="popup" id="contact-popup">
    <div class="popup-content">
        <h3>Contact Us</h3>
        <p>Email: webcloudsinfo@gmail.com</p>
        <button onclick="closePopup()">Exit</button>
    </div>
</div>

<script>
    function openPopup(type) {
        document.getElementById(type + '-popup').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('terms-popup').style.display = 'none';
        document.getElementById('contact-popup').style.display = 'none';
    }
</script>

<!-- Managed by WebClouds -->
<div class="footer" style="position: absolute; bottom: 10px; width: 100%; font-size: 12px;">
    <p>Managed by WebClouds</p>
</div>

</body>
</html>