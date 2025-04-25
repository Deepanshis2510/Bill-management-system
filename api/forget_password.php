<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <style>
        /* Add styling similar to your login page */
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
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        input[type="email"], input[type="submit"] {
            width: 90%;
            padding: 15px;
            margin: 10px 0;
            border-radius: 50px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forget Password</h2>
        <form action="../smtp/sen_otp.php" method="post">
            <label for="email">Enter your registered email:</label>
            <input type="email" name="email" id="email" required>
            <input type="submit" value="Send OTP">
        </form>
    </div>
</body>
</html>
