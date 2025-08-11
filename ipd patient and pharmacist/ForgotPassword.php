<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['btnReset'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
 // Check if email exists
    $query = "SELECT * FROM pharmacist WHERE Email = '$email'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Generate a 6-digit reset code
        $resetCode = rand(100000, 999999);
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code'] = $resetCode;

        // Send reset code via Gmail
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
              $mail->Username   = 'ipdhealthhub@gmail.com';  // Your Gmail address
            $mail->Password   = 'oaqq uaeq xjll jqfs'; // Use App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;


            // Email settings
            $mail->setFrom('ipdhealthhub@gmail.com', 'IPD Health Hub'); // Use a proper sender email
            $mail->addAddress($email);
            $mail->Subject = 'Password Reset Code';
            $mail->Body    = "Your password reset code is: $resetCode";

            if ($mail->send()) {
                echo "<script>alert('A reset code has been sent to your email.'); window.location.href='verifyCode.php';</script>";
                exit();
            }
        } catch (Exception $e) {
            echo "<script>alert('Email could not be sent. Error: " . $mail->ErrorInfo . "');</script>";
        }
    } else {
        echo "<script>alert('Email not found!');</script>";
    }
}

mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="">

</head>
<style type="text/css">
    /* General Styling */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #32e0c4, #008b8b);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Forgot Password Container */
.container {
    background: white;
    padding: 40px;
    width: 400px;
    border-radius: 12px;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
    text-align: center;
}

/* Title */
.container h2 {
    font-size: 24px;
    font-weight: bold;
    color: #008b8b;
    margin-bottom: 15px;
}

/* Description */
.container p {
    font-size: 14px;
    color: #555;
    margin-bottom: 20px;
}

/* Form Styling */
form {
    text-align: center;
}

/* Input Fields */
input {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    margin-bottom: 15px;
    transition: border-color 0.3s ease-in-out;
    background: #f9f9f9;
}

input:focus {
    border-color: #32e0c4;
    box-shadow: 0 0 10px rgba(50, 224, 196, 0.3);
    background: white;
}

/* Buttons */
button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

/* Primary Button */
.btn-primary {
    background: #32e0c4;
    color: white;
}

.btn-primary:hover {
    background: #008b8b;
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(0, 139, 139, 0.4);
}

/* Back Link */
.back-link {
    display: block;
    margin-top: 15px;
    font-size: 14px;
    color: #008b8b;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 480px) {
    .container {
        width: 90%;
        padding: 30px;
    }
}

</style>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        <p>Enter your email to receive a password reset code.</p>

        <form action="forgotPassword.php" method="POST">
            <input type="email" name="email" required placeholder="Enter your email">
            <button type="submit" name="btnReset" class="btn-primary">Send Reset Code</button>
        </form>

        <a href="pharmacistSignup.php" class="back-link">Back to Login</a>
    </div>
</body>
</html>
