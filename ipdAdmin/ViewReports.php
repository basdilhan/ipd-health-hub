<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Fetch list of hospitals for filtering
$hospital_query = "SELECT HospitalID, Name, Location FROM hospital";
$hospital_result = $con->query($hospital_query);

// Default to all hospitals or selected one
$hospital_id = isset($_GET['hospital']) ? intval($_GET['hospital']) : '';

// Fetch overall website statistics
$stats_query = "
    SELECT 
        (SELECT COUNT(*) FROM pharmacist) AS total_pharmacists,
        (SELECT COUNT(*) FROM patient) AS total_patients,
        (SELECT COUNT(*) FROM appointment) AS total_appointments,
        (SELECT COUNT(*) FROM payment ) AS total_payments,
        (SELECT COUNT(*) FROM `patient-room`) AS total_bed_reservations,
        (SELECT COUNT(*) FROM prescription) AS total_prescriptions
";
$stats_result = $con->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Fetch Hospital-wise Appointments
$hospital_stats_query = "
    SELECT 
        h.HospitalID,
        h.Name AS hospital_name,
        h.Location AS hospital_location,
        (SELECT COUNT(*) FROM appointment WHERE HospitalID = h.HospitalID) AS total_appointments
    FROM hospital h
    " . ($hospital_id ? "WHERE h.HospitalID = $hospital_id" : "") . "
";

$hospital_stats_result = $con->query($hospital_stats_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reports</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f4f4f9;
            font-family: 'Poppins', sans-serif;
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



        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 40px;
            width: calc(100% - 270px);
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .stat-card i {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .chart-container {
            width: 100%;
            margin: auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .btn-export {
            background: #008b8b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-export:hover {
            background: #005f5f;
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
            <li><a href="ViewAppointments.php"><i class="fas fa-calendar-alt"></i>View Appointments</a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i>View Reports</a></li> <!-- Added View Reports -->
            
        </ul>
    </div>

<!-- Main Content -->
<div class="main-content">
    <h2 class="text-center">📊 Website Activity Dashboard</h2>
    <hr>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <i class="fas fa-user-md text-primary"></i>
                <p>Pharmacists Registered</p>
                <h3><?= $stats['total_pharmacists']; ?></h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <i class="fas fa-users text-success"></i>
                <p>Patients Registered</p>
                <h3><?= $stats['total_patients']; ?></h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <i class="fas fa-credit-card text-danger"></i>
                <p>Payments Confirmed</p>
                <h3><?= $stats['total_payments']; ?></h3>
            </div>
        </div>
    </div>

    <hr>

    <!-- Filter by Hospital -->
    <form method="GET" class="mb-4">
        <label>Select Hospital:</label>
        <select name="hospital" class="form-control" onchange="this.form.submit()">
            <option value="">All Hospitals</option>
            <?php while ($hospital = $hospital_result->fetch_assoc()): ?>
                <option value="<?= $hospital['HospitalID']; ?>" <?= ($hospital['HospitalID'] == $hospital_id) ? 'selected' : ''; ?>>
                    <?= $hospital['Name'] . " - " . $hospital['Location']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <!-- Stats Cards (Hospital Reports) -->
    <?php while ($hospital_stats = $hospital_stats_result->fetch_assoc()): ?>
        <h4><?= $hospital_stats['hospital_name']; ?> (<?= $hospital_stats['hospital_location']; ?>)</h4>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="stat-card">
                    <i class="fas fa-calendar-check text-warning"></i>
                    <p>Appointments Booked</p>
                    <h3><?= $hospital_stats['total_appointments']; ?></h3>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <hr>

    <!-- Bar Chart -->
    <div class="chart-container">
        <h5 class="text-center">📈 Activity Overview</h5>
        <canvas id="barChart"></canvas>
    </div>

    <br>
</div>

<!-- Chart JS -->
<script>
const statsData = {
    labels: ["Pharmacists", "Patients", "Payments"],
    datasets: [{
        label: "Total Count",
        data: [
            <?= $stats['total_pharmacists']; ?>,
            <?= $stats['total_patients']; ?>,
            <?= $stats['total_payments']; ?>
        ],
        backgroundColor: ["#008b8b", "#32e0c4", "#ff6666"],
        borderWidth: 1
    }]
};

const ctxBar = document.getElementById("barChart").getContext("2d");
new Chart(ctxBar, {
    type: "bar",
    data: statsData,
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>

</body>
</html>
