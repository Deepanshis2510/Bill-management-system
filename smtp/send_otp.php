<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../config/db_connection.php';

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
            // Prepare SQL statements to check both users and admins
            $userSql = "SELECT * FROM users WHERE username = :username AND email = :email";
            $adminSql = "SELECT * FROM admins WHERE username = :username AND email = :email";

            // Check users first
            $stmt = $pdo->prepare($userSql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Fetch the user
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // If no user found, check admins
            if (!$user) {
                $stmt = $pdo->prepare($adminSql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                // Fetch the admin
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            // Verify password if user or admin exists
            if ($user && password_verify($password, $user['password'])) {
                // Generate a random OTP
                $otp = rand(100000, 999999);

                // Store OTP in session and user's email
                $_SESSION['otp'] = $otp;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Set role if available, otherwise default to 'user'
                $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'user'; 
                
                $_SESSION['email'] = $email;

                // Send OTP to user's email
                if (sendOTPEmail($email, $otp)) {
                    // Redirect to OTP verification page
                    header("Location: ../api/otp_verification.php");
                    exit();
                } else {
                    $error = "Failed to send OTP. Please try again.";
                }
            } else {
                $error = "Invalid username, email, or password.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Function to send OTP using PHPMailer
function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer;

    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Update with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'meeting@myoffiz.in'; // Your email username
    $mail->Password = 'pnwz xakc yzam fawl'; // Your email password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Email content
    $mail->setFrom('meeting@myoffiz.in', 'Bill Management System');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Your OTP code is: <strong>$otp</strong>";

    // Send email and return success/failure
    return $mail->send();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<!-- Your form HTML here -->

<?php if ($error): ?>
    <script>
        alert("<?php echo $error; ?>");
        window.location.href = "../index.php";
    </script>
<?php endif; ?>

</body>
</html>
