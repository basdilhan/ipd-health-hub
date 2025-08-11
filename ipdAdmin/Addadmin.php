<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('location: AdminLogin.php');
    exit();
}

$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Adding a New Admin
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
        echo "<script>alert('Admin added successfully!'); window.location.href='Addadmin.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

// Handle Editing an Existing Admin
if (isset($_POST['btnEditAdmin'])) {
    $admin_id = $_POST['admin_id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $teleno = mysqli_real_escape_string($con, $_POST['teleno']);
    $password = $_POST['password'];

    $query = "UPDATE hospitaladmin SET Name='$name', Email='$email', TeleNo='$teleno' ";
    
    // Update password only if provided
    if (!empty($password)) {
        $query .= ", Password='$password'";
    }

    $query .= " WHERE AdminID='$admin_id'";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Admin details updated successfully!'); window.location.href='Addadmin.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating admin details: " . mysqli_error($con) . "');</script>";
    }
}

// Fetch Existing Admins for Editing
$admin_result = mysqli_query($con, "SELECT * FROM hospitaladmin");

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins - IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            display: flex;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
      /* General Sidebar Styling */
.sidebar {
    width: 80px; /* Default collapsed width */
    height: 100vh;
    background: rgba(32, 201, 151, 0.9); /* Semi-transparent cyan */
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    box-shadow: 5px 0 15px rgba(32, 201, 151, 0.4);
    padding-top: 20px;
    text-align: center;
    border-right: 3px solid #1ea896;
    backdrop-filter: blur(10px); /* Glassmorphism effect */
    overflow: hidden;
    transition: all 0.3s ease-in-out;
}

/* Expanded Sidebar on Hover */
.sidebar:hover {
    width: 250px;
}

/* Sidebar Header */
.sidebar h2 {
    font-weight: 700;
    font-size: 24px;
    margin-bottom: 30px;
    color: white;
    text-shadow: 0 0 12px rgba(255, 255, 255, 0.5);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

/* Show Sidebar Text on Hover */
.sidebar:hover h2 {
    opacity: 1;
}

/* Sidebar Navigation */
.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin: 10px 0;
}

/* Sidebar Links */
.sidebar ul li a {
    color: white;
    text-decoration: none;
    font-size: 0; /* Hide text by default */
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px 20px;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    background: rgba(255, 255, 255, 0.1);
    position: relative;
}

/* Show Text on Hover */
.sidebar:hover ul li a {
    font-size: 18px;
    justify-content: flex-start;
}

/* Sidebar Icons */
.sidebar ul li a i {
    font-size: 24px;
    margin-right: 0; /* Hide margin when collapsed */
    transition: margin 0.3s ease-in-out;
}

/* Adjust Icon Margin when Sidebar Expands */
.sidebar:hover ul li a i {
    margin-right: 12px;
}

/* Sidebar Hover Effects */
.sidebar ul li a:hover {
    background: rgba(255, 255, 255, 0.2);
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
    transform: translateX(10px);
}



/* Mobile Responsive */
@media (max-width: 768px) {
    .sidebar {
        width: 60px;
    }

    .sidebar:hover {
        width: 200px;
    }

    .sidebar ul li a {
        font-size: 0;
    }

    .sidebar:hover ul li a {
        font-size: 16px;
    }
}

        .main-content {
            margin-left: 270px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: calc(100% - 270px);
            flex-direction: column;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            width: 420px;
            text-align: center;
            display: none;
        }
        h2 {
            color: #008b8b;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-group input, select {
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
        .toggle-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .toggle-buttons button {
            width: 150px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="Adminpanel.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="manageAppointments.php"><i class="fas fa-clinic-medical"></i>Manage Clinics</a></li>
            <li><a href="ManageRooms.php"><i class="fas fa-bed"></i>Room Availability</a></li>
            <li><a href="doctors.php"><i class="fas fa-user-md"></i>Manage Doctors</a></li>
            <li><a href="#"><i class="fas fa-user-plus"></i>Manage Admins</a></li>
            <li><a href="ViewReservations.php"><i class="fas fa-calendar-check"></i>View Reservations</a></li>
            <li><a href="ViewAppointments.php"><i class="fas fa-calendar-alt"></i>View Appointments</a></li>
            <li><a href="ViewReports.php"><i class="fas fa-chart-line"></i>View Reports</a></li> <!-- Added View Reports -->
            
        </ul>
    </div>

    <div class="main-content">
        <div class="toggle-buttons">
            <button class="btn btn-primary" onclick="toggleForm('add')">Add Admin</button>
            <button class="btn btn-primary" onclick="toggleForm('edit')">Edit Admin</button>
        </div>

        <!-- Add Admin Form -->
        <div class="container" id="addForm">
            <h2>Add New Admin</h2>
            <form method="POST">
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

        <!-- Edit Admin Form -->
        <div class="container" id="editForm">
            <h2>Edit Admin Details</h2>
            <form method="POST">
                <label>Select Admin:</label>
                <select name="admin_id" required>
                    <?php while ($admin = mysqli_fetch_assoc($admin_result)) { ?>
                        <option value="<?= $admin['AdminID'] ?>"><?= $admin['Name'] ?> - <?= $admin['Email'] ?></option>
                    <?php } ?>
                </select>
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
                    <label>New Password (Leave blank to keep old password):</label>
                    <input type="password" name="password">
                </div>
                <button type="submit" name="btnEditAdmin" class="btn btn-primary">Update Admin</button>
            </form>
        </div>
    </div>
     <script>
        function toggleForm(formType) {
            document.getElementById('addForm').style.display = (formType === 'add') ? 'block' : 'none';
            document.getElementById('editForm').style.display = (formType === 'edit') ? 'block' : 'none';
        }
    </script>
</body>
</html>
