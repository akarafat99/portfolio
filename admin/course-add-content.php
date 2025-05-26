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
include_once "../class-file/CourseDetails.php";
include_once "../class-file/File.php";

// Initialize objects
$course = new Course();
$course_details = new CourseDetails();
$file = new FileManager();

$editMode = 0;

if (isset($_GET['editContent'])) {
    $editMode = 1; // Set edit mode to true
    $details_id = $_GET['editContent'];
    // Fetch the details
    $course_details->getByFilters($details_id);
}

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['updateContent'])) {
    $course_details->details_id = $_POST['updateContent'];
    $course_details->getByFilters($course_details->details_id);

    // Get form data
    $course_details->course_id = $_POST['courseId'];
    $course_details->day_no = $_POST['dayNo'];
    $course_details->content_details = $_POST['contentDetails'];
    $course_details->comment = $_POST['comment'];

    // Handle file uploads using doOp()
    $fileInsertIds = [];
    if (isset($_FILES['courseFiles']) && $_FILES['courseFiles']['error'][0] !== UPLOAD_ERR_NO_FILE) {
        foreach ($_FILES['courseFiles']['tmp_name'] as $index => $tmpName) {
            $fileArray = [
                'name'     => $_FILES['courseFiles']['name'][$index],
                'tmp_name' => $tmpName,
                'error'    => $_FILES['courseFiles']['error'][$index]
            ];
            $result = $file->doOp($fileArray);
            $fileInsertIds[] = $file->file_id;
        }
    }

    // Handle removed files and update the resource_files field
    $previousFiles = explode(',', $course_details->resource_files);
    $removedFiles = isset($_POST['removedFiles']) ? explode(',', $_POST['removedFiles']) : [];
    $remainingFiles = array_diff($previousFiles, $removedFiles);
    $allFiles = array_merge($remainingFiles, $fileInsertIds);
    $course_details->resource_files = implode(',', $allFiles);

    // Update the course details
    $course_details->update();
    $session::set('msg1', "Course content updated successfully.");
    echo "<script>window.location.href='course-dashboard.php?course_id=" . $course_details->course_id . "';</script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['addContent'])) {
    $course_details->status = 1;
    // Get form data
    $course_details->course_id = $_POST['courseId'];
    $course_details->day_no = $_POST['dayNo'];
    $course_details->content_details = $_POST['contentDetails'];
    $course_details->comment = $_POST['comment'];
    $course_details->resource_files = "";

    // Handle file uploads using doOp()
    $fileInsertIds = [];
    if (isset($_FILES['courseFiles']) && $_FILES['courseFiles']['error'][0] !== UPLOAD_ERR_NO_FILE) {
        foreach ($_FILES['courseFiles']['tmp_name'] as $index => $tmpName) {
            $fileArray = [
                'name'     => $_FILES['courseFiles']['name'][$index],
                'tmp_name' => $tmpName,
                'error'    => $_FILES['courseFiles']['error'][$index]
            ];
            $result = $file->doOp($fileArray);
            $fileInsertIds[] = $file->file_id;
        }
        $course_details->resource_files = implode(',', $fileInsertIds);
    }
    $course_details->insert();
    $session::set('msg1', "Course content added successfully.");
    echo "<script>window.location.href='course-dashboard.php?course_id=" . $course_details->course_id . "';</script>";
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

        <?php
        include_once "sidebar.php";
        ?>


        <!-- Add Section Start -->
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="form-wrapper">
                        <div class="form-header text-center mb-4">
                            <?php
                            if ($session::get('msg1')) {
                                echo '<div class="alert alert" role="alert">' . $session::get('msg1') . '</div>';
                                $session::delete('msg1');
                            }
                            ?>
                        </div>
                        <div class="form-header text-center mb-4">
                            <h4>Add Course Content</h4>
                        </div>
                        <div class="form-block">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="courseOf" class="form-label">Course ID</label>
                                        <input type="text" class="form-control" id="courseId" name="courseId"
                                            value="<?php echo $editMode ? $course_details->course_id : $_GET['course_id']; ?>" readonly>
                                    </div>
                                </div>

                                <!-- for day -->
                                <div class="mb-3">
                                    <label for="courseDay" class="form-label">Day No</label>
                                    <input type="number" class="form-control" id="courseDay" name="dayNo"
                                        value="<?php echo $editMode ? $course_details->day_no : ''; ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="courseSummary" class="form-label">Content details</label>
                                    <textarea class="form-control" id="courseSummary" name="contentDetails" rows="2"
                                        required><?php echo $editMode ? $course_details->content_details : ''; ?></textarea>
                                </div>

                                <!-- Display Uploaded Files -->
                                <?php if ($editMode && !empty($course_details->resource_files)): ?>
                                    <div class="mb-3">
                                        <label for="uploadedFiles" class="form-label">Uploaded Files</label>
                                        <ul class="list-group">
                                            <?php
                                            $fileIds = explode(',', $course_details->resource_files);
                                            foreach ($fileIds as $fileId):
                                                $file->getByFilters($fileId);
                                            ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="../uploads1/<?php echo htmlspecialchars($file->file_new_name); ?>" target="_blank">
                                                        <?php echo htmlspecialchars($file->file_original_name); ?>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm remove-file" data-file-id="<?php echo $fileId; ?>">
                                                        &times;
                                                    </button>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" id="removedFiles" name="removedFiles" value="">

                                <div class="mb-3">
                                    <label for="courseFile" class="form-label">Upload Course Files</label>
                                    <input class="form-control" type="file" id="courseFile" name="courseFiles[]" multiple>
                                </div>

                                <div class="mb-3">
                                    <label for="courseSummary" class="form-label">Comment</label>
                                    <input class="form-control" id="courseComment" name="comment" rows="2" value="<?php echo $editMode ? $course_details->comment : ''; ?>">
                                </div>

                                <div class="button-wrapper d-flex align-items-center justify-content-center text-center">
                                    <button type="submit"
                                        class="button"
                                        name="<?php echo $editMode ? 'updateContent' : 'addContent'; ?>"
                                        value="<?php echo $editMode ? $course_details->details_id : 0; ?>">
                                        <?php echo $editMode ? 'Update' : 'Create'; ?>
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Registration Section End -->

    </div> <!-- for sidebar -->
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

    <!-- Custom JavaScript for Removing Files -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removedFileIds = []; // Array to store removed file IDs
            const removedFilesInput = document.getElementById('removedFiles');

            document.querySelectorAll('.remove-file').forEach(button => {
                button.addEventListener('click', function() {
                    const fileId = this.getAttribute('data-file-id');
                    removedFileIds.push(fileId);
                    removedFilesInput.value = removedFileIds.join(',');

                    // Fade out the `li` and remove it
                    const listItem = this.closest('li');
                    listItem.style.transition = 'opacity 0.3s';
                    listItem.style.opacity = '0.30';
                    setTimeout(() => listItem.style.display = 'none', 300);
                });
            });
        });
    </script>

</body>

</html>