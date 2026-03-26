<?php
session_start();

// Destroy all session data to log out the user
session_unset();
session_destroy();

// Redirect to login page after logout
header("Location: login.php");
exit(); // Don't forget to exit after header redirection
?>
