<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Get form data
if (isset($_POST['btnSearch'])) {
    $doctor_id = $_POST['doctor_id'];
    $hospital_id = $_POST['hospital_id'];
    $date = $_POST['date'];

    // Fetch available schedules for the selected date
    $schedule_query = "SELECT s.ScheduleID, s.RoomNo, s.Time, s.MaxAppointments, 
                              d.Name AS DoctorName, h.Name AS HospitalName
                       FROM schedule s
                       JOIN doctor d ON s.DID = d.DID
                       JOIN hospital h ON s.HospitalID = h.HospitalID
                       WHERE s.DID = ? AND s.HospitalID = ? AND s.Date = ?";
    $stmt = $con->prepare($schedule_query);
    $stmt->bind_param("iis", $doctor_id, $hospital_id, $date);
    $stmt->execute();
    $schedule_result = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Appointments</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
            padding-top: 70px;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #fff;
        }
        .btn-primary {
            background-color: #008b8b;
            border-color: #008b8b;
            padding: 10px 20px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #006060;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Available Appointment Schedules</h2>

    <?php if ($schedule_result->num_rows > 0): ?>
        <?php while ($schedule = $schedule_result->fetch_assoc()): ?>
            <div class="card">
                <h4><?= $schedule['DoctorName']; ?> (Room <?= $schedule['RoomNo']; ?>)</h4>
                <p><strong>Hospital:</strong> <?= $schedule['HospitalName']; ?></p>
                <p><strong>Date:</strong> <?= $date; ?></p>
                <p><strong>Time:</strong> <?= $schedule['Time']; ?></p>
                <p><strong>Max Appointments:</strong> <?= $schedule['MaxAppointments']; ?></p>

                <!-- Book Appointment Button -->
                <form action="book_appointment.php" method="POST">
                    <input type="hidden" name="schedule_id" value="<?= $schedule['ScheduleID']; ?>">
                    <input type="hidden" name="doctor_id" value="<?= $doctor_id; ?>">
                    <input type="hidden" name="hospital_id" value="<?= $hospital_id; ?>">
                    <input type="hidden" name="date" value="<?= $date; ?>">
                    <button type="submit" name="btnBook" class="btn btn-primary">Book Appointment</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center text-danger">No available schedules for the selected date.</p>
    <?php endif; ?>

</div>

</body>
</html>
