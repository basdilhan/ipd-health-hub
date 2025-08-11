<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Validate Payment Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'], $_POST['patient_id'], $_POST['amount'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $patient_id = intval($_POST['patient_id']);
    $amount = floatval($_POST['amount']);
    $payment_type = "Local"; // Default payment method

    // ✅ Insert Payment Record
    $query = "INSERT INTO payment (Amount, Type, Date, AppointmentID) VALUES (?, ?, NOW(), ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("dsi", $amount, $payment_type, $appointment_id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ You need to make Payment Locally details are correct!'); window.location.href='Index.php';</script>";
    } else {
        echo "<script>alert('❌ Payment details Failed. Try again.'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('❌ Invalid Payment Request.'); window.history.back();</script>";
}

$con->close();
?>
