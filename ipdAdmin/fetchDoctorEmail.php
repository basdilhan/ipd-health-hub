<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ipdhealthhub");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['doctor_id'])) {
    $doctor_id = $_POST['doctor_id'];
    $query = "SELECT Email FROM doctor WHERE DID = '$doctor_id'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['Email']; // Return the doctor's email
    } else {
        echo ""; // Return empty if no email found
    }
}
?>