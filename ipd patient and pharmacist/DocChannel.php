<?php
session_start();

// Database connection
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Fetch hospitals from the database with location
$hospital_query = "SELECT HospitalID, Name, Location FROM hospital";
$hospital_result = mysqli_query($con, $hospital_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Channeling - IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
      <style>
       body {
          background: url('SignBack.jpg');
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
       .form-container {
           background-color: rgba(255, 255, 255, 0.95);
           padding: 30px;
           border-radius: 15px;
           box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
           max-width: 700px;
           margin: 50px auto;
           width: 90%;
       }
       .form-title {
           margin-bottom: 30px;
           font-size: 28px;
           color: #008b8b;
           font-weight: bold;
           text-align: center;
       }
       .form-group label {
           font-weight: bold;
           color: #555;
       }
       .form-control {
           width: 100% !important;
           height: 50px;
           padding: 12px;
           font-size: 18px;
           border-radius: 8px;
           border: 1px solid #ddd;
           transition: all 0.3s ease-in-out;
           overflow: hidden;
           text-overflow: ellipsis;
       }
       select.form-control {
           width: 100%;
           height: auto;
           min-height: 50px;
           font-size: 18px;
           border-radius: 8px;
           border: 1px solid #ddd;
       }
       select option {
           white-space: normal !important;
           font-size: 16px;
           padding: 10px;
       }
       .btn-primary {
           background-color: #008b8b;
           border-color: #008b8b;
           border-radius: 30px;
           padding: 12px 30px;
           font-size: 18px;
           font-weight: bold;
           color: #fff;
           width: 100%;
           transition: background-color 0.3s ease;
       }
       .btn-primary:hover {
           background-color: #006060;
       }
       .footer {
           background-color: #008b8b;
           color: white;
           padding: 15px 0;
           text-align: center;
           position: fixed;
           bottom: 0;
           width: 100%;
           box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
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

    <!-- Navbar -->
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

    <div class="container form-container">
        <h2 class="form-title">Channel Your Doctor</h2>
        
     <!-- Search Form -->
<form action="scheduleAppointments.php" method="POST">
    <div class="form-group search-container">
        <label for="doctor_search">Search Doctor</label>
        <input type="text" class="form-control" id="doctor_search" placeholder="Type doctor's name..." required>
        <div class="search-results" id="search_results"></div>
        <input type="hidden" id="doctor_id" name="doctor_id">
    </div>

    <div class="form-group">
        <label for="hospital_id">Select Hospital</label>
        <select class="form-control" id="hospital_id" name="hospital_id" required>
            <option value="" disabled selected>Choose Your Hospital</option>
            <?php while ($hospital = mysqli_fetch_assoc($hospital_result)): ?>
                <option value="<?= $hospital['HospitalID']; ?>">
                    <?= $hospital['Name'] . " - " . $hospital['Location']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="date">Select Date (Optional)</label>
       <input type="date" class="form-control" id="date" name="date" min="">

    </div>

    <button type="submit" name="btnSearch" class="btn btn-primary">Search</button>
</form>

</div>

<script>
      const today = new Date().toISOString().split('T')[0];
        document.getElementById("date").setAttribute("min", today);
        
    $(document).ready(function() {
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

        $(document).on("click", ".doctor-option", function() {
            let doctorId = $(this).data("id");
            let doctorName = $(this).text();
            $("#doctor_search").val(doctorName);
            $("#doctor_id").val(doctorId);
            $("#search_results").hide();
        });
    });
</script>
</body>
</html>
