<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Ensure user has verified the reset code
if (!isset($_SESSION['code_verified']) || !isset($_SESSION['reset_email'])) {
    echo "<script>alert('Invalid access!'); window.location.href='forgotPassword.php';</script>";
    exit();
}

if (isset($_POST['btnChangePassword'])) {
    $newPassword = mysqli_real_escape_string($con, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($con, $_POST['confirm_password']);
    $email = $_SESSION['reset_email'];

    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Update password
        $updateQuery = "UPDATE pharmacist SET Password='$newPassword' WHERE Email='$email'";
        if (mysqli_query($con, $updateQuery)) {
            session_unset();
            session_destroy();
            echo "<script>alert('Password reset successfully! Please log in.'); window.location.href='pharmacistSignup.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating password.');</script>";
        }
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 280px;
        }

        h2 {
            font-size: 20px;
            color: #008b8b;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            background: #008b8b;
            color: white;
            border: none;
            padding: 8px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background: #005f5f;
        }

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
        <h2>Reset Password</h2>
        <form action="resetPassword.php" method="POST">
            <input type="password" name="password" required placeholder="New Password">
            <input type="password" name="confirm_password" required placeholder="Confirm Password">
            <button type="submit" name="btnChangePassword">Reset</button>
        </form>
        <a href="pharmacistSignup.php" class="back-link">Back to Login</a>
    </div>
</body>
</html>
