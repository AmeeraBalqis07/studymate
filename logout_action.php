<?php
// Start the session to access session variables
session_start();

// Destroy the session to log out the user
session_destroy();

// Redirect the user to the login page after logout
header('Location: login.php');
exit;
?>
