<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
      body {
        font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #2c3e50, white);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
}

.container {
    max-width: 400px;
    width: 100%; /* Ensures responsiveness */
    background-color: rgba(255, 255, 255, 0.95); /* Slightly more opacity */
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* Larger shadow for better depth */
    text-align: center;
}

.container h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #2c3e50; /* Matches the button color */
}

label {
    display: block;
    font-weight: bold;
    margin: 10px 0 5px;
    color: #555; /* Subtle text color */
    text-align: left;
}

input[type="text"], input[type="password"] {
    width: 90%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 25px;
    border: 1px solid #ccc;
    font-size: 16px;
    outline: none;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

input[type="text"]:focus, input[type="password"]:focus {
    border-color: #2c3e50;
    box-shadow: 0 0 5px rgba(44, 62, 80, 0.5); /* Focus glow effect */
}

input[type="submit"] {
    width: 90%;
    padding: 12px;
    margin: 20px 0 0;
    border-radius: 25px;
    border: none;
    background-color: #2c3e50;
    color: white;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

input[type="submit"]:hover {
    background-color: #34495e; /* Slightly darker color */
    transform: scale(1.05); /* Subtle zoom effect */
}

input[type="submit"]:active {
    transform: scale(0.95); /* Click effect */
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="./process_reset_password.php" method="post">
            <label for="otp">Enter OTP:</label>
            <input type="text" name="otp" id="otp" required>

            <label for="password">Enter New Password:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Reset Password">
        </form>
    </div>
</body>
</html>
