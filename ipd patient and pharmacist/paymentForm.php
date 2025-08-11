<?php
session_start();
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Validate Payment Data from `POST`
if (!isset($_POST['appointment_id']) || !isset($_POST['patient_id']) || !isset($_POST['amount'])) {
    die("<div class='alert alert-danger text-center'>❌ Invalid Payment Request</div>");
}

$appointment_id = intval($_POST['appointment_id']);
$patient_id = intval($_POST['patient_id']);
$payment_amount = floatval($_POST['amount']); // Retrieve amount from appointmentDetails.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Process | IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: url('payment.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1;
        }

        .payment-container {
            background: rgba(255, 255, 255, 0.97);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 420px;
            position: relative;
            z-index: 2;
        }

        .btn-pay {
            background: linear-gradient(to right, #28a745, #218838);
            color: white;
            padding: 14px;
            width: 100%;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 4px 10px rgba(40, 167, 69, 0.3);
        }

        .btn-pay:hover {
            background: linear-gradient(to right, #218838, #1e7e34);
            transform: scale(1.02);
        }

        .payment-heading {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
        }

        .card-input {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            padding-left: 50px;
            border: 2px solid #ddd;
            background: rgba(255, 255, 255, 0.9);
        }

        .card-input:focus {
            border-color: #28a745;
            box-shadow: 0px 0px 10px rgba(40, 167, 69, 0.3);
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2 class="payment-heading">Complete Your Payment</h2>
        <p><strong>Amount to Pay:</strong> LKR <?= number_format($payment_amount, 2); ?></p>

        <form action="paymentProcess.php" method="POST">
            <input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>">
            <input type="hidden" name="patient_id" value="<?= $patient_id; ?>">
            <input type="hidden" name="amount" value="<?= $payment_amount; ?>">
            
           

          
        </form>
    </div>
</body>
</html>
