<?php
// Include session management
include_once "../class-file/SessionManager.php";

// Create a session object
$session = SessionStatic::class;
$session::destroy(); // Destroy the session
// Redirect to login page
echo '<script> window.location.href = "login.php";</script>';
exit(); // Always call exit after header redirection
?>

<!-- End -->