<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Fetch all hospitals with location
$hospital_query = "SELECT DISTINCT HospitalID, Name, Location FROM hospital";
$hospital_result = $con->query($hospital_query);

// Handle form submission for reserving a bed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve_bed'])) {
    $hospital_id = intval($_POST['hospital']);
    $room_id = intval($_POST['room']);
    $start_date = $_POST['start_date'];
    $patient_name = trim($_POST['name']);
    $patient_email = trim($_POST['email']);
    $patient_age = intval($_POST['age']);
    $patient_gender = trim($_POST['gender']);
    $patient_phone = trim($_POST['phone']);

    if (!empty($hospital_id) && !empty($room_id) && !empty($start_date) && !empty($patient_name) && !empty($patient_email) && !empty($patient_age) && !empty($patient_gender) && !empty($patient_phone)) {
        
        // ✅ Insert patient details into patient table
        $insert_patient = "INSERT INTO patient (Name, Email, Age, Gender, TeleNo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insert_patient);
        $stmt->bind_param("ssiss", $patient_name, $patient_email, $patient_age, $patient_gender, $patient_phone);
        $stmt->execute();
        $pid = $stmt->insert_id;
        $stmt->close();

        // ✅ Insert reservation into patient-room table (WITHOUT End Date)
        $insert_reservation = "INSERT INTO `patient-room`(PID, RoomID, StartDate) VALUES (?, ?, ?)";
        $stmt = $con->prepare($insert_reservation);
        $stmt->bind_param("iis", $pid, $room_id, $start_date);

        if ($stmt->execute()) {
            $stmt->close();

            // ✅ Update room availability to "Occupied"
            $update_room = "UPDATE room SET Availability = 'Occupied' WHERE RoomID = ?";
            $stmt = $con->prepare($update_room);
            $stmt->bind_param("i", $room_id);
            $stmt->execute();
            $stmt->close();

            // ✅ Redirect with PID in URL
            echo "<script>
                alert('✅ Bed Reserved Successfully for Patient ID: $pid!');
                window.location.href = 'BookingDetails.php?pid=$pid';
            </script>";
        } else {
            echo "<script>alert('❌ Error reserving bed. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('❌ All fields are required!');</script>";
    }
}

// Fetch available rooms when a hospital is selected
if (isset($_POST['fetch_rooms']) && isset($_POST['hospital_id'])) {
    $hospital_id = intval($_POST['hospital_id']);
    $query = "SELECT RoomID, RoomNumber FROM room WHERE HospitalID = ? AND Availability = 'Available'";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $rooms = '<option value="">Select a Room</option>';
    while ($row = $result->fetch_assoc()) {
        $rooms .= '<option value="'.$row['RoomID'].'">'.$row['RoomNumber'].'</option>';
    }
    echo $rooms;
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Reserving - IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
   <style>
    body {
        background: url('bedReserving.jpg') no-repeat center center fixed;
        background-size: cover;
        padding-top: 70px;
        font-family: 'Arial', sans-serif;
        color: #333;
    }

    .navbar {
        background-color: #008b8b;
    }

    .navbar-brand, .navbar-light .navbar-nav .nav-link {
        color: #fff;
    }

    .container {
        margin-top: 50px;
        background-color: #ffffff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .hidden {
        display: none;
    }

    h2 {
        color: #008b8b;
        margin-bottom: 20px;
        text-align: center;
    }

    h4 {
        color: #008b8b;
        margin-top: 20px;
        margin-bottom: 15px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        color: #333;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: #008b8b;
        box-shadow: 0 0 8px rgba(0, 139, 139, 0.3);
        outline: none;
    }

    .form-control::placeholder {
        color: #999;
    }

    .form-control[type="date"] {
        appearance: none;
        background-color: #fff;
    }

    .btn-primary {
        background-color: #008b8b;
        border: none;
        padding: 12px 25px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #006666;
    }

    .btn-primary:focus {
        box-shadow: 0 0 8px rgba(0, 139, 139, 0.3);
        outline: none;
    }

    /* Custom styles for select dropdown */
    .form-control.select {
        appearance: none;
        background: url('data:image/svg+xml;utf8,<svg fill="%23333" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>') no-repeat right 10px center;
        background-color: #fff;
        padding-right: 35px;
    }

    /* Style for error messages (optional) */
    .error-message {
        color: #ff0000;
        font-size: 14px;
        margin-top: 5px;
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
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h2>Bed Reserving</h2>
        <form id="bedReservingForm" method="POST" action="">
            <div class="form-group">
                <label for="hospital">Select Hospital</label>
              <select class="form-control" id="hospital" name="hospital" required>
    <option value="">Select a Hospital</option>
    <?php while ($hospital = $hospital_result->fetch_assoc()): ?>
        <option value="<?= $hospital['HospitalID']; ?>">
            <?= $hospital['Name']; ?> - <?= $hospital['Location']; ?>
        </option>
    <?php endwhile; ?>
</select>

            </div>

            <div class="form-group hidden" id="roomSection">
                <label for="room">Available Room Number</label>
                <select class="form-control" id="room" name="room" required>
                    <option value="">Select a Room</option>
                </select>
            </div>

            <h4>Patient Details</h4>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" class="form-control" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="start_date"> Date</label>
              
                  <input type="date" class="form-control" id="date" name="date" min=""  required>
            </div>
            <!--
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
             -->

            <button type="submit" name="reserve_bed" class="btn btn-primary">Reserve Bed</button>
        </form>
    </div>

    <!-- jQuery & AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
         const today = new Date().toISOString().split('T')[0];
        document.getElementById("date").setAttribute("min", today);
        
        $(document).ready(function(){
            $('#hospital').change(function(){
                var hospitalID = $(this).val();
                if(hospitalID) {
                    $.ajax({
                        type: "POST",
                        url: "bedReserving.php", // Ensure this is the correct file name
                        data: { fetch_rooms: 1, hospital_id: hospitalID },
                        success: function(response){
                            $('#room').html(response); // Update the room dropdown with the response
                            $('#roomSection').removeClass('hidden'); // Show the room dropdown
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: " + status + error); // Log any errors
                        }
                    });
                } else {
                    $('#roomSection').addClass('hidden'); // Hide the room dropdown if no hospital is selected
                }
            });
        });
    </script>
</body>
</html>
