<?php
session_start();
// Ensure pharmacist is logged in
if (!isset($_SESSION['pharmacist_id'])) {
    header("Location: pharmacistSignup.php");
    exit();
}

$pharmacist_id = $_SESSION['pharmacist_id'];

$con = new mysqli("localhost", "root", "", "ipdhealthhub");

if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Fetch Prescription Data from the Database for the logged-in pharmacist
$prescription_query = "
    SELECT p.PreID, p.Image, pa.Name AS PatientName, pa.Email
    FROM prescription p
    JOIN patient pa ON p.PID = pa.PID
    WHERE p.PharmacistID = ?
";
$stmt = $con->prepare($prescription_query);

if (!$stmt) {
    die("Error preparing query: " . $con->error);
}

$stmt->bind_param("i", $pharmacist_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$prescription_result = $stmt->get_result();

if (!$prescription_result) {
    die("Error fetching prescriptions: " . $con->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Dashboard - IPD Health Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
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
            width: 100%;
        }

        .section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .section h2 {
            color: #008b8b;
            font-size: 22px;
            margin-bottom: 15px;
        }

        /* Prescription Section */
        .prescription-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .prescription-card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 220px;
        }

        .prescription-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }

        .prescription-card p {
            font-size: 14px;
            color: #555;
            margin-top: 10px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
        }

        .btn-view {
            background: #32e0c4;
            color: white;
            margin-top: 10px;
            display: block;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            padding: 8px;
        }

        .btn-view:hover {
            background: #008b8b;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Pharmacist Panel</h2>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="ViewClientDetails.php">View Clients Details</a></li>
            <li><a href="pharmacistMessages.php">Messages</a></li>
            <li><a href="pharmacistLogout.php" style="color: red;">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- View Prescription Images -->
        <div class="section">
            <h2>📜 Prescription Images</h2>
            <div class="prescription-container">
                <?php if ($prescription_result->num_rows > 0): ?>
                    <?php while ($prescription = $prescription_result->fetch_assoc()): ?>
                        <div class="prescription-card">
                            <?php if (!empty($prescription['Image'])): ?>
                                <img src="<?= htmlspecialchars($prescription['Image']); ?>" alt="Prescription">
                            <?php else: ?>
                                <p>No image available</p>
                            <?php endif; ?>
                            <p><strong>Patient:</strong> <?= htmlspecialchars($prescription['PatientName']); ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($prescription['Email']); ?></p>
                            <a href="<?= htmlspecialchars($prescription['Image']); ?>" target="_blank" class="btn btn-view">View Full</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No prescriptions found.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

</body>
</html>
