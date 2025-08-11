<?php
session_start(); // Start the session at the very top

// Debugging: Check if session variables are set
if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_code'])) {
    echo "<script>alert('Session expired or invalid access! Please request a new reset code.'); window.location.href='forgotPassword.php';</script>";
    exit();
}

if (isset($_POST['btnVerify'])) {
    $userCode = $_POST['reset_code'];

    // Check if the entered code matches the stored session code
    if ($userCode == $_SESSION['reset_code']) {
        $_SESSION['code_verified'] = true;
        header("Location: resetPassword.php");
        exit();
    } else {
        echo "<script>alert('Invalid reset code! Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Reset Code</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container for Verify Code Form */
        .container {
            background: white;
            padding: 20px;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        /* Title */
        .container h2 {
            font-size: 18px;
            font-weight: bold;
            color: #008b8b;
            margin-bottom: 10px;
        }

        /* Input Fields */
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Buttons */
        button {
            width: 100%;
            background: #008b8b;
            color: white;
            border: none;
            padding: 8px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        button:hover {
            background: #005f5f;
        }

        /* Back Link */
        .back-link {
            display: block;
            margin-top: 10px;
            font-size: 12px;
            color: #008b8b;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter Reset Code</h2>
        <form action="verifyCode.php" method="POST">
            <input type="text" name="reset_code" required placeholder="Enter reset code">
            <button type="submit" name="btnVerify">Verify</button>
        </form>
        <a href="forgotPassword.php" class="back-link">Back to Forgot Password</a>
    </div>
</body>
</html>
