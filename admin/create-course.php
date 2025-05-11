<?php
// Include session management
include_once "../class-file/session.php";
include_once "../class-file/course.php";

$session = new SessionManager();

// Generate a new CSRF token if it doesn't exist
if ($session->exists('form_token') == false) {
    $session->set('form_token', bin2hex(random_bytes(32))); // Secure random token
}

// Instantiate the Course class
$course = new Course();
$editMode = false;

// Check if edit_course is present in the query string
if (isset($_GET['edit_course']) && !empty($_GET['edit_course'])) {
    $courseId = $_GET['edit_course'];
    $editMode = true;

    // Fetch the course details
    $course->course_id = $courseId;
    $course->getCourseById();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate the token
    if (isset($_POST['form_token']) && $_POST['form_token'] === $session->get('form_token')) {
        // Assign form data to course object properties
        $course->course_name = $_POST['courseName'];
        $course->program_name = $_POST['program'];
        $course->course_code = $_POST['courseCode'];
        $course->course_details = $_POST['courseDetails'];
        $course->course_objectives = $_POST['courseObjectives'];
        $course->session = $_POST['session'];
        $course->department = $_POST['department'];

        if (isset($_POST["updateCourse"])) {
            // Update existing course
            $course->course_id = $_POST["updateCourse"]; // Include the course ID for update
            $isUpdated = $course->updateCourse();

            if ($isUpdated) {
                $session->set("msg1", "Course updated successfully.");
                $session->set("msg1d", 1);
            } else {
                $session->set("msg1", "Course update failed. Please try again.");
                $session->set("msg1d", 1);
            }
        } else if (isset($_POST["createCourse"])) {
            // Create new course
            $insertedId = $course->insertCourse();

            if ($insertedId) {
                $session->set("msg1", "Course creation successful.");
                $session->set("msg1d", 1);
            } else {
                $session->set("msg1", "Course creation unsuccessful. Please try again.");
                $session->set("msg1d", 1);
            }
        }

        // Prevent resubmission by unsetting the token
        $session->remove('form_token');
    } else {
        $session->set("msg1", "Invalid or duplicate form submission.");
        $session->set("msg1d", 1);
    }
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

    <div class="site-wrap">


        <?php include_once "sidebar.php"; ?>


        <!-- Add Section Start -->
        <section class="form-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="form-wrapper">
                            <div class="form-header text-center mb-4">
                                <?php
                                if ($session->get("msg1d") == 0) {
                                    $session->remove("msg1");
                                    $session->remove("msg1d");
                                }
                                if ($session->isSessionActive() && $session->exists("msg1") && $session->get("msg1d") == 1) {
                                    echo '<h2 style="color: red;">' . $session->get("msg1") . '</h2>';
                                    $session->set("msg1d", 0);
                                }
                                ?>
                            </div>
                            <div class="form-header text-center mb-4">
                                <h4>Create Course</h4>
                            </div>
                            <div class="form-block">
                                <form action="" method="POST">
                                    <!-- Include the CSRF token -->
                                    <input type="hidden" name="form_token" value="<?php echo $session->get('form_token'); ?>">

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="courseName" class="form-label">Course Name</label>
                                            <input type="text" class="form-control" id="courseName" name="courseName"
                                                value="<?php echo $editMode ? htmlspecialchars($course->course_name) : ''; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="program" class="form-label">Program Name</label>
                                            <select class="form-control" id="program" name="program" required>
                                                <option value="bsc" <?php echo $editMode && $course->program_name === 'bsc' ? 'selected' : ''; ?>>B.Sc.</option>
                                                <option value="msc" <?php echo $editMode && $course->program_name === 'msc' ? 'selected' : ''; ?>>M.Sc.</option>
                                                <option value="training" <?php echo $editMode && $course->program_name === 'training' ? 'selected' : ''; ?>>Training</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="courseCode" class="form-label">Course Code</label>
                                            <input type="text" class="form-control" id="courseCode" name="courseCode"
                                                value="<?php echo $editMode ? htmlspecialchars($course->course_code) : ''; ?>" required>
                                        </div>
                                    </div>

                                    <?php
                                    // Array of department options
                                    $departments = [
                                        "Computer Science and Engineering",
                                        "Chemical Engineering",
                                        "Textile Engineering",
                                        "Civil Engineering",
                                        "Electrical Engineering",
                                        "Mechanical Engineering",
                                        "Biotechnology",
                                        "Aerospace Engineering",
                                        "Environmental Engineering",
                                        "Architecture"
                                    ];
                                    $selectedDepartment = $editMode ? $course->department : ''; // Handle edit mode
                                    ?>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="department" class="form-label">Department</label>
                                            <select class="form-control" id="department" name="department" required>
                                                <?php foreach ($departments as $department): ?>
                                                    <option value="<?php echo $department; ?>" <?php echo $editMode && $department === $selectedDepartment ? 'selected' : ''; ?>>
                                                        <?php echo $department; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="session" class="form-label">Session</label>
                                            <input type="text" class="form-control" id="session" name="session" placeholder="2019-2020"
                                                value="<?php echo $editMode ? htmlspecialchars($course->session) : ''; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="courseDetails" class="form-label">Course Description (Details)</label>
                                            <input type="text" class="form-control" id="courseDetails" name="courseDetails"
                                                value="<?php echo $editMode ? htmlspecialchars($course->course_details) : ''; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="courseObjectives" class="form-label">Course Objectives</label>
                                            <input type="text" class="form-control" id="courseObjectives" name="courseObjectives"
                                                value="<?php echo $editMode ? htmlspecialchars($course->course_objectives) : ''; ?>" required>
                                        </div>
                                    </div>

                                    <div class="button-wrapper d-flex align-items-center justify-content-center text-center">
                                        <button class="button" type="submit"
                                            name="<?php echo $editMode ? 'updateCourse' : 'createCourse'; ?>" 
                                            value="<?php echo $editMode ? $course->course_id : ''; ?>">
                                            <?php echo $editMode ? 'Update Course' : 'Create'; ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Registration Section End -->



        <!-- Footer -->
        <footer class="footer">
            <div class="container text-center">
                <p class="mb-0">© 2024 Your Website Name. All Rights Reserved.</p>
                <small>Designed with ❤️ by Your Name</small>
            </div>
        </footer>
        <!-- Footer -->




    </div> <!-- .site-wrap -->

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



    <!-- Custom JavaScript for Filtering -->
    <script>
        document.getElementById('courseFilter').addEventListener('change', function() {
            const filterValue = this.value;
            const cards = document.querySelectorAll('#courseContainer .col-md-6');

            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filterValue === 'all' || filterValue === category) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>