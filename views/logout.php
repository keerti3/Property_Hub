<?php
session_start(); // Start the session
session_destroy(); // Destroy all session data
header("Location: home.php"); // Redirect to the login page
exit();
?>