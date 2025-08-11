<?php
session_start();
if(!isset($_SESSION['loggedin'])){
    header('location: AdminLogin.php');
    exit();
}
?>
<?php
$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['btnAddAdmin'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    $teleno = mysqli_real_escape_string($con, $_POST['teleno']);

    $check_email = mysqli_query($con, "SELECT * FROM hospitaladmin WHERE Email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        echo "<script>alert('Email already exists! Please use a different email.'); window.location.href='Addadmin.php';</script>";
        exit();
    }

    $query = "INSERT INTO hospitaladmin (Name, Password, TeleNo, Email) VALUES ('$name', '$password', '$teleno', '$email')";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Admin added successfully!'); window.location.href='AdminPanel.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin - IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #32e0c4;
            padding: 20px;
            position: fixed;
            color: white;
        }
        .sidebar h2 {
            font-size: 22px;
            text-align: center;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            font-size: 18px;
            display: block;
            color: white;
            padding: 10px;
            transition: 0.3s;
        }
        .sidebar ul li a:hover {
            background: #26c2aa;
            border-radius: 5px;
        }
        .main-content {
            margin-left: 270px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: calc(100% - 270px);
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            width: 420px;
            text-align: center;
        }
        h2 {
            color: #008b8b;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .btn-primary {
            background: #32e0c4;
            color: white;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background: #008b8b;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="Adminpanel.php">Home</a></li>
            <li><a href="manageAppointments.php">Manage Clinics</a></li>
            <li><a href="ManageRooms.php">Room Availability</a></li>
            <li><a href="alerts.php">Send Alerts</a></li>
            <li><a href="doctors.php">Add Doctors</a></li>
            <li><a href="#">Add Admins</a></li>
            <li><a href="ViewReservations.php">View Reservations</a></li>
            <li><a href="viewAppointments.php">View Appointments</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="container">
            <h2>Add New Admin</h2>
            <form method="POST" action="Addadmin.php">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Contact No:</label>
                    <input type="text" name="teleno" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="btnAddAdmin" class="btn btn-primary">Add Admin</button>
            </form>
        </div>
    </div>
</body>
</html>
