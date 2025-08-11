<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}



$doctor_id = intval($_POST['doctor_id']);
$hospital_id = intval($_POST['hospital_id']);
$date = !empty($_POST['date']) ? $_POST['date'] : NULL;

// SQL Query: Fetch Available Appointments
$query = "
    SELECT 
        s.ScheduleID, s.RoomNo, s.MaxAppointments, s.Time, s.Date,
        d.Name AS DoctorName, h.Name AS HospitalName,
        (s.MaxAppointments) AS AvailableAppointments
    FROM schedule s
    JOIN doctor d ON s.DID = d.DID
    JOIN hospital h ON s.HospitalID = h.HospitalID
    LEFT JOIN (
        SELECT ScheduleID, COUNT(*) AS booked_count
        FROM appointment
        GROUP BY ScheduleID
    ) a ON s.ScheduleID = a.ScheduleID
    WHERE s.DID = ? 
      AND s.HospitalID = ?
      AND s.Date >= CURDATE()";  // ✅ Only future or today


$params = [$doctor_id, $hospital_id];
$types = "ii";

if ($date) {
    $query .= " AND s.Date = ?";
    $params[] = $date;
    $types .= "s";
}

$query .= " ORDER BY s.Date, s.Time";

$stmt = $con->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Appointments - IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            padding-top: 70px;
            background-color: #e3f2fd;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        .navbar {
            background-color: #008b8b;
        }
        .navbar-brand, .navbar-light .navbar-nav .nav-link {
            color: #fff !important;
        }
        .container {
            max-width: 900px;
            margin-top: 30px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            text-align: center;
            background: white;
        }
        .card-title {
            font-size: 22px;
            color: #008b8b;
            font-weight: bold;
        }
        .card p {
            font-size: 16px;
            margin: 5px 0;
        }
        .btn-primary {
            background-color: #008b8b;
            border-color: #008b8b;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #006060;
        }
        .nav__logo img {
           width: 50px;
           height: 50px;
           vertical-align: middle;
           border-radius: 50%;
           object-fit: cover;
       }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="index.php">IPD Health Hub
        <span class="nav__logo"><img src="mm.ICO" alt="logo" class="logo-white" /></span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    <div class="container">
        <h2 class="text-center text-primary">Available Appointments</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="card">
                            <h4 class="card-title"><i class="fas fa-user-md"></i> <?= $row['DoctorName']; ?></h4>
                            <p><b>Hospital:</b> <?= $row['HospitalName']; ?></p>
                            <p><b>Room:</b> <?= $row['RoomNo']; ?></p>
                            <p><b>Date:</b> <?= date("F j, Y", strtotime($row['Date'])); ?></p>
                            <p><b>Time:</b> <?= date("h:i A", strtotime($row['Time'])); ?></p>
                           <p><b>Available Slots:</b> <?= $row['AvailableAppointments']; ?></p>

<?php if ($row['AvailableAppointments'] > 0): ?>
    <form method="POST" action="bookAppointment.php">
        <input type="hidden" name="schedule_id" value="<?= isset($row['ScheduleID']) ? htmlspecialchars($row['ScheduleID']) : '' ?>">
<input type="hidden" name="doctor_id" value="<?= isset($doctor_id) ? htmlspecialchars($doctor_id) : '' ?>">
<input type="hidden" name="hospital_id" value="<?= isset($hospital_id) ? htmlspecialchars($hospital_id) : '' ?>">
<input type="hidden" name="date" value="<?= isset($date) ? htmlspecialchars($date) : '' ?>">

        <button type="submit" name="btnBook" class="btn btn-primary">Book Appointment</button>
    </form>
<?php else: ?>
    <button class="btn btn-secondary" disabled onclick="alert('❌ No available slots for this appointment.')">
        No Slots Available
    </button>
<?php endif; ?>



                        </div>
                    </div>

                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">No available appointments for the selected criteria.</div>
        <?php endif; ?>
    </div>

</body>
</html>
