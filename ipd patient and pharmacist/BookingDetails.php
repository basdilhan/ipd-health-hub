<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Check if PID is provided in URL
if (!isset($_GET['pid']) || empty($_GET['pid'])) {
    die("<script>alert('❌ Patient ID not provided!'); window.history.back();</script>");
}
$pid = intval($_GET['pid']);

// Fetch booking details for the specific patient
$query = "
    SELECT 
        p.PID, p.Name AS PatientName, p.Email, p.Age, p.Gender, p.TeleNo, 
        r.RoomNumber, h.Name AS HospitalName, h.Location AS HospitalLocation, 
        pr.StartDate
    FROM `patient-room` pr
    JOIN patient p ON pr.PID = p.PID
    JOIN room r ON pr.RoomID = r.RoomID
    JOIN hospital h ON r.HospitalID = h.HospitalID
    WHERE p.PID = ?";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $pid);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

// If no booking found
if (!$booking) {
    die("<script>alert('❌ No booking details found for this patient!'); window.history.back();</script>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Booking Details | IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <style type="text/css"> /* General Styles */
   </head>
body {
    background-color: #e3f2fd;
    font-family: Arial, sans-serif;
    padding-top: 70px;
}

/* Container Styling */
.container {
    max-width: 600px;
    margin-top: 50px;
    background-color: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Header Styling */
.container h2 {
    color: #008b8b;
    margin-bottom: 20px;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0, 139, 139, 0.3);
}

/* Information Box */
.info {
    text-align: left;
    font-size: 18px;
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    border-left: 5px solid #008b8b;
}

/* Paragraph Styles */
.info p {
    margin: 10px 0;
    font-weight: 500;
    color: #333;
}

/* Horizontal Line */
hr {
    border: 0;
    height: 1px;
    background: #ddd;
    margin: 15px 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 20px;
    }
    .info {
        font-size: 16px;
    }
}
</style>

<body>
    <div class="container">
        <h2>Patient Booking Details</h2>
        <div class="info">
            <p><strong>Patient Name:</strong> <?= htmlspecialchars($booking['PatientName']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($booking['Email']); ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($booking['Age']); ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($booking['Gender']); ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($booking['TeleNo']); ?></p>
            <hr>
            <p><strong>Hospital:</strong> <?= htmlspecialchars($booking['HospitalName']); ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($booking['HospitalLocation']); ?></p>
            <p><strong>Room Number:</strong> <?= htmlspecialchars($booking['RoomNumber']); ?></p>
            <p><strong>Start Date:</strong> <?= date("F j, Y", strtotime($booking['StartDate'])); ?></p>
          
        </div>

        <!-- Go to Home Button -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</body>

</html>
