<?php
session_start();
$_SESSION['loggedin'] = false;
session_destroy(); // Destroy all session data
header("Location: AdminLogin.php"); // Redirect to the homepage
exit();
?>
