<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Ensure pharmacist is logged in
if (!isset($_SESSION['pharmacist_id'])) {
    header("Location: pharmacistSignup.php");
    exit();
}

$pharmacist_id = $_SESSION['pharmacist_id'];

// Fetch patient details only for this pharmacist
$query = "
    SELECT p.PreID, p.Date, p.PID, p.PharmacistID,
           pt.Name AS PatientName, pt.Email, pt.Age, pt.Gender, pt.TeleNo
    FROM prescription p
    JOIN patient pt ON p.PID = pt.PID
    WHERE p.PharmacistID = ? 
    ORDER BY p.Date DESC";
    
$stmt = $con->prepare($query);
$stmt->bind_param("i", $pharmacist_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist - View Client Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            background-color: #f4f4f4;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #008b8b;
            color: white;
            padding: 20px;
            position: fixed;
            text-align: center;
        }

        .sidebar h2 {
            margin-bottom: 20px;
            font-size: 22px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .sidebar ul li a:hover {
            background: #32e0c4;
        }

        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 40px;
            width: calc(100% - 270px);
        }

        .details-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            margin-bottom: 20px;
        }

        .btn-back {
            background: #008b8b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #005f5f;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Pharmacist Panel</h2>
    <ul>
        <li><a href="pharmacistDashboard.php">Home</a></li>
        
        <li><a href="ViewClientDetails.php">View Client Details</a></li>
        <li><a href="pharmacistMessages.php">Messages</a></li>
        <li><a href="pharmacistLogout.php" style="color: red;">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2 class="text-center">🧑 Your Client Details</h2>
    <hr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($patient = $result->fetch_assoc()): ?>
            <div class="details-card">
                <h4>Patient Information</h4>
                <p><strong>Name:</strong> <?= htmlspecialchars($patient['PatientName']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($patient['Email']); ?></p>
                <p><strong>Age:</strong> <?= htmlspecialchars($patient['Age']); ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($patient['Gender']); ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($patient['TeleNo']); ?></p>

                <hr>

                <h4>Prescription Details</h4>
                <p><strong>Date:</strong> <?= date("F j, Y", strtotime($patient['Date'])); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">No prescription data found for you.</p>
    <?php endif; ?>

    <br>
    <button class="btn-back" onclick="window.history.back()">🔙 Back</button>
</div>

<!-- Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
