<?php
session_start();
require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

// Check connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
// Handle Add Clinic (Schedule a Clinic)
if (isset($_POST['btnAdd'])) { 
    $hospital_id = $_POST['hospital_id'];
    $doctor_id = $_POST['doctor_id'];
    $room_number = $_POST['room_number'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $max_appointments = $_POST['max_appointments'];

    // Fetch Doctor's Email
    $doctor_query = "SELECT Email FROM doctor WHERE DID = '$doctor_id'";
    $doctor_result = mysqli_query($con, $doctor_query);
    $doctor_data = mysqli_fetch_assoc($doctor_result);
    $doctor_email = $doctor_data['Email'] ?? '';

    // Fetch Hospital Name
    $hospital_query = "SELECT Name FROM hospital WHERE HospitalID = '$hospital_id'";
    $hospital_result = mysqli_query($con, $hospital_query);
    $hospital_data = mysqli_fetch_assoc($hospital_result);
    $hospital_name = $hospital_data['Name'] ?? 'Unknown Hospital';

    if (empty($doctor_email) || !filter_var($doctor_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Error: Invalid or missing doctor email. Please select a valid doctor.');</script>";
    } else {
        // Insert Schedule into Database
        $query = "INSERT INTO schedule (HospitalID, DID, RoomNo, Date, Time, MaxAppointments) 
                  VALUES ('$hospital_id', '$doctor_id', '$room_number', '$date', '$time', '$max_appointments')";

        if (mysqli_query($con, $query)) {
            // Prepare Email Content with Hospital Name
            $subject = "New Clinic Schedule - IPD Health Hub";
            $message = "Dear Doctor,\n\n";
            $message .= "You have been scheduled for a clinic session at **$hospital_name**.\n\n";
            $message .= "📅 Date: $date\n";
            $message .= "⏰ Time: $time\n";
            $message .= "🏥 Hospital: $hospital_name\n";
            $message .= "🏠 Room No: $room_number\n";
            $message .= "🔢 Max Appointments: $max_appointments\n\n";
            $message .= "Please be prepared for your upcoming session.\n\n";
            $message .= "Best regards,\nIPD Health Hub";

            // Send Email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = "ipdhealthhub@gmail.com";   // Replace with your Gmail
                $mail->Password = "oaqq uaeq xjll jqfs";
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('your-email@gmail.com', 'IPD Health Hub');
                $mail->addAddress($doctor_email);
                $mail->Subject = $subject;
                $mail->Body = $message;

                if ($mail->send()) {
                    echo "<script>alert('Clinic scheduled successfully! Email sent to the doctor.');</script>";
                } else {
                    echo "<script>alert('Clinic scheduled, but email could not be sent.');</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('Email sending failed: " . $mail->ErrorInfo . "');</script>";
            }
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
        }
    }
}




// Handle Edit Clinic
if (isset($_POST['btnEdit'])) {
    $schedule_id = $_POST['schedule_id'];
    $room_number = $_POST['room_number'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $max_appointments = $_POST['max_appointments'];

    // Fetch Doctor and Hospital Details
    $fetch_query = "
        SELECT s.DID, s.HospitalID, d.Email AS DoctorEmail, d.Name AS DoctorName, h.Name AS HospitalName
        FROM schedule s
        JOIN doctor d ON s.DID = d.DID
        JOIN hospital h ON s.HospitalID = h.HospitalID
        WHERE s.ScheduleID = ?";
    
    $stmt = mysqli_prepare($con, $fetch_query);
    mysqli_stmt_bind_param($stmt, "i", $schedule_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $schedule_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($schedule_data) {
        $doctor_email = $schedule_data['DoctorEmail'];
        $doctor_name = $schedule_data['DoctorName'];
        $hospital_name = $schedule_data['HospitalName'];

        if (empty($doctor_email) || !filter_var($doctor_email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Error: Invalid or missing doctor email. Please check the doctor details.');</script>";
        } else {
            // Update Schedule in Database
            $query = "UPDATE schedule 
                      SET RoomNo = ?, Date = ?, Time = ?, MaxAppointments = ?
                      WHERE ScheduleID = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "issii", $room_number, $date, $time, $max_appointments, $schedule_id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // Prepare Email Content
                $subject = "Updated Clinic Schedule - IPD Health Hub";
                $message = "Dear Dr. $doctor_name,\n\n";
                $message .= "Your clinic session details have been **updated** at **$hospital_name**.\n\n";
                $message .= "📅 **New Date:** $date\n";
                $message .= "⏰ **New Time:** $time\n";
                $message .= "🏥 **Hospital:** $hospital_name\n";
                $message .= "🏠 **Room No:** $room_number\n";
                $message .= "🔢 **Max Appointments:** $max_appointments\n\n";
                $message .= "Please review your updated schedule.\n\n";
                $message .= "Best regards,\nIPD Health Hub";

                // Send Email using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = "ipdhealthhub@gmail.com";  // Replace with your Gmail
                    $mail->Password = "oaqq uaeq xjll jqfs";  // Replace with your Gmail App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('your-email@gmail.com', 'IPD Health Hub');
                    $mail->addAddress($doctor_email);
                    $mail->Subject = $subject;
                    $mail->Body = $message;

                    if ($mail->send()) {
                        echo "<script>alert('Clinic details updated successfully! Email sent to the doctor.');</script>";
                    } else {
                        echo "<script>alert('Clinic details updated, but email could not be sent.');</script>";
                    }
                } catch (Exception $e) {
                    echo "<script>alert('Email sending failed: " . $mail->ErrorInfo . "');</script>";
                }
            } else {
                echo "<script>alert('Error updating clinic details: " . mysqli_error($con) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Error fetching doctor and hospital details.');</script>";
    }
}

    

// Handle Delete Clinic
if (isset($_POST['btnDelete'])) {
    $schedule_id = $_POST['schedule_id'];

    // Fetch clinic details before deletion
    $query = "SELECT * FROM schedule WHERE ScheduleID = '$schedule_id'";
    $result = mysqli_query($con, $query);
    $clinic = mysqli_fetch_assoc($result);

    if ($clinic) {
        // Proceed with deletion
        $delete_query = "DELETE FROM schedule WHERE ScheduleID = '$schedule_id'";
        if (mysqli_query($con, $delete_query)) {
            echo "<script>alert('Clinic deleted successfully!'); window.location.href='manageAppointments.php';</script>";
        } else {
            echo "<script>alert('Error deleting clinic: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Clinic not found!');</script>";
    }
}

// Fetch Hospitals, Doctors & Scheduled Clinics
$hospital_result = mysqli_query($con, "SELECT HospitalID, Name, Location FROM hospital");
$doctor_result = mysqli_query($con, "SELECT DID, Name FROM doctor");
$schedule_result = mysqli_query($con, "
    SELECT s.*, h.Name AS HospitalName, h.Location 
    FROM schedule s
    JOIN hospital h ON s.HospitalID = h.HospitalID
");

// Fetch all schedule data into an array
$schedules = [];
while ($schedule = mysqli_fetch_assoc($schedule_result)) {
    $schedules[] = $schedule;
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clinics</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <script>
        function showForm(formType) {
            document.getElementById('addForm').style.display = (formType === 'add') ? 'block' : 'none';
            document.getElementById('editForm').style.display = (formType === 'edit') ? 'block' : 'none';
            document.getElementById('deleteForm').style.display = (formType === 'delete') ? 'block' : 'none';
        }
    </script>
    <style type="text/css">
           body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0;
            display: flex;
        }

    /* General Sidebar Styling */
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



/* Mobile Responsive */
@media (max-width: 768px) {
    .sidebar {
        width: 60px;
    }

    .sidebar:hover {
        width: 200px;
    }

    .sidebar ul li a {
        font-size: 0;
    }

    .sidebar:hover ul li a {
        font-size: 16px;
    }
}

        .main-content {
            margin-left: 270px;
            padding: 40px;
            width: 100%;
        }

        .container {
            background: white;
            padding: 30px;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-group {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn {
            background: #32e0c4;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn:hover {
            background: #26c2aa;
        }

        .form-container {
            display: none;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-top: 5px;
            font-size: 16px;
        }

        .submit-btn {
            width: 100%;
            background: #32e0c4;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }

        .submit-btn:hover {
            background: #26c2aa;
        }       .search-container {
           position: relative;
       }
       .search-results {
           position: absolute;
           width: 100%;
           background: white;
           border: 1px solid #ddd;
           max-height: 200px;
           overflow-y: auto;
           z-index: 10;
       }
       .search-results div {
           padding: 10px;
           cursor: pointer;
           transition: background 0.3s;
       }
       .search-results div:hover {
           background: #f0f0f0;
       }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="Adminpanel.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="#"><i class="fas fa-clinic-medical"></i>Manage Clinics</a></li>
            <li><a href="ManageRooms.php"><i class="fas fa-bed"></i>Room Availability</a></li>
            <li><a href="doctors.php"><i class="fas fa-user-md"></i>Manage Doctors</a></li>
            <li><a href="Addadmin.php"><i class="fas fa-user-plus"></i>Manage Admins</a></li>
            <li><a href="ViewReservations.php"><i class="fas fa-calendar-check"></i>View Reservations</a></li>
            <li><a href="ViewAppointments.php"><i class="fas fa-calendar-alt"></i>View Appointments</a></li>
            <li><a href="ViewReports.php"><i class="fas fa-chart-line"></i>View Reports</a></li> <!-- Added View Reports -->
            
        </ul>
    </div>

    <div class="main-content">
        <h1>Manage Clinics</h1>

        <div class="btn-group">
            <button class="btn" onclick="showForm('add')">➕ Add Clinic</button>
            <button class="btn" onclick="showForm('edit')">✏️ Edit Clinic</button>
            <button class="btn" onclick="showForm('delete')">❌ Delete Clinic</button>
        </div>

      <div id="addForm" class="container form-container">
    <h2>Schedule a Clinic</h2>
    <form method="POST">
        <label>Hospital:</label>
        <select name="hospital_id">
            <?php while ($hospital = mysqli_fetch_assoc($hospital_result)) { ?>
                <option value="<?= $hospital['HospitalID'] ?>">
                    <?= $hospital['Name'] ?> - <?= $hospital['Location'] ?>
                </option>
            <?php } ?>
        </select>

        <div class="form-group search-container">
            <label for="doctor_search">Search Doctor</label>
            <input type="text" class="form-control" id="doctor_search" placeholder="Type doctor's name...">
            <div class="search-results" id="search_results"></div>
            <input type="hidden" id="doctor_id" name="doctor_id">
            <input type="hidden" id="doctor_email" name="doctor_email"> <!-- Hidden field for doctor's email -->
        </div>

        <label>Room Number:</label>
        <input type="number" name="room_number" required>

        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Time:</label>
        <input type="time" name="time" required>

        <label>Max Appointments:</label>
        <input type="number" name="max_appointments" min="1" required>

        <button type="submit" name="btnAdd" class="submit-btn">Add Clinic</button>
    </form>
</div>

        <!-- Edit Clinic Form -->
        <div id="editForm" class="container form-container">
            <h2>Edit Clinic Details</h2>
            <form method="POST">
                <label>Select Clinic:</label>
                <select name="schedule_id" required>
                    <option value="">Select a Clinic</option>
                    <?php foreach ($schedules as $schedule) { ?>
                       <option 
    value="<?= $schedule['ScheduleID'] ?>"
    data-room="<?= $schedule['RoomNo'] ?>"
    data-date="<?= $schedule['Date'] ?>"
    data-time="<?= $schedule['Time'] ?>"
    data-max="<?= $schedule['MaxAppointments'] ?>"
>

                            Room <?= $schedule['RoomNo'] ?> - <?= $schedule['Date'] ?> at <?= $schedule['Time'] ?> (<?= $schedule['HospitalName'] ?> - <?= $schedule['Location'] ?>)
                        </option>
                    <?php } ?>
                </select>

                <label>Room Number:</label>
                <input type="number" name="room_number" id="room_number" required>

                <label>Date:</label>
                <input type="date" name="date" id="date" required>

                <label>Time:</label>
                <input type="time" name="time" id="time" required>

                <label>Max Appointments:</label>
                <input type="number" name="max_appointments" id="max_appointments" min="1" required>

                <button type="submit" name="btnEdit" class="submit-btn">Update Clinic</button>
            </form>
        </div>

        <!-- Delete Clinic Form -->
        <div id="deleteForm" class="container form-container">
            <h2>Delete Clinic</h2>
            <form method="POST">
                <label>Select Clinic:</label>
                <select name="schedule_id" required>
                    <option value="">Select a Clinic</option>
                    <?php foreach ($schedules as $schedule) { ?>
                        <option value="<?= $schedule['ScheduleID'] ?>">
                            Room <?= $schedule['RoomNo'] ?> - <?= $schedule['Date'] ?> at <?= $schedule['Time'] ?> (<?= $schedule['HospitalName'] ?> - <?= $schedule['Location'] ?>)
                        </option>
                    <?php } ?>
                </select>

                <button type="submit" name="btnDelete" class="submit-btn" style="background: red;">Delete Clinic</button>
            </form>
        </div>
    </div>
     <script>
  $(document).ready(function() {
    // Search for doctors
    $("#doctor_search").on("keyup", function() {
        let query = $(this).val();
        if (query.length > 0) {
            $.ajax({
                url: "fetchDoctors.php",
                method: "POST",
                data: { search: query },
                success: function(response) {
                    $("#search_results").html(response).show();
                }
            });
        } else {
            $("#search_results").hide();
        }
    });

    // Handle doctor selection
    $(document).on("click", ".doctor-option", function() {
        let doctorId = $(this).data("id");
        let doctorName = $(this).text();
        $("#doctor_search").val(doctorName);
        $("#doctor_id").val(doctorId);
        $("#search_results").hide();

        // Fetch doctor's email
        $.ajax({
            url: "fetchDoctorEmail.php",
            method: "POST",
            data: { doctor_id: doctorId },
            success: function(response) {
                $("#doctor_email").val(response); // Set the doctor's email in the hidden field
            }
        });
    });
});
</script>
<script>
document.querySelector("select[name='schedule_id']").addEventListener("change", function() {
    const selectedOption = this.options[this.selectedIndex];

    // Get values from data attributes
    const room = selectedOption.getAttribute("data-room");
    const date = selectedOption.getAttribute("data-date");
    const time = selectedOption.getAttribute("data-time");
    const max = selectedOption.getAttribute("data-max");

    // Fill the fields
    document.getElementById("room_number").value = room;
    document.getElementById("date").value = date;
    document.getElementById("time").value = time;
    document.getElementById("max_appointments").value = max;
});
</script>


</body>
</html>