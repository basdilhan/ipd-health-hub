<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('location: AdminLogin.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        /* General Page Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background: url('backgroundDoc.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #000;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 350px; /* Increased width */
            height: 100vh;
            background: rgba(32, 201, 151, 0.95); /* Semi-transparent cyan */
            color: black;
            box-shadow: 5px 0 15px rgba(32, 201, 151, 0.4);
            padding: 30px;
            text-align: center;
            border-right: 3px solid #1ea896;
            backdrop-filter: blur(10px); /* Blur effect for modern look */
        }

        .sidebar h2 {
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 30px;
            color: white;
            text-shadow: 0 0 12px rgba(255, 255, 255, 0.5);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: black;
            text-decoration: none;
            font-size: 18px;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
            transform: translateX(10px);
        }

        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 20px;
        }

        .sidebar ul li a.logout {
            color: red;
            font-weight: bold;
            background: rgba(255, 0, 0, 0.1);
        }

        .sidebar ul li a.logout:hover {
            background: rgba(255, 0, 0, 0.2);
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.3);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            backdrop-filter: blur(10px); /* Blur effect for modern look */
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            margin: 20px;
            margin-left: 370px; /* Adjusted for the wider sidebar */
        }

        .highlight {
            font-size: 32px;
            font-weight: bold;
            color: #32e0c4;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
            height: auto;
            display: block;
            margin: 20px auto;
            animation: glow 1.5s infinite alternate;
        }

        @keyframes glow {
            from {
                filter: drop-shadow(0 0 5px #32e0c4);
            }
            to {
                filter: drop-shadow(0 0 15px #32e0c4);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;
                padding: 15px;
            }

            .main-content {
                margin-left: 0;
                margin-top: 80px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="manageAppointments.php"><i class="fas fa-clinic-medical"></i>Manage Clinics</a></li>
            <li><a href="ManageRooms.php"><i class="fas fa-bed"></i>Room Availability</a></li>
            <li><a href="alerts.php"><i class="fas fa-bell"></i>Send Alerts</a></li>
            <li><a href="doctors.php"><i class="fas fa-user-md"></i>Add Doctors</a></li>
            <li><a href="Addadmin.php"><i class="fas fa-user-plus"></i>Add Admins</a></li>
            <li><a href="ViewReservations.php"><i class="fas fa-calendar-check"></i>View Reservations</a></li>
            <li><a href="ViewAppointments.php"><i class="fas fa-calendar-alt"></i>View Appointments</a></li>
            <li><a href="ViewReports.php"><i class="fas fa-chart-line"></i>View Reports</a></li> <!-- Added View Reports -->
            <li><a href="AdminLogout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <img src="mm.ICO" alt="logo" class="logo">
        <h1 class="highlight">Welcome to IPD Health Hub</h1>
        <p>Use the sidebar to navigate through different management sections.</p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
</body>
</html>