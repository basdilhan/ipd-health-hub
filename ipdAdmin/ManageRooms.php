<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

// Check database connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch hospital list for dropdown
$hospital_result = mysqli_query($con, "SELECT HospitalID, Name, Location FROM hospital");

// Handle Adding a Room
if (isset($_POST['btnAddRoom'])) {
    $hospital_id = $_POST['hospital_id'];
    $room_number = $_POST['room_number'];
    $availability = $_POST['availability'];

    $query = "INSERT INTO room (HospitalID, RoomNumber, Availability) VALUES ('$hospital_id', '$room_number', '$availability')";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Room added successfully!'); window.location.href='ManageRooms.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

// Handle Deleting a Room
if (isset($_POST['btnDeleteRoom'])) {
    $room_id = $_POST['room_id'];

    $query = "DELETE FROM room WHERE RoomID = '$room_id'";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Room deleted successfully!'); window.location.href='ManageRooms.php';</script>";
    } else {
        echo "<script>alert('Error deleting room: " . mysqli_error($con) . "');</script>";
    }
}

// Handle Editing Room Availability
if (isset($_POST['btnEditRoom'])) {
    $room_id = $_POST['room_id'];
    $availability = $_POST['availability'];

    $query = "UPDATE room SET Availability = '$availability' WHERE RoomID = '$room_id'";
    
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Room updated successfully!'); window.location.href='ManageRooms.php';</script>";
    } else {
        echo "<script>alert('Error updating room: " . mysqli_error($con) . "');</script>";
    }
}

// Fetch room details with hospital name and location
$room_result = mysqli_query($con, "
    SELECT r.RoomID, r.RoomNumber, r.Availability, h.Name AS HospitalName, h.Location 
    FROM room r
    INNER JOIN hospital h ON r.HospitalID = h.HospitalID
");

if (!$room_result) {
    die("Query Failed: " . mysqli_error($con)); // Debugging error message
}

// Store room data in an array for reuse
$rooms = [];
while ($room = mysqli_fetch_assoc($room_result)) {
    $rooms[] = $room;
}

// Close the connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Room Availability</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0;
            display: flex;
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
            padding: 40px;
            width: 100%;
        }

        .container {
            background: white;
            padding: 30px;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-top: 5px;
            font-size: 16px;
        }

        .submit-btn {
            width: 100%;
            background: #32e0c4;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }

        .submit-btn:hover {
            background: #26c2aa;
        }

        .delete-btn {
            width: 100%;
            background: red;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }

        .delete-btn:hover {
            background: darkred;
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
            <li><a href="#"><i class="fas fa-bed"></i>Room Availability</a></li>
            <li><a href="doctors.php"><i class="fas fa-user-md"></i>Manage Doctors</a></li>
            <li><a href="Addadmin.php"><i class="fas fa-user-plus"></i>Manage Admins</a></li>
            <li><a href="ViewReservations.php"><i class="fas fa-calendar-check"></i>View Reservations</a></li>
            <li><a href="ViewAppointments.php"><i class="fas fa-calendar-alt"></i>View Appointments</a></li>
            <li><a href="ViewReports.php"><i class="fas fa-chart-line"></i>View Reports</a></li> <!-- Added View Reports -->
            
        </ul>
    </div>

    <div class="main-content">
        <h1>Manage Room Availability</h1>

        <!-- Add New Room -->
        <div class="container">
            <h2>Add New Room</h2>
            <form method="POST">
                <label>Select Hospital:</label>
                <select name="hospital_id" required>
                    <option value="">Select a Hospital</option>
                    <?php while ($hospital = mysqli_fetch_assoc($hospital_result)) { ?>
                        <option value="<?= $hospital['HospitalID'] ?>">
                            <?= $hospital['Name'] ?> - <?= $hospital['Location'] ?>
                        </option>
                    <?php } ?>
                </select>

                <label>Room Number:</label>
                <input type="number" name="room_number" required>

                <label>Availability:</label>
                <select name="availability" required>
                    <option value="Available">Available</option>
                    <option value="Occupied">Occupied</option>
                </select>

                <button type="submit" name="btnAddRoom" class="submit-btn">Add Room</button>
            </form>
        </div>

        <!-- Edit Room Availability -->
        <div class="container">
            <h2>Edit Room Availability</h2>
            <form method="POST">
                <label>Select Room:</label>
                <select name="room_id" required>
                    <option value="">Select a Room</option>
                    <?php foreach ($rooms as $room) { ?>
                        <option value="<?= $room['RoomID'] ?>">
                            Room <?= $room['RoomNumber'] ?> (<?= $room['HospitalName'] ?> - <?= $room['Location'] ?>)
                        </option>
                    <?php } ?>
                </select>

                <label>New Availability:</label>
                <select name="availability" required>
                    <option value="Available">Available</option>
                    <option value="Occupied">Occupied</option>
                </select>

                <button type="submit" name="btnEditRoom" class="submit-btn">Update Room</button>
            </form>
        </div>

        <!-- Delete Room -->
        <div class="container">
            <h2>Delete Room</h2>
            <form method="POST">
                <label>Select Room:</label>
                <select name="room_id" required>
                    <option value="">Select a Room</option>
                    <?php foreach ($rooms as $room) { ?>
                        <option value="<?= $room['RoomID'] ?>">
                            Room <?= $room['RoomNumber'] ?> (<?= $room['HospitalName'] ?> - <?= $room['Location'] ?>)
                        </option>
                    <?php } ?>
                </select>

                <button type="submit" name="btnDeleteRoom" class="delete-btn">Delete Room</button>
            </form>
        </div>
    </div>
</body>
</html>