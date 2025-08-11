<?php
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

if (!$con) {
    die("Database connection failed: " . $con->connect_error);
}

if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($con, $_POST['search']);
    $query = "SELECT DID, Name, Speciality FROM doctor WHERE Name LIKE '%$search%' ORDER BY Name ASC";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='doctor-option' data-id='{$row['DID']}'>{$row['Name']} - {$row['Speciality']}</div>";
        }
    } else {
        echo "<div>No doctors found</div>";
    }
}

mysqli_close($con);
?>
