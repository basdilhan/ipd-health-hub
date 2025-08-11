<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Ensure schedule_id is available
if (!isset($_POST['schedule_id']) || empty($_POST['schedule_id'])) {
    die("<script>alert('❌ Schedule ID NOT received! Please try again.'); window.history.back();</script>");
}
$schedule_id = intval($_POST['schedule_id']);

// ✅ Fetch Schedule details including Doctor and Hospital information
$query = "
    SELECT 
        s.ScheduleID, s.RoomNo, s.Time, s.Date, s.MaxAppointments, s.HospitalID, s.DID,
        d.Name AS Doctor_Name, d.Speciality, 
        h.Name AS Hospital_Name, h.Location AS Hospital_Location
    FROM schedule s
    JOIN doctor d ON s.DID = d.DID
    JOIN hospital h ON s.HospitalID = h.HospitalID
    WHERE s.ScheduleID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();
$schedule = $result->fetch_assoc();
$stmt->close();

// ✅ Ensure valid schedule data
if (!$schedule) {
    die("<script>alert('❌ Schedule details not found!'); window.history.back();</script>");
}

// Retrieve values from schedule
$room_no = $schedule['RoomNo'];
$hospital_id = $schedule['HospitalID'];
$doctor_id = $schedule['DID'];
$date = $schedule['Date'];
$time = $schedule['Time'];
$max_appointments = $schedule['MaxAppointments'];
$doctor_name = $schedule['Doctor_Name'];
$speciality = $schedule['Speciality'];
$hospital_name = $schedule['Hospital_Name'];
$hospital_location = $schedule['Hospital_Location'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_appointment'])) {
    $patient_name = trim($_POST['name']);
    $patient_email = trim($_POST['email']);
    $patient_age = intval($_POST['age']);
    $patient_gender = trim($_POST['gender']);
    $patient_phone = trim($_POST['phone']);

    if (!empty($patient_name) && !empty($patient_email) && $patient_age > 0 && !empty($patient_gender) && !empty($patient_phone)) {
        
        // ✅ Insert patient details into patient table
        $insert_patient = "INSERT INTO patient (Name, Email, Age, Gender, TeleNo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insert_patient);
        $stmt->bind_param("ssiss", $patient_name, $patient_email, $patient_age, $patient_gender, $patient_phone);
        $stmt->execute();
        $pid = $stmt->insert_id;
        $stmt->close();

        // ✅ Check if slots are available
        if ($max_appointments > 0) {
            // ✅ Generate next appointment number
            $max_appointment_query = "SELECT COALESCE(MAX(appointmentNo), 0) FROM appointment WHERE ScheduleID = ?";
            $stmt = $con->prepare($max_appointment_query);
            $stmt->bind_param("i", $schedule_id);
            $stmt->execute();
            $stmt->bind_result($max_appointment);
            $stmt->fetch();
            $stmt->close();

            $appointment_no = $max_appointment + 1;

            // ✅ Insert appointment details into appointment table
            $insert_appointment = "INSERT INTO appointment (appointmentNo, RoomNo, Date, Time, HospitalID, DID, PID, ScheduleID) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($insert_appointment);
            $stmt->bind_param("isssiiii", $appointment_no, $room_no, $date, $time, $hospital_id, $doctor_id, $pid, $schedule_id);
            
            if ($stmt->execute()) {
                $appointment_id = $stmt->insert_id; // Get inserted appointment ID
                $stmt->close();

                // ✅ Update MaxAppointments count in schedule table
                $update_schedule = "UPDATE schedule SET MaxAppointments = MaxAppointments - 1 WHERE ScheduleID = ?";
                $stmt = $con->prepare($update_schedule);
                $stmt->bind_param("i", $schedule_id);
                $stmt->execute();
                $stmt->close();

                // ✅ Redirect to **Appointment Details Page** instead of Payment Form
                echo "<script>
                    setTimeout(function() {
                        alert('✅ Appointment booked successfully! Redirecting to appointment details...');
                        window.location.href='AppointmentDetails.php?aid=$appointment_id&pid=$pid';
                    }, 500);
                </script>";

                exit();
            } else {
                echo "<script>alert('❌ Error booking appointment. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('❌ No available slots for this schedule. Please select another date.');</script>";
        }
    } else {
        echo "<script>alert('❌ All fields are required! Please fill in the form correctly.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
  <style>
        body {
            background-color: #e3f2fd;
            font-family: 'Arial', sans-serif;
            padding-top: 70px;
        }
        .navbar {
            background-color: #0097A7 !important;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }
        .navbar-nav .nav-link:hover {
            color: #80deea !important;
        }
        .container {
            max-width: 800px;
            margin-top: 30px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #00838F;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
        }
        .card-body {
            padding: 20px;
        }
        .form-group label {
            font-weight: bold;
            color: #004d40;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #00838F;
        }
        .confirm-button {
            width: 100%;
            margin-top: 15px;
            background-color: #00796B;
            border-color: #00796B;
            font-size: 18px;
            font-weight: bold;
            padding: 12px;
            border-radius: 8px;
            color: white;
            transition: 0.3s;
        }
        .confirm-button:hover {
            background-color: #004d40;
        }
        .footer {
            background-color: #0097A7;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 30px;
            font-size: 14px;
        }
         .nav__logo img {
           width: 50px;
           height: 50px;
           vertical-align: middle;
           border-radius: 50%;
           object-fit: cover;
       }
    </style>
<body>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="index.php">IPD Health Hub 
            <span class="nav__logo"><img src="mm.ICO" alt="logo" class="logo-white" /></span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
               
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-info text-white text-center">
                <h4>Dr. <?= htmlspecialchars($doctor_name); ?> (<?= htmlspecialchars($speciality); ?>)</h4>
            </div>
            <div class="card-body">
                <p><strong>Hospital:</strong> <?= htmlspecialchars($hospital_name); ?>, <?= htmlspecialchars($hospital_location); ?></p>
                <p><strong>Appointment Date:</strong> <?= date("F j, Y", strtotime($date)); ?></p>
                <p><strong>Time:</strong> <?= date("h:i A", strtotime($time)); ?></p>
                <p><strong>Room No:</strong> <?= htmlspecialchars($room_no); ?></p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-success text-white text-center">
                <h5>Enter Patient Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="schedule_id" value="<?= htmlspecialchars($schedule_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <button type="submit" name="confirm_appointment" class="btn btn-primary btn-block">Confirm Appointment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
