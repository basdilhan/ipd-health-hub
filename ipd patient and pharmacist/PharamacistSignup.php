<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

// Check connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Pharmacist Login
if (isset($_POST['btnLogin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query pharmacist database
    $query = "SELECT * FROM pharmacist WHERE Email = '$email'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['Password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['pharmacist_id'] = $row['PharmacistID'];
        $_SESSION['pharmacist_email'] = $row['Email'];
        echo "<script>alert('Login successful!'); window.location.href='pharmacist_dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
}

// Handle Pharmacist Registration
if (isset($_POST['btnRegister'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO pharmacist (Email, Password) VALUES ('$email', '$password')";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Registration successful! You can now log in.'); window.location.href='pharmacist_login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

// Handle Password Reset
if (isset($_POST['btnReset'])) {
    $email = $_POST['email'];
    
    $query = "SELECT * FROM pharmacist WHERE Email = '$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('A password reset link has been sent to your email!');</script>";
    } else {
        echo "<script>alert('Email not found in system!');</script>";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Sign In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        .form-container h2 {
            margin-bottom: 15px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background: #32e0c4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .submit-btn:hover {
            background: #26c2aa;
        }
        .toggle-link {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 id="modalTitle">Pharmacist Login</h2>

        <!-- Sign In Form -->
        <form id="signInForm" method="POST">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="btnLogin" class="submit-btn">Login</button>
        </form>

        <p><a class="toggle-link" onclick="toggleResetForm()">Forgot Password?</a></p>

        <!-- Sign Up Form (Hidden Initially) -->
        <form id="signUpForm" method="POST" style="display:none;">
            <h3>Create an Account</h3>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="btnRegister" class="submit-btn">Sign Up</button>
        </form>

        <!-- Reset Password Form (Hidden Initially) -->
        <form id="resetPasswordForm" method="POST" style="display:none;">
            <h3>Reset Password</h3>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" name="btnReset" class="submit-btn">Reset Password</button>
        </form>

        <p><a class="toggle-link" onclick="toggleSignUpForm()">Create New Account</a></p>
    </div>

    <script>
        function toggleSignUpForm() {
            document.getElementById("signInForm").style.display = "none";
            document.getElementById("signUpForm").style.display = "block";
            document.getElementById("resetPasswordForm").style.display = "none";
        }

        function toggleResetForm() {
            document.getElementById("signInForm").style.display = "none";
            document.getElementById("resetPasswordForm").style.display = "block";
            document.getElementById("signUpForm").style.display = "none";
        }
    </script>
</body>
</html>
