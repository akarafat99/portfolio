<?php
// Include session management
include_once "../class-file/session.php";
include_once "../class-file/course-details.php";
include_once "../class-file/file.php";

$session = new SessionManager();
$course_details = new CourseDetails();

$editMode = 0;

if (isset($_GET['editContent'])) {
    $editMode = 1; // Set edit mode to true
    $details_id = $_GET['editContent'];

    // Fetch the details
    $course_details->details_id = $details_id;
    $course_details->getCourseDetailsByDetailsId();
}

// Generate a new CSRF token if it doesn't exist
if ($session->exists('form_token') == false) {
    $session->set('form_token', bin2hex(random_bytes(32))); // Secure random token
}

// handle update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["updateContent"])) {
    // Validate the token
    if (isset($_POST['form_token']) && $_POST['form_token'] === $_SESSION['form_token']) {
        $details_id = $_POST["updateContent"];

        // Fetch existing details
        $course_details->details_id = $details_id;
        $course_details->getCourseDetailsByDetailsId();

        // Preserve existing file IDs, excluding removed ones
        $existingFileIds = explode(',', $course_details->resource_files);
        $removedFileIds = isset($_POST['removedFiles']) ? explode(',', $_POST['removedFiles']) : [];
        // Filter out non-numeric values from $existingFileIds and $removedFileIds
        $existingFileIds = array_filter($existingFileIds, 'is_numeric');
        $removedFileIds = array_filter($removedFileIds, 'is_numeric');

        $preservedFileIds = array_diff($existingFileIds, $removedFileIds);

        // Handle removed files and set status to -3 to delete them
        // -3 is the deleted status
        if (!empty($removedFileIds)) {
            // echo 'files removed';
            foreach ($removedFileIds as $fileId) {
                $file = new File();
                $file->file_id = $fileId;
                // Status indicating removal
                $file->status = -3;
                $file->updateStatus();
            }
        }

        // Handle new file uploads
        $targetDir = "../file/";
        $newFileInsertIds = [];

        if (isset($_FILES['courseFiles'])) {
            foreach ($_FILES['courseFiles']['tmp_name'] as $index => $tmpName) {
                if (!empty($tmpName)) {
                    $originalName = $_FILES['courseFiles']['name'][$index];

                    // Create a new File instance
                    $file = new File();
                    $file->status = 1;
                    $file->file_owner = 0;
                    $file->file_name = "0.jpg";
                    $file->file_original_name = $originalName;

                    // Insert new file metadata
                    $insertedId = $file->insertFile();

                    if ($insertedId) {
                        $randomString = bin2hex(random_bytes(8));
                        $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $newFileName = $randomString . $insertedId . '.' . $fileExtension;
                        $targetFile = $targetDir . $newFileName;

                        if (move_uploaded_file($tmpName, $targetFile)) {
                            $newFileInsertIds[] = $insertedId;
                            $file->file_id = $insertedId;
                            $file->file_name = $newFileName;
                            $file->updateFileName();
                        }
                    }
                }
            }
        }

        // Combine preserved and new file IDs
        $updatedFileIds = array_merge($preservedFileIds, $newFileInsertIds);
        $course_details->resource_files = implode(',', $updatedFileIds);

        // Update other fields
        $course_details->course_id = $_POST['courseId'];
        $course_details->content_details = $_POST['contentDetails'];
        $course_details->comment = $_POST['comment'];

        // Update the course details
        $isUpdated = $course_details->updateCourseDetails();

        if ($isUpdated) {
            $session->set("msg1", "Update successful.");
            $session->set("msg1d", 1);
        } else {
            $session->set("msg1", "Update failed. Please try again.");
            $session->set("msg1d", 1);
        }

        $session->remove('form_token');
    } else {
        $session->set("msg1", "Invalid or duplicate form submission.");
        $session->set("msg1d", 1);
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["addContent"])) {
    // Validate the token
    if (isset($_POST['form_token']) && $_POST['form_token'] === $_SESSION['form_token']) {
        // Create an instance of the Research class
        $course_details = new CourseDetails();
        // set status 1 by default
        $course_details->status = 1;

        // Get form data
        $course_details->course_id = $_POST['courseId'];
        $course_details->content_details = $_POST['contentDetails'];
        $course_details->comment = $_POST['comment'];

        // echo $course_details->course_id;
        // echo $course_details->content_details;
        // echo $course_details->comment;

        // ----------
        // Handle file upload
        $targetDir = "../file/"; // Directory to store uploaded files
        $fileInsertIds = []; // Array to store inserted file IDs

        if (isset($_FILES['courseFiles'])) {
            foreach ($_FILES['courseFiles']['tmp_name'] as $index => $tmpName) {
                if (!empty($tmpName)) {
                    $originalName = $_FILES['courseFiles']['name'][$index];

                    // Create a new instance of the File class
                    $file = new File();
                    $file->status = 1;
                    $file->file_owner = 0;
                    $file->file_name = "0.jpg";
                    $file->file_original_name = $originalName;

                    // Insert file metadata into the database
                    $insertedId = $file->insertFile();

                    if ($insertedId) {
                        // Generate a 16-character random string
                        $randomString = bin2hex(random_bytes(8)); // 8 bytes = 16 characters
                        // Rename and move the uploaded file using the inserted ID
                        $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $newFileName = $randomString . $insertedId . '.' . $fileExtension;
                        $targetFile = $targetDir . $newFileName;

                        if (move_uploaded_file($tmpName, $targetFile)) {
                            $fileInsertIds[] = $insertedId; // Store the file ID
                            $file->file_id = $insertedId;
                            $file->file_name = $newFileName; // Update file name in the database
                            $file->updateFileName();
                        } else {
                            // Handle file move error
                            // echo "Error moving file: $originalName<br>";
                        }
                    } else {
                        // Handle database insert error
                        // echo "Error inserting file metadata for: $originalName<br>";
                    }
                }
            }
        }
        // ----------

        // Combine file IDs into a single string and save to the CourseDetails object
        $course_details->resource_files = implode(",", $fileInsertIds);

        // Insert the course content
        $insertedId = $course_details->insertCourseDetails();

        // Check if the insertion was successful
        if ($insertedId) {
            $session->set("msg1", "Submission successful.");
            $session->set("msg1d", 1);
        } else {
            $session->set("msg1", "Submission unsuccessful. Please try again.");
            $session->set("msg1d", 1);
        }

        // Prevent resubmission by unsetting the token
        $session->remove('form_token');

        // // Redirect to clear POST data
        // header("Location: research-manage.php");
        // exit();
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

        <?php
        include_once "sidebar.php";
        ?>


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
                                <h4>Add Course Content</h4>
                            </div>
                            <div class="form-block">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <!-- Include the token in the form -->
                                    <input type="hidden" name="form_token" value="<?php echo $session->get('form_token') ?>">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="courseOf" class="form-label">Course ID</label>
                                            <input type="text" class="form-control" id="courseId" name="courseId"
                                                value="<?php echo $editMode ? htmlspecialchars($course_details->course_id) : (isset($_GET['course_id']) ? htmlspecialchars($_GET['course_id']) : ''); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="courseSummary" class="form-label">Content details</label>
                                        <textarea class="form-control" id="courseSummary" name="contentDetails" rows="2"
                                            required><?php echo $editMode ? htmlspecialchars($course_details->content_details) : ''; ?></textarea>
                                    </div>

                                    <!-- Display Uploaded Files -->
                                    <?php if ($editMode && !empty($course_details->resource_files)): ?>
                                        <div class="mb-3">
                                            <label for="uploadedFiles" class="form-label">Uploaded Files</label>
                                            <ul class="list-group">
                                                <?php
                                                $fileIds = explode(',', $course_details->resource_files);
                                                foreach ($fileIds as $fileId):
                                                    $file = new File();
                                                    $file->file_id = $fileId;
                                                    $file->setValuesById();
                                                ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="../file/<?php echo htmlspecialchars($file->file_name); ?>" target="_blank">
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
                                        <input class="form-control" id="courseComment" name="comment" rows="2" value="<?php echo $editMode ? htmlspecialchars($course_details->comment) : ''; ?>">
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