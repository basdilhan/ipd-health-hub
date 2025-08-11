<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

// Check database connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Pharmacist Registration (Without Password Hashing)
if (isset($_POST['btnRegister'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $teleno = mysqli_real_escape_string($con, $_POST['teleno']);
    $password = mysqli_real_escape_string($con, $_POST['password']); // Store plain text password

    // Check if email already exists
    $check_email = mysqli_query($con, "SELECT * FROM pharmacist WHERE Email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        echo "<script>
                alert('Email already exists! Please use a different email.');
                window.location.href='pharmacistSignup.php';
              </script>";
        exit();
    }

    // Insert new pharmacist record
    $query = "INSERT INTO pharmacist (Name, Location, Email, TeleNo, Password) VALUES ('$name', '$location', '$email', '$teleno', '$password')";

    if (mysqli_query($con, $query)) {
        echo "<script>
                alert('Registration successful! Please log in.');
                window.location.href='pharmacistSignup.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($con) . "');
              </script>";
        exit();
    }
}

// Handle Pharmacist Login
if (isset($_POST['btnLogin'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $query = "SELECT * FROM pharmacist WHERE Email = '$email'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row && $password === $row['Password']) { // Directly compare passwords
        $_SESSION['loggedin'] = true;
        $_SESSION['pharmacist_id'] = $row['PharmacistID'];
        $_SESSION['pharmacist_email'] = $row['Email'];

        header("Location: pharmacistDashboard.php");
        exit();
    } else {
        echo "<script>
                alert('Invalid email or password!');
                window.location.href='pharmacistSignup.php';
              </script>";
        exit();
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Authentication - IPD Health Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Background Styling */
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: url('PharmacistBack.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* Dark overlay for better readability */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); 
            z-index: 0;
        }

        /* Auth Form Container */
        .container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
            width: 500px;
            max-width: 95%;
            text-align: center;
            color: black;
        }

        /* Title */
        .container h2 {
            color: #008b8b;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Logo */
        .logo {
            width: 70px;
            height: auto;
            margin-bottom: 10px;
        }

        /* Input Styling */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #004d40;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease-in-out;
            background: #f9f9f9;
        }

        .form-group input:focus {
            border-color: #32e0c4;
            box-shadow: 0 0 10px rgba(50, 224, 196, 0.3);
            background: white;
        }

        /* Button Styling */
        .btn {
            width: 100%;
            padding: 14px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary {
            background: #32e0c4;
            color: white;
        }

        .btn-primary:hover {
            background: #008b8b;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 139, 139, 0.4);
        }

        .btn-secondary {
            background: #ddd;
            color: black;
        }

        .btn-secondary:hover {
            background: #bbb;
            transform: scale(1.05);
        }

        /* Toggle Link */
        .toggle-link {
            cursor: pointer;
            color: #008b8b;
            font-weight: bold;
            text-decoration: none;
        }

        .toggle-link:hover {
            text-decoration: underline;
        }

        /* Icon Styling */
        .icon {
            font-size: 40px;
            color: #32e0c4;
            margin-bottom: 10px;
        }

        /* Back Button */
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #008b8b;
        }

        .back-button:hover {
            color: #32e0c4;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 30px;
            }
        }

        /* Hide Signup Form by Default */
        #signUpForm {
            display: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <button class="back-button" onclick="window.location.href='index.php'"><i class="fas fa-arrow-left"></i></button>
        <img src="mm.ICO" alt="IPD Health Hub Logo" class="logo">
        <h2 id="modalTitle">Welcome to IPD Health Hub</h2>
        <i class="fas fa-user-md icon"></i>

        <!-- Login Form -->
        <form id="signInForm" action="pharmacistSignup.php" method="POST">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="btnLogin" class="btn btn-primary">Login</button>
        </form>

        <p><a class="toggle-link" onclick="toggleSignUpForm()">Create New Account</a></p>
          <p><a href="ForgotPassword.php" class="toggle-link">Forgot Password?</a></p>


        <!-- Signup Form -->
        <form id="signUpForm" action="pharmacistSignup.php" method="POST" onsubmit="return validateForm()">
            <h2>Create an Account</h2>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Location:</label>
                <input type="text" name="location" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="email" name="email" required oninput="validateEmail()">
                <span class="error-message" id="emailError">Invalid email address.</span>
            </div>
            <div class="form-group">
                <label>Contact No:</label>
                <input type="text" id="phone" name="teleno" required oninput="validatePhone()">
                <span class="error-message" id="phoneError" style="display: none; color: red;">Invalid Sri Lankan mobile number.</span>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="btnRegister" class="btn btn-primary">Sign Up</button>
            <button type="button" class="btn btn-secondary" onclick="toggleLoginForm()">Back to Login</button>


        </form>
    </div>

    <script>
        // Toggle between Login and Signup Forms
        function toggleSignUpForm() {
            document.getElementById("signInForm").style.display = "none";
            document.getElementById("signUpForm").style.display = "block";
            document.getElementById("modalTitle").innerText = "Create an Account";
        }

        function toggleLoginForm() {
            document.getElementById("signUpForm").style.display = "none";
            document.getElementById("signInForm").style.display = "block";
            document.getElementById("modalTitle").innerText = "Welcome to IPD Health Hub";
        }

        // Validate Sri Lankan Phone Number
        function validatePhone() {
            var phoneInput = document.getElementById("phone");
            var phoneError = document.getElementById("phoneError");
            var phonePattern = /^(070|071|072|074|075|076|077|078)\d{7}$/;

            if (phonePattern.test(phoneInput.value)) {
                phoneError.style.display = "none"; 
                return true;
            } else {
                phoneError.style.display = "block"; 
                return false;
            }
        } // Validate Email Address
        function validateEmail() {
            var emailInput = document.getElementById("email");
            var emailError = document.getElementById("emailError");
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (emailPattern.test(emailInput.value)) {
                emailError.style.display = "none"; 
                return true;
            } else {
                emailError.style.display = "block";
                return false;
            }
        }

        // Validate Form Before Submission
        function validateForm() {
            if (!validatePhone()) {
                alert("Please enter a valid Sri Lankan mobile number before submitting.");
                return false;
            }
            return true;
        }
    </script>

</body>
</html>