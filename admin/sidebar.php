<?php
// Include session management
include_once "../class-file/session.php";
$session = new SessionManager();


// Check already logged in or not
if ($session->exists("user") == false) {
    echo '<script> window.location.href = "login.php";</script>';
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>portfolio</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/jquery-ui.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">

    <link rel="stylesheet" href="../css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="../fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="../css/aos.css">

    <link rel="stylesheet" href="../css/style.css">


</head>

<body>

    <!-- Sidebar Start -->
    <nav id="sidebar" class="sidebar bg-dark text-white">
        <div class="sidebar-header text-center py-4">
            <h4>Admin Panel</h4>
        </div>
        <ul class="list-unstyled components">
            <li><a href="#" class="text-white">Admin</a></li>
            <li><a href="profile.php" class="text-white">Profile</a></li>
            <li><a href="pending-registration.php" class="text-white">Pending Registration</a></li>
            <li><a href="user-management.php" class="text-white">User Management</a></li>
            <li><a href="course-management.php" class="text-white">Course Management</a></li>
            <li><a href="create-course.php" class="text-white">Create Course</a></li>
            <li><a href="research-management.php" class="text-white">Research Management</a></li>
            <li><a href="create-research.php" class="text-white">Create Research</a></li>
            <li><a href="project-management.php" class="text-white">Project Management</a></li>
            <li><a href="create-project.php" class="text-white">Create Project</a></li>
            <li><a href="research-lab-management.php" class="text-white">Research Lab Management</a></li>
            <li><a href="create-research-lab.php" class="text-white">Create Research Lab</a></li>
        </ul>
        <ul class="list-unstyled components">
            <li><a href="logout.php" class="text-white">Logout</a></li>
        </ul>
    </nav>
    <!-- Sidebar End -->

    <!-- Main Content Start -->
    <div id="content" class="flex-grow-1">
        <!-- Toggle Button for Sidebar -->
        <div class="toggle-btn d-lg-none  toggle-bg text-white">
            <button id="sidebarToggle" class="btn btn-sm btn-light">☰ Menu</button>
        </div>
        <!-- </div> -->









        <script src="../js/jquery-3.3.1.min.js"></script>
        <script src="../js/jquery-migrate-3.0.1.min.js"></script>
        <script src="../js/jquery-ui.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/owl.carousel.min.js"></script>
        <script src="../js/jquery.stellar.min.js"></script>
        <script src="../js/jquery.countdown.min.js"></script>
        <script src="../js/bootstrap-datepicker.min.js"></script>
        <script src="../js/jquery.easing.1.3.js"></script>
        <script src="../js/aos.js"></script>
        <script src="../js/jquery.fancybox.min.js"></script>
        <script src="../js/jquery.sticky.js"></script>


        <script src="../js/main.js"></script>



        <script>
            // Toggle Sidebar for Mobile
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('active');
            });
        </script>

</body>

</html>