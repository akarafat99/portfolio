<?php
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
$session::ensureSessionStarted();

if($session::get("admin") == null) {
    echo "<script> window.alert('Please login to access this page.'); </script>";
    $session::set('msg1', "Please login to access this page.");
    echo '<script> window.location.href="login.php"; </script>';
    exit();
}

include_once "../class-file/Course.php";
$course = new Course();
$allCourses = $course->getByFilters();
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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- Custom CSS -->


</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap ">

        <?php include_once "sidebar.php"; ?>

        <!-- Course Management Section -->
        <div class="container mt-4">
            <div class="table-wrapper">
                <h3 class="text-center mb-4">Course Management</h3>

                <?php if (!empty($allCourses)): ?>
                    <!-- Responsive Table Wrapper -->
                    <div class="table-responsive" style="overflow-x: auto;">
                        <!-- DataTable -->
                        <table id="courseTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Course Name</th>
                                    <th>Session</th>
                                    <th>Department</th>
                                    <th>Course Dashboard</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($allCourses as $course) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($course['course_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($course['session']) . "</td>";
                                    echo "<td>" . htmlspecialchars($course['department']) . "</td>";
                                    echo "<td><div><a href='course-dashboard.php?course_id=" . urlencode($course['course_id']) . "' class='table-btn btn-sm'>Dashboard</a></div></td>";
                                    echo "<td><div><a href='create-course.php?edit_course=" . urlencode($course['course_id']) . "' class='table-btn btn-sm'>Edit course</a></div></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-danger">No courses available. Please add a course to continue.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Main Content End -->
    </div>

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



    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
        // Toggle Sidebar for Mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        });

        // Initialize DataTable with responsiveness
        $(document).ready(function() {
            $('#courseTable').DataTable({
                responsive: true, // Enable responsive DataTables
                paging: true,
                searching: true,
                info: true,
                lengthChange: true,
                autoWidth: false,
            });
        });
    </script>
</body>

</html>