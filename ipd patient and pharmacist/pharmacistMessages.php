<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Ensure PHPMailer is installed

$con = new mysqli("localhost", "root", "", "ipdhealthhub");

// Ensure pharmacist is logged in
if (!isset($_SESSION['pharmacist_id'])) {
    header("Location: pharmacistSignup.php");
    exit();
}

$pharmacist_id = $_SESSION['pharmacist_id'];
$pharmacist_email = "";
$pharmacist_name = "";
$pharmacist_teleno = "";

// Fetch pharmacist details (Name, Email, TeleNo) from the database
$query = "SELECT Name, Email, TeleNo FROM pharmacist WHERE PharmacistID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $pharmacist_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $pharmacist_email = $row['Email'];
    $pharmacist_name = $row['Name'];
    $pharmacist_teleno = $row['TeleNo'];
}
$stmt->close();

// Generate subject with pharmacist's name
$default_subject = "Message from $pharmacist_name - IPD Health Hub";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendEmail'])) {
    $recipient_email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message_body = trim($_POST['message']);

    // Append pharmacist contact details to the message
    $message = $message_body . "\n\n---\n📞 Contact: $pharmacist_teleno\n📧 Email: $pharmacist_email";

    if (!empty($recipient_email) && !empty($subject) && !empty($message)) {
        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "ipdhealthhub@gmail.com"; 
            $mail->Password = "oaqq uaeq xjll jqfs"; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email Details
            $mail->setFrom($pharmacist_email, "Pharmacist - IPD Health Hub");
            $mail->addAddress($recipient_email);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Send Email
            if ($mail->send()) {
                echo "<script>alert('📩 Message sent successfully!'); window.location.href='pharmacistMessages.php';</script>";
            } else {
                echo "<script>alert('❌ Error sending message.');</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('❌ Email error: " . $mail->ErrorInfo . "');</script>";
        }
    } else {
        echo "<script>alert('❌ All fields are required!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist - Send Message</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
            width: calc(100% - 270px);
        }

        .message-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-send {
            background: #008b8b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
        }

        .btn-send:hover {
            background: #005f5f;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Pharmacist Panel</h2>
    <ul>
        <li><a href="pharmacistDashboard.php">Home</a></li>
        <li><a href="ViewClientDetails.php">View Client Details</a></li>
        <li><a href="#">Messages</a></li>
        <li><a href="pharmacistLogout.php" style="color: red;">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2 class="text-center">📩 Send Message to Client</h2>
    <hr>

    <div class="message-form">
        <form method="POST">
            <div class="form-group">
                <label for="email">Client Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Client's Email" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" value="<?= htmlspecialchars($default_subject); ?>" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Type your message here..." required></textarea>
            </div>
            <button type="submit" name="sendEmail" class="btn-send">📧 Send Message</button>
        </form>
    </div>
</div>

</body>
</html>