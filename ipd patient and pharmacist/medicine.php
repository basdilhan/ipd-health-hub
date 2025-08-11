<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch unique locations
$location_query = "SELECT DISTINCT Location FROM pharmacist";
$location_result = mysqli_query($con, $location_query);

// Handle AJAX request to fetch pharmacies based on location
if (isset($_POST['fetch_pharmacies'])) {
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $pharmacy_query = "SELECT Name FROM pharmacist WHERE Location = '$location'";
    $pharmacy_result = mysqli_query($con, $pharmacy_query);
    $pharmacies = [];
    while ($row = mysqli_fetch_assoc($pharmacy_result)) {
        $pharmacies[] = $row['Name'];
    }
    echo json_encode($pharmacies);
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['fetch_pharmacies'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $teleno = mysqli_real_escape_string($con, $_POST['teleno']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $pharmacy = mysqli_real_escape_string($con, $_POST['pharmacy']);
    $prescription_file = $_FILES['prescription']['name'];

    // Upload prescription file
    $target_dir = "photos/";
    $target_file = $target_dir . basename($_FILES["prescription"]["name"]);
    move_uploaded_file($_FILES["prescription"]["tmp_name"], $target_file);

    // Insert patient data
    $insert_patient = "INSERT INTO patient (Name, Email, Age, Gender, TeleNo) VALUES ('$name', '$email', '$age', '$gender', '$teleno')";
    mysqli_query($con, $insert_patient);
    $pid = mysqli_insert_id($con);

    // Get pharmacist ID
    $pharmacist_query = "SELECT PharmacistID FROM pharmacist WHERE Name = '$pharmacy'";
    $pharmacist_result = mysqli_query($con, $pharmacist_query);
    $pharmacist = mysqli_fetch_assoc($pharmacist_result);
    $pharmacist_id = $pharmacist['PharmacistID'];

    // Insert prescription data with the current date
    $current_date = date("Y-m-d"); 
    $insert_prescription = "INSERT INTO prescription (PID, PharmacistID, Date, Image) VALUES ('$pid', '$pharmacist_id', '$current_date', '$target_file')";
    $ret=mysqli_query($con, $insert_prescription);

    echo "<script>alert('Prescription uploaded successfully!');</script>";
    if ($ret ==1)
     {
        $spath="photos/".$_FILES["prescription"]["name"];
        move_uploaded_file($_FILES["prescription"]["tmp_name"],$spath);
    }
}
else
{
   
}
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Prescription - IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
        body {
    background: url('preBack.jpg') no-repeat center center fixed;
    background-size: cover;
    padding-top: 70px;
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
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin-top: 80px;
        }
        h2 {
            color: #008b8b;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group select,
        .form-group input {
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #008b8b;
            font-size: 18px;
            width: 100%;
            margin-bottom: 15px;
            transition: all 0.3s ease-in-out;

        } select.form-control {
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
        .form-group select:focus,
        .form-group input:focus {

            border-color: #32e0c4;
            box-shadow: 0 0 12px rgba(50, 224, 196, 0.4);
        }
        .btn-primary {
            background: #32e0c4;
            color: white;
            font-weight: bold;
            padding: 15px;
            border: none;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background: #008b8b;
            transform: scale(1.05);
        }
        .btn-secondary {
            background: #ddd;
            color: black;
            font-weight: bold;
            padding: 15px;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s ease-in-out;
        }
        .btn-secondary:hover {
            background: #bbb;
            transform: scale(1.05);
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
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="index.php">IPD Health Hub
        <span class="nav__logo"><img src="mm.ICO" alt="logo" class="logo-white" /></span></a>
    </nav><div class="container">
        <h2>Patient Details</h2>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Age:</label>
                <input type="number" name="age" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Gender:</label>
                <select name="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label>Contact Number:</label>
                <input type="text" name="teleno" class="form-control" required>
            </div>
            
    <div class="container">
        <h2>Upload Prescription</h2>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Select Location:</label>
                <select id="location" name="location" class="form-control" required>
                    <option value="" disabled selected>Select a location</option>
                    <?php while ($row = mysqli_fetch_assoc($location_result)) { ?>
                        <option value="<?php echo $row['Location']; ?>"> <?php echo $row['Location']; ?> </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Select Nearest Pharmacy:</label>
                <select id="pharmacy" name="pharmacy" class="form-control" required>
                    <option value="" disabled selected>Select a pharmacy</option>
                </select>
            </div>
             <div class="form-group">
                <label>Upload Prescription File:</label>
                <input type="file" name="prescription" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#location').change(function() {
                let location = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: window.location.href,
                    data: { fetch_pharmacies: 1, location: location },
                    success: function(response) {
                        let pharmacies = JSON.parse(response);
                        let pharmacyDropdown = $('#pharmacy');
                        pharmacyDropdown.empty();
                        pharmacyDropdown.append('<option value="" disabled selected>Select a pharmacy</option>');
                        pharmacies.forEach(function(pharmacy) {
                            pharmacyDropdown.append('<option value="' + pharmacy + '">' + pharmacy + '</option>');
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
