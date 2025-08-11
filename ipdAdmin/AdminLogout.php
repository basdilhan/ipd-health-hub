<?php
session_start();
$_SESSION['loggedin'] = false;
session_destroy(); // Destroy all session data
header("Location: index.php"); // Redirect to the homepage
exit();
?>
