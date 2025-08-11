<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Validate Appointment ID and Patient ID
if (!isset($_GET['aid']) || !isset($_GET['pid']) || empty($_GET['aid']) || empty($_GET['pid'])) {
    die("<div class='alert alert-danger text-center'>❌ Invalid Appointment Details</div>");
}

$appointment_id = intval($_GET['aid']);
$patient_id = intval($_GET['pid']);

// Define Fixed Payment Amounts for Each Specialty
$specialty_fees = [
    "Chest Physician" => 3000,
    "Neuro Surgeon" => 5000,
    "Eye Surgeon" => 4000,
    "Cardiologist" => 6000,
    "Counselor" => 3500,
    "Orthopedic" => 4500,
    "Gynaecologist" => 3200,
];

// Fetch appointment details along with Doctor's specialty
$query = "
    SELECT 
        a.AppointmentID, a.RoomNo, a.Date, a.Time, 
        h.Name AS HospitalName, h.Location AS HospitalLocation, 
        d.Name AS DoctorName, d.Speciality, 
        p.Name AS PatientName, p.Email, p.TeleNo
    FROM appointment a
    JOIN hospital h ON a.HospitalID = h.HospitalID
    JOIN doctor d ON a.DID = d.DID
    JOIN patient p ON a.PID = p.PID
    WHERE a.AppointmentID = ? AND p.PID = ?";

$stmt = $con->prepare($query);
$stmt->bind_param("ii", $appointment_id, $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();
$stmt->close();

// Check if appointment exists
if (!$appointment) {
    die("<div class='alert alert-danger text-center'>❌ Invalid Appointment ID</div>");
}

// Get doctor's specialty and set the payment amount
$specialty = $appointment['Speciality'] ?? "General Physician"; // Default if missing
$payment_amount = $specialty_fees[$specialty] ?? 3000; // Assign fee based on specialty or default to 3000
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details | IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Appointment Details</h2>
        <p><strong>Patient:</strong> <?= htmlspecialchars($appointment['PatientName']); ?></p>
        <p><strong>Doctor:</strong> <?= htmlspecialchars($appointment['DoctorName']); ?> (<?= htmlspecialchars($appointment['Speciality']); ?>)</p>
        <p><strong>Hospital:</strong> <?= htmlspecialchars($appointment['HospitalName']); ?>, <?= htmlspecialchars($appointment['HospitalLocation']); ?></p>
        <p><strong>Date:</strong> <?= date("F j, Y", strtotime($appointment['Date'])); ?></p>
        <p><strong>Time:</strong> <?= date("h:i A", strtotime($appointment['Time'])); ?></p>
        <p><strong>Room No:</strong> <?= htmlspecialchars($appointment['RoomNo']); ?></p>
        <h4>Amount: LKR <?= number_format($payment_amount, 2); ?></h4>
        
        <!-- Redirect to Payment Process Page -->
        <form action="paymentProcess.php" method="POST">
            <input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>">
            <input type="hidden" name="patient_id" value="<?= $patient_id; ?>">
            <input type="hidden" name="amount" value="<?= $payment_amount; ?>">
            <button type="submit" class="btn-primary">Proceed to Payment</button>
        </form>
    </div>
</body>
</html>
