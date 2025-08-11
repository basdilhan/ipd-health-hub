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
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #f0f0f0;
            color: #000;
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
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="Adminpanel.php">Home</a></li>
            <li><a href="manageAppointments.php">Manage Clinics</a></li>
            <li><a href="ManageRooms.php">Room Availability</a></li>
            <li><a href="alerts.php">Send Alerts</a></li>
            <li><a href="#">Manage Doctors</a></li>
            <li><a href="Addadmin.php">Add Admins</a></li>
            <li><a href="ViewReservations.php">View Reservations</a></li>
            <li><a href="viewAppointments.php">View Appointments</a></li>
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
