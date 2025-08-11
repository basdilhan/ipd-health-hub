<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Handle Appointment Booking
if (isset($_POST['btnBook'])) {
    $schedule_id = $_POST['schedule_id'];
    $doctor_id = $_POST['doctor_id'];
    $hospital_id = $_POST['hospital_id'];
    $date = $_POST['date'];

    // Start transaction to prevent race conditions
    $con->begin_transaction();

    try {
        // Fetch current max appointments and check availability
        $query = "SELECT MaxAppointments FROM schedule WHERE ScheduleID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $current_max = $row['MaxAppointments'];

        if ($current_max <= 0) {
            echo "<script>alert('No available slots! Please choose another schedule.'); window.location.href='scheduleAppointments.php';</script>";
            exit();
        }

        // Insert booking into the appointment table
        $stmt = $con->prepare("INSERT INTO appointment (Adate, DID, HospitalID, ScheduleID) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $date, $doctor_id, $hospital_id, $schedule_id);
        $stmt->execute();

        // Update the MaxAppointments (Reduce by 1)
        $stmt = $con->prepare("UPDATE schedule SET MaxAppointments = MaxAppointments - 1 WHERE ScheduleID = ?");
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();

        // Commit transaction
        $con->commit();

        echo "<script>alert('Appointment booked successfully!'); window.location.href='paymentForm.php';</script>";
    } catch (Exception $e) {
        $con->rollback(); // Rollback in case of error
        echo "<script>alert('Error booking appointment: " . $e->getMessage() . "');</script>";
    }
}

// Close connection
$con->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking - IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            padding-top: 70px;
            background-color: #f0f8ff;
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
            max-width: 600px;
            margin-top: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card-title {
            font-size: 22px;
            color: #008b8b;
            font-weight: bold;
        }
        .details {
            font-size: 18px;
            margin: 10px 0;
        }
        .btn-primary {
            background-color: #008b8b;
            border-color: #008b8b;
            border-radius: 30px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #006060;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="index.php">IPD Health Hub</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    <div class="container">
        <h2 class="text-primary">Confirm Your Appointment</h2>

        <div class="card">
            <h4 class="card-title"><i class="fas fa-user-md"></i> <?= $appointment['DoctorName']; ?></h4>
            <p class="details"><b>Hospital:</b> <?= $appointment['HospitalName']; ?> (<?= $appointment['HospitalLocation']; ?>)</p>
            <p class="details"><b>Room No:</b> <?= $appointment['RoomNo']; ?></p>
            <p class="details"><b>Date:</b> <?= date("F j, Y", strtotime($date)); ?></p>
            <p class="details"><b>Time:</b> <?= date("h:i A", strtotime($appointment['Time'])); ?></p>
        </div>

        <!-- Confirm Booking Form -->
        <form action="paymentForm.php" method="POST">
            <input type="hidden" name="schedule_id" value="<?= $schedule_id; ?>">
            <input type="hidden" name="doctor_id" value="<?= $doctor_id; ?>">
            <input type="hidden" name="hospital_id" value="<?= $hospital_id; ?>">
            <input type="hidden" name="date" value="<?= $date; ?>">
            <button type="submit" name="btnConfirm" class="btn btn-primary">Confirm Booking & Proceed to Payment</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p class="mt-3">&copy; 2024 IPD Health Hub. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
