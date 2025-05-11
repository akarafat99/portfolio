<?php
// Include session management and class files
include_once "../class-file/session.php";
include_once "../class-file/course.php";
include_once "../class-file/course-details.php";
include_once "../class-file/file.php";

// Initialize objects
$course = new Course();
$courseDetails = new CourseDetails();

// Check if the course_id parameter is set in the URL
if (isset($_GET['course_id'])) {
    // Retrieve and sanitize the course_id
    $course_id = htmlspecialchars($_GET['course_id']);

    // Load course data
    $course->course_id = $course_id;
    $course->getCourseById();

    // Get all course details matching course_of
    $courseDetails->course_id = $course_id;
    $allDetails = $courseDetails->getCourseDetailsByCourseId();
} else {
    // die("Course ID not provided.");
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

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap ">

        <?php include_once "sidebar.php"; ?>

        <!-- Course details Section Start -->
        <section class="course-details-dashboard px-5">
            <div class="container py-5">
                <!-- Course Header -->
                <!-- Course Header -->
                <div class="course-header mb-5">
                    <h1>Course Title: <?php echo htmlspecialchars($course->course_name ?? "N/A"); ?></h1><br>
                    <h4 style="color: grey;">Course ID: <?php echo htmlspecialchars($course->course_id ?? "N/A"); ?></h4>
                    <p class="course-text mb-1"><strong>Course Code:</strong> <?php echo htmlspecialchars($course->course_code ?? "N/A"); ?></p>
                    <p class="course-text mb-1"><strong>Course Description (Details):</strong> <?php echo htmlspecialchars($course->course_details ?? "No description available."); ?></p>
                    <p class="course-text mb-1"><strong>Course Objectives:</strong> <?php echo htmlspecialchars($course->course_objectives ?? "No objectives available."); ?></p>
                    <p class="course-text mb-1"><strong>Department:</strong> <?php echo htmlspecialchars($course->department ?? "No department available."); ?></p>
                    <p class="course-text mb-1"><strong>Session:</strong> <?php echo htmlspecialchars($course->session ?? "No session available."); ?></p>
                    <p class="course-text mb-1"><strong>Created On:</strong> <?php echo htmlspecialchars(date("F j, Y, g:i a", strtotime($course->created ?? "now"))); ?></p>
                    <p class="course-text mb-1"><strong>Last Modified:</strong> <?php echo htmlspecialchars(date("F j, Y, g:i a", strtotime($course->modified ?? $course->created_at ?? "now"))); ?></p>
                </div>


                <!-- Create Button -->
                <div class="d-flex justify-content-end mb-4">
                    <a href="add-content.php?course_id=<?php echo urlencode($course_id); ?>" class="table-btn">Add Content</a>
                </div>



                <!-- Course Syllabus Table -->
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Content</th>
                                <th>Resources</th>
                                <th>Comments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($allDetails)) {
                                $day_temp = 1;
                                foreach ($allDetails as $detail) {
                                    echo "<tr>";
                                    // echo "<td>" . htmlspecialchars($detail['day_no']) . "</td>";
                                    echo "<td>" . $day_temp . "</td>";
                                    $day_temp = $day_temp + 1;
                                    echo "<td>" . htmlspecialchars($detail['content_details']) . "</td>";

                                    // Handle resources: If there are multiple, split and create links
                                    if (!empty($detail['resource_files'])) {
                                        $resources = explode(',', $detail['resource_files']);
                                        echo "<td>";
                                        foreach ($resources as $file_id) {
                                            $file_id = trim($file_id);
                                            $file = new File();
                                            $file->file_id = $file_id;
                                            $file->setValuesById();
                                            $file_name = $file->file_name;
                                            $file_original_name = $file->file_original_name;
                                            echo "<a href='../file/$file->file_name' class='btn-link' target='_blank'>" . htmlspecialchars($file->file_original_name) . "</a><br>";
                                        }
                                        echo "</td>";
                                    } else {
                                        echo "<td>No resources</td>";
                                    }
                                    echo "<td>" . htmlspecialchars($detail['comment']) . "</td>";
                                    // Add "Edit Content" button in a new column
                                    echo "<td>";
                                    echo "<a href='add-content.php?editContent=" . htmlspecialchars($detail['details_id']) . "' class='btn btn-primary'>Edit Content</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No course contents available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
        <!-- Course details Section End-->

        <!-- Footer -->
        <footer class="footer mt-5">
            <div class="container text-center">
                <p class="mb-0">© 2024 Your Website Name. All Rights Reserved.</p>
                <small>Designed with ❤️ by Your Name</small>
            </div>
        </footer>
        <!-- Footer End -->
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