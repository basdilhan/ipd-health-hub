<?php  
session_start();

if(isset($_POST['btnLogin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create connection with MySQL server
    $con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Select database
    mysqli_select_db($con, "ipdhealthhub");

    // Perform SQL operations (Check admin credentials)
    $sql = "SELECT * FROM hospitaladmin WHERE Email='$email' AND Password='$password'";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Error in query: " . mysqli_error($con));
    }

    // Check if login is successful
    if ($row = mysqli_fetch_array($result)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['admin_id'] = $row['AdminID']; // Store Admin ID
        $_SESSION['lat'] = time();

        // Redirect to admin panel
        header("Location: Adminpanel.php");
        exit();
    } else {
        echo "<script>alert('Incorrect email or password. Please try again.');</script>";
    }

    // Disconnect from server
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            background: url('AdminBack.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            position: relative;
        }

        /* Dark overlay for better readability */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        /* Back button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: #32e0c4;
            font-weight: bold;
            z-index: 2;
        }

        .back-button:hover {
            color: #008b8b;
        }

        /* Login container */
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 400px;
            max-width: 90%;
            text-align: center;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #008b8b;
            font-size: 28px;
            font-weight: 600;
        }

        /* Input group styling */
        .input-group {
            margin-bottom: 20px;
            text-align: left;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
            background: #f9f9f9;
            transition: all 0.3s ease-in-out;
            outline: none;
        }

        .input-group input:focus {
            border-color: #32e0c4;
            box-shadow: 0 0 10px rgba(50, 224, 196, 0.3);
            background: #fff;
        }

        .input-group svg {
            position: absolute;
            top: 40px;
            right: 12px;
            color: #777;
            cursor: pointer;
        }

        /* Login button */
        .login-btn {
            width: 100%;
            padding: 14px;
            background: #32e0c4;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease-in-out;
        }

        .login-btn:hover {
            background: #008b8b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 139, 139, 0.3);
        }

        /* Additional text or links */
        .additional-text {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .additional-text a {
            color: #32e0c4;
            text-decoration: none;
            font-weight: 500;
        }

        .additional-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
       
        <h2>Login</h2>
        <form method="post" action="#">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <button type="submit" name="btnLogin" class="login-btn">Login</button>
        </form>
        
    </div>
</body>
</html>
