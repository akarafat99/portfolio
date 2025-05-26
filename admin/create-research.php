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

include_once "../class-file/Research.php";
include_once "../class-file/File.php";


// Initialize edit mode and pre-fill values if `edit_research` is set
$editMode = false;
$research = new Research();
$file = new FileManager();

if (isset($_GET['editResearch'])) {
    $editMode = true;
    $research->research_id = $_GET['editResearch'];
    $research->getByFilters($research->research_id);
}

// Handle form submission for creating part
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createResearch'])) {
    $research->status = 1;
    $research->research_type = $_POST['research_type'] ?? "";
    $research->research_title = $_POST['research_title'] ?? "";
    $research->abstract = $_POST['abstract'] ?? "";
    $research->published_date = $_POST['published_date'] ?? "";
    $research->accepted_date = $_POST['accepted_date'] ?? "";
    $research->journal_name = $_POST['journal_name'] ?? "";
    $research->doi = $_POST['doi'] ?? "";
    $research->isbn = $_POST['isbn'] ?? "";
    $research->issn = $_POST['issn'] ?? "";
    $research->publisher = $_POST['publisher'] ?? "";
    $research->file_url = $_POST['file_url'] ?? "";
    $research->website_link = $_POST['websiteLink'] ?? "";

    // Handle file uploads
    $uploaded = [];
    if (!empty($_FILES['files']['name'][0])) {
        foreach ($_FILES['files']['tmp_name'] as $i => $tmp) {
            $info = ['name' => $_FILES['files']['name'][$i], 'tmp_name' => $tmp, 'error' => $_FILES['files']['error'][$i]];
            if ($file->doOp($info)) {
                $uploaded[] = $file->file_id;
            }
        }
    }
    $research->files = implode(',', $uploaded);

    $ok = $research->insert();
    $session::set('msg1', $ok ? 'Research added.' : 'Failed to add research.');
    echo "<script>window.location.href='create-research.php';</script>";
    exit;
}

// Handle form submission for updating part
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateResearch'])) {
    $research->research_id = $_POST['updateResearch'];
    $research->getByFilters($research->research_id);
    $research->research_type = $_POST['research_type'] ?? "";
    $research->research_title = $_POST['research_title'] ?? "";
    $research->abstract = $_POST['abstract'] ?? "";
    $research->published_date = $_POST['published_date'] ?? "";
    $research->accepted_date = $_POST['accepted_date'] ?? "";
    $research->journal_name = $_POST['journal_name'] ?? "";
    $research->doi = $_POST['doi'] ?? "";
    $research->isbn = $_POST['isbn'] ?? "";
    $research->issn = $_POST['issn'] ?? "";
    $research->publisher = $_POST['publisher'] ?? "";
    $research->file_url = $_POST['file_url'] ?? "";
    $research->website_link = $_POST['websiteLink'] ?? "";

    // Handle file updates
    $prevFiles = !empty($research->files) ? explode(',', $research->files) : [];
    $removedFiles = !empty($_POST['removedFiles']) ? explode(',', $_POST['removedFiles']) : [];
    $keepFiles = array_diff($prevFiles, $removedFiles);

    // New uploads
    $uploaded = [];
    if (!empty($_FILES['files']['name'][0])) {
        foreach ($_FILES['files']['tmp_name'] as $i => $tmp) {
            $info = ['name' => $_FILES['files']['name'][$i], 'tmp_name' => $tmp, 'error' => $_FILES['files']['error'][$i]];
            if ($file->doOp($info)) {
                $uploaded[] = $file->file_id;
            }
        }
    }
    $allFiles = array_merge($keepFiles, $uploaded);
    $research->files = implode(',', $allFiles);
    $ok = $research->update();
    $session::set('msg1', $ok ? 'Research updated.' : 'Failed to update research.');
    echo "<script>window.location.href='create-research.php';</script>";
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
    <link rel="stylesheet" href="../fonts/icomoon/style.css">

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



        <!-- TOAST POP UP -->
        <?php
        // if ($session->isSessionActive() && $session->exists("msg1") && $session->get("msg1d") == 1) {
        //     //the popup html code
        // }
        ?>

        <!-- Add Section Start -->
        <div class="container my-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="form-wrapper">
                        <div class="form-header text-center mb-4">
                            <?php
                            if ($session::get("msg1")) {
                                echo '<div class="alert alert-success">' . $session::get("msg1") . '</div>';
                                $session::delete("msg1");
                            }
                            ?>
                        </div>
                        <div class="form-header text-center mb-4">
                            <h4><?php echo $editMode ? 'Edit Research' : 'Add Research or Publication'; ?></h4>
                        </div>
                        <div class="form-block">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <!-- research type -->
                                <div class="mb-3">
                                    <label class="form-label">Research Type</label>
                                    <select name="research_type" class="form-control" required>
                                        <?php
                                        include_once "../class-file/ResearchType.php";
                                        $types = getResearchTypes();
                                        foreach ($types as $t):
                                        ?>
                                            <option value="<?php echo $t; ?>"
                                                <?php echo $editMode && $research->research_type === $t ? 'selected' : ''; ?>>
                                                <?php echo ucfirst($t); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- title -->
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text"
                                        name="research_title"
                                        class="form-control"
                                        required
                                        value="<?php echo $editMode ? htmlspecialchars($research->research_title) : ''; ?>">
                                </div>

                                <!-- abstract -->
                                <div class="mb-3">
                                    <label class="form-label">Abstract</label>
                                    <textarea name="abstract"
                                        class="form-control"
                                        style="min-height: 200px;"
                                        required><?php echo $editMode ? htmlspecialchars($research->abstract) : ''; ?></textarea>
                                </div>

                                <!-- dates -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Published Date</label>
                                        <input type="date"
                                            name="published_date"
                                            class="form-control"
                                            required
                                            value="<?php echo $editMode ? substr($research->published_date, 0, 10) : ''; ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Accepted Date</label>
                                        <input type="date"
                                            name="accepted_date"
                                            class="form-control"
                                            value="<?php echo $editMode ? substr($research->accepted_date, 0, 10) : ''; ?>">
                                    </div>
                                </div>

                                <!-- journal / publisher info -->
                                <div class="mb-3">
                                    <label class="form-label">Journal / Publisher</label>
                                    <input type="text"
                                        name="journal_name"
                                        class="form-control"
                                        value="<?php echo $editMode ? htmlspecialchars($research->journal_name) : ''; ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">DOI</label>
                                        <input type="text"
                                            name="doi"
                                            class="form-control"
                                            value="<?php echo $editMode ? htmlspecialchars($research->doi) : ''; ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">ISBN</label>
                                        <input type="text"
                                            name="isbn"
                                            class="form-control"
                                            value="<?php echo $editMode ? htmlspecialchars($research->isbn) : ''; ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">ISSN</label>
                                        <input type="text"
                                            name="issn"
                                            class="form-control"
                                            value="<?php echo $editMode ? htmlspecialchars($research->issn) : ''; ?>">
                                    </div>
                                </div>

                                <!-- publisher -->
                                <div class="mb-3">
                                    <label class="form-label">Publisher</label>
                                    <input type="text"
                                        name="publisher"
                                        class="form-control"
                                        value="<?php echo $editMode ? htmlspecialchars($research->publisher) : ''; ?>">
                                </div>

                                <!-- file_url link -->
                                <div class="mb-3">
                                    <label class="form-label">External File URL</label>
                                    <input type="url"
                                        name="file_url"
                                        class="form-control"
                                        placeholder="https://..."
                                        value="<?php echo $editMode ? htmlspecialchars($research->file_url) : ''; ?>">
                                </div>

                                <!-- website_link -->
                                <div class="mb-3">
                                    <label for="websiteLink" class="form-label">Website Link</label>
                                    <input type="url"
                                        class="form-control"
                                        id="websiteLink"
                                        name="websiteLink"
                                        placeholder="https://your-website.com"
                                        value="<?php echo $editMode ? htmlspecialchars($research->website_link) : ''; ?>">
                                </div>

                                <?php if ($editMode && $research->files): ?>
                                    <div class="mb-3">
                                        <label>Existing Files</label>
                                        <ul class="list-group">
                                            <?php foreach (explode(',', $research->files) as $fid): $file->getByFilters($fid); ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="../uploads1/<?php echo $file->file_new_name; ?>" target="_blank"><?php echo $file->file_original_name; ?></a>
                                                    <button type="button" class="btn btn-sm btn-danger remove-file" data-file-id="<?php echo $fid; ?>">&times;</button>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <input type="hidden" name="removedFiles" id="removedFiles" value="">
                                    </div>
                                <?php endif; ?>

                                <!-- multiple file uploads -->
                                <div class="mb-3">
                                    <label class="form-label">Upload Files</label>
                                    <input type="file"
                                        name="files[]"
                                        class="form-control"
                                        multiple>
                                </div>

                                <div class="text-center">
                                    <button type="submit"
                                        name="<?php echo $editMode ? 'updateResearch' : 'createResearch'; ?>"
                                        value="<?php echo $editMode ? $research->research_id : ''; ?>"
                                        class="btn btn-primary">
                                        <?php echo $editMode ? 'Update Research' : 'Submit Research'; ?>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--  Section End -->

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

    <!-- Remove File Script -->
    <script>
        $(document).ready(function () {
            // Remove file button click event
            $('.remove-file').click(function () {
                var fileId = $(this).data('file-id');
                $(this).closest('li').remove();
                var removedFiles = $('#removedFiles').val();
                $('#removedFiles').val(removedFiles ? removedFiles + ',' + fileId : fileId);
            });
        });
    </script>

</body>

</html>