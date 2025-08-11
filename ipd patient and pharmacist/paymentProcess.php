<?php
session_start();

// Database connection
$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Validate data from URL (GET) or form submission (POST)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['appointment_id']) || !isset($_GET['patient_id']) || !isset($_GET['amount'])) {
        die("<div class='alert alert-danger text-center'>❌ Invalid Payment Request</div>");
    }
    $appointment_id = intval($_GET['appointment_id']);
    $patient_id = intval($_GET['patient_id']);
    $amount = intval($_GET['amount']);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['appointment_id']) || !isset($_POST['patient_id']) || !isset($_POST['amount'])) {
        die("<div class='alert alert-danger text-center'>❌ Invalid Payment Request</div>");
    }
    $appointment_id = intval($_POST['appointment_id']);
    $patient_id = intval($_POST['patient_id']);
    $amount = intval($_POST['amount']);
} else {
    die("<div class='alert alert-danger text-center'>❌ Invalid Request Method</div>");
}

// Process payment if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_number'])) {
    if (!isset($_POST['card_number']) || !isset($_POST['holder_name']) || !isset($_POST['cvv']) || !isset($_POST['expire_date'])) {
        echo "<div class='alert alert-danger text-center'>❌ Invalid Payment Data</div>";
    } else {
        $card_number = $_POST['card_number'];
        $holder_name = $_POST['holder_name'];
        $cvv = intval($_POST['cvv']);
        $expire_date = $_POST['expire_date']; // Format: YYYY-MM

        // Insert Payment into Database
        $query = "INSERT INTO payment (Amount, ExpireDate, CardNum, HolderName, CVV, AppointmentID) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("isssii", $amount, $expire_date, $card_number, $holder_name, $cvv, $appointment_id);

        if ($stmt->execute()) {
            header("Location: paymentSuccess.php?status=success");
            exit();
        } else {
            echo "<div class='alert alert-danger text-center'>❌ Payment Failed. Try Again.</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Process | IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
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

        /* Dark Overlay for Better Visibility */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Black overlay with 50% opacity */
            backdrop-filter: blur(5px); /* Blur effect */
            z-index: 1;
        }

        /* Payment Container */
        .payment-container {
            background: rgba(255, 255, 255, 0.97);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 420px;
            position: relative;
            z-index: 2;
        }

        /* Input Fields with Icons */
        .form-group {
            position: relative;
            margin-bottom: 15px;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
        }

        .form-control {
            padding-left: 40px;
            height: 50px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ddd;
            background: rgba(255, 255, 255, 0.8);
            transition: 0.3s ease-in-out;
        }

        /* Input Hover & Focus Effects */
        .form-control:hover,
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0px 0px 10px rgba(40, 167, 69, 0.3);
        }

        /* Payment Button */
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

        /* Heading */
        .payment-heading {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2 class="payment-heading">Complete Your Payment</h2>
        <p><strong>Amount:</strong> LKR <?= number_format($amount, 2); ?></p>

        <form action="#" method="POST">
            <input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>">
            <input type="hidden" name="patient_id" value="<?= $patient_id; ?>">
            <input type="hidden" name="amount" value="<?= $amount; ?>">
            
            <div class="form-group">
                <i class="fas fa-credit-card"></i>
                <input type="text" name="card_number" id="card_number" 
                       class="form-control card-input" 
                       placeholder="XXXX XXXX XXXX XXXX" 
                       maxlength="19" required>
            </div>

            <script>
                document.getElementById('card_number').addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, ''); // Remove all non-numeric characters
                    value = value.replace(/(\d{4})/g, '$1 ').trim(); // Add space every 4 digits
                    e.target.value = value.substring(0, 19); // Max length 19 including spaces
                });
            </script>

            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" name="holder_name" class="form-control" placeholder="Card Holder Name" required maxlength="50">
            </div>

            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="number" name="cvv" class="form-control" placeholder="CVV" required maxlength="3">
            </div>

            <div class="form-group">
                <i class="fas fa-calendar-alt"></i>
                <input type="month" name="expire_date" class="form-control" required>
            </div>

            <button type="submit" class="btn-pay">Make Payment</button>
        </form>
    </div>
</body>
</html>

<?php
// Close database connection
$con->close();
?>