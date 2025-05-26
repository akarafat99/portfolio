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

include_once '../class-file/Course.php';

// Instantiate the Course class
$course = new Course();
$editMode = false;

// Check if edit_course is present in the query string
if (isset($_GET['edit_course']) && !empty($_GET['edit_course'])) {
    $courseId = $_GET['edit_course'];
    $editMode = true;

    // Fetch the course details
    $course->getByFilters($courseId);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course->session = $_POST['session'];
    $course->department = $_POST['department'];
    $course->program_name = $_POST['program'];
    $course->course_code = $_POST['courseCode'];
    $course->course_name = $_POST['courseName'];
    $course->course_details = $_POST['courseDetails'];
    $course->course_objectives = $_POST['courseObjectives'];

    if (isset($_POST['createCourse'])) {
        // Create a new course
        if ($course->insert()) {
            $session::set("msg1", "Course created successfully.");
        } else {
            $session::set("msg1", "Failed to create course.");
        }
    } elseif (isset($_POST['updateCourse'])) {
        // Update the existing course
        if ($course->update($_POST['updateCourse'])) {
            $session::set("msg1", "Course updated successfully.");
        } else {
            $session::set("msg1", "Failed to update course.");
        }
    }
    // Redirect to the same page to avoid resubmission
    echo "<script>window.location.href='create-course.php';</script>";
    exit();
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
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="form-wrapper">
                        <div class="form-header text-center mb-4">
                            <?php if ($session::get("msg1")): ?>
                                <div class="alert alert-success">
                                    <?php echo $session::get("msg1"); ?>
                                </div>
                                <?php $session::delete("msg1"); ?>
                            <?php endif; ?>
                        </div>
                        <div class="form-header text-center mb-4">
                            <h4>Create Course</h4>
                        </div>
                        <div class="form-block">
                            <form action="" method="POST">
                                <?php
                                include_once '../class-file/Department.php';
                                $department = new Department();
                                $departments = $department->getByFilters(null, 1);
                                $x = [];
                                foreach ($departments as $dept) {
                                    $x[] = $dept['department_name'];
                                }
                                $departments = $x;
                                $selectedDepartment = $editMode ? $course->department : '';
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
                                        <input type="text" class="form-control" id="session" name="session" placeholder="example: 2019-2020"
                                            value="<?php echo $editMode ? htmlspecialchars($course->session) : ''; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="program" class="form-label">Program Name</label>
                                        <?php
                                        include_once '../class-file/ProgramName.php';
                                        $allProgram = getProgramNames();
                                        ?>
                                        <select class="form-control" id="program" name="program" required>
                                            <?php foreach ($allProgram as $program): ?>
                                                <option value="<?php echo $program; ?>" <?php echo $editMode && $program === $course->program_name ? 'selected' : ''; ?>>
                                                    <?php echo $program; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="courseCode" class="form-label">Course Code</label>
                                        <input type="text" class="form-control" id="courseCode" name="courseCode"
                                            value="<?php echo $editMode ? htmlspecialchars($course->course_code) : ''; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="courseName" class="form-label">Course Name</label>
                                        <input type="text" class="form-control" id="courseName" name="courseName"
                                            value="<?php echo ($editMode) ? $course->course_name : ''; ?>" required>
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
        <!-- Registration Section End -->

    </div> <!-- .site-navbar -->
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