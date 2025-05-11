<?php
// Include session management
include_once "../class-file/session.php";

// Create a session object
$session = new SessionManager();

// Destroy the session
$session->destroy(); // assuming you have a method that handles session destruction

// Redirect to login page
echo '<script> window.location.href = "login.php";</script>';
exit(); // Always call exit after header redirection
?>

<!-- End -->