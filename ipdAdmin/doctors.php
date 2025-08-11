<?php
session_start();

// Database connection
$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

// Check connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle form submission for adding a new doctor
if (isset($_POST['btnAdd'])) {
    $name = $_POST['name'];
    $speciality = $_POST['speciality'];
    $email = $_POST['email'];
    $teleno = $_POST['teleno'];

    if (!empty($name) && !empty($speciality) && !empty($email) && !empty($teleno)) {
        $stmt = $con->prepare("INSERT INTO doctor (Name, Speciality, Email, TeleNo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $speciality, $email, $teleno);

        if ($stmt->execute()) {
            echo "<script>alert('Doctor added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding doctor. Please try again.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required!');</script>";
    }
}

// Handle form submission for editing an existing doctor
if (isset($_POST['btnEdit'])) {
    $doctor_id = $_POST['doctor_id'];
    $name = $_POST['name'];
    $speciality = $_POST['speciality'];
    $email = $_POST['email'];
    $teleno = $_POST['teleno'];

    if (!empty($doctor_id) && !empty($name) && !empty($speciality) && !empty($email) && !empty($teleno)) {
        $stmt = $con->prepare("UPDATE doctor SET Name=?, Speciality=?, Email=?, TeleNo=? WHERE DID=?");
        $stmt->bind_param("ssssi", $name, $speciality, $email, $teleno, $doctor_id);

        if ($stmt->execute()) {
            echo "<script>alert('Doctor details updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating doctor details. Please try again.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required!');</script>";
    }
}

// Fetch all doctors for the dropdown
$doctor_result = mysqli_query($con, "SELECT DID, Name FROM doctor");
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #f0f0f0;
            color: #000;
        }
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


        .main-content {
            margin-left: 270px;
            padding: 50px;
            width: 100%;
            background-color: #ffffff;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
            margin: auto;
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
        .form-group input, .form-group select {
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
            <li><a href="#"><i class="fas fa-user-md"></i>Manage Doctors</a></li>
            <li><a href="Addadmin.php"><i class="fas fa-user-plus"></i>Manage Admins</a></li>
            <li><a href="ViewReservations.php"><i class="fas fa-calendar-check"></i>View Reservations</a></li>
            <li><a href="ViewAppointments.php"><i class="fas fa-calendar-alt"></i>View Appointments</a></li>
            <li><a href="ViewReports.php"><i class="fas fa-chart-line"></i>View Reports</a></li> <!-- Added View Reports -->
            
        </ul>
    </div>

    <div class="main-content">
        <h1>Manage Doctors</h1>
        <div class="form-container">
            <h2>Add/Edit Doctor</h2>
            <form method="POST" action="doctors.php">
                <div class="form-group">
                    <label for="doctor_id">Select Doctor (for Editing)</label>
                    <select name="doctor_id">
                        <option value="">Add New Doctor</option>
                        <?php while ($doctor = mysqli_fetch_assoc($doctor_result)) { ?>
                            <option value="<?= $doctor['DID'] ?>"> <?= $doctor['Name'] ?> </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Doctor Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label for="speciality">Speciality</label>
                    <input type="text" name="speciality" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="teleno">Contact Number</label>
                    <input type="text" name="teleno" required>
                </div>
                <button type="submit" name="btnAdd" class="submit-btn">Add Doctor</button>
                <button type="submit" name="btnEdit" class="submit-btn" style="background: orange;">Edit Doctor</button>
            </form>
        </div>
    </div>
</body>
</html>
