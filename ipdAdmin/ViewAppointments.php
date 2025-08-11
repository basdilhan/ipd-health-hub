<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Fetch appointment details with JOIN queries
$query = "
    SELECT 
        a.AppointmentID, a.appointmentNo, a.RoomNo, a.Date, a.Time, 
        h.Name AS HospitalName, h.Location AS HospitalLocation,
        d.Name AS DoctorName, d.Speciality AS DoctorSpeciality,
        p.Name AS PatientName, 
        a.ScheduleID
    FROM appointment a
    JOIN hospital h ON a.HospitalID = h.HospitalID
    JOIN doctor d ON a.DID = d.DID
    JOIN patient p ON a.PID = p.PID
    ORDER BY a.Date DESC, a.Time DESC";

$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Appointments | IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
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
            width: calc(100% - 270px);
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .search-container input {
            width: 50%;
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #ccc;
        }
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 210px;
            }
            .search-container input {
                width: 80%;
            }
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
            <li><a href="Addadmin.php"><i class="fas fa-user-plus"></i>Manage Admins</a></li>
            <li><a href="ViewReservations.php"><i class="fas fa-calendar-check"></i>View Reservations</a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i>View Appointments</a></li>
            <li><a href="ViewReports.php"><i class="fas fa-chart-line"></i>View Reports</a></li> <!-- Added View Reports -->
            
        </ul>
    </div>
    <div class="main-content">
        <div class="container">
            <h2 class="text-center">Appointment Details</h2>
            <div class="search-container">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by Patient, Doctor, or Hospital">
            </div>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Appointment ID</th>
                        <th>Appointment No</th>
                        <th>Patient Name</th>
                        <th>Doctor Name</th>
                        <th>Speciality</th>
                        <th>Hospital Name</th>
                        <th>Location</th>
                        <th>Room No</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Schedule ID</th>
                    </tr>
                </thead>
                <tbody id="appointmentTable">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['AppointmentID']; ?></td>
                                <td><?= $row['appointmentNo']; ?></td>
                                <td><?= $row['PatientName']; ?></td>
                                <td><?= $row['DoctorName']; ?></td>
                                <td><?= $row['DoctorSpeciality']; ?></td>
                                <td><?= $row['HospitalName']; ?></td>
                                <td><?= $row['HospitalLocation']; ?></td>
                                <td><?= $row['RoomNo']; ?></td>
                                <td><?= date("F j, Y", strtotime($row['Date'])); ?></td>
                                <td><?= date("h:i A", strtotime($row['Time'])); ?></td>
                                <td><?= $row['ScheduleID']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="text-center">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript to filter search results -->
    <script>
        document.getElementById("searchInput").addEventListener("keyup", function () {
            let filter = this.value.toUpperCase();
            let rows = document.getElementById("appointmentTable").getElementsByTagName("tr");
            for (let i = 0; i < rows.length; i++) {
                let patientColumn = rows[i].getElementsByTagName("td")[2]; // Patient Name
                let doctorColumn = rows[i].getElementsByTagName("td")[3]; // Doctor Name
                let hospitalColumn = rows[i].getElementsByTagName("td")[5]; // Hospital Name
                
                if (patientColumn && doctorColumn && hospitalColumn) {
                    let patientText = patientColumn.textContent || patientColumn.innerText;
                    let doctorText = doctorColumn.textContent || doctorColumn.innerText;
                    let hospitalText = hospitalColumn.textContent || hospitalColumn.innerText;
                    
                    if (patientText.toUpperCase().indexOf(filter) > -1 || 
                        doctorText.toUpperCase().indexOf(filter) > -1 || 
                        hospitalText.toUpperCase().indexOf(filter) > -1) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        });
    </script>
</body>
</html>
