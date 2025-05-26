<?php
// Include session management
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
$session::ensureSessionStarted();
$session::destroy();

echo '<script> window.location.href = "login.php";</script>';
exit();
?>

<!-- End -->