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

include_once "../class-file/Project.php";
include_once "../class-file/File.php";

// Initialize objects
$project = new Project();
$file = new FileManager();
$isEdit = false;

// Check if editing
if (isset($_GET['editProject'])) {
    $projectId = intval($_GET['editProject']);
    $project->getByFilters($projectId);
    $isEdit = true;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProject'])) {
    $project->project_id   = intval($_POST['projectId']);
    $project->getByFilters($project->project_id);
    $project->title        = $_POST['projectTitle'] ?? "";
    $project->description  = $_POST['projectDescription'] ?? "";
    $project->project_type = $_POST['projectType'] ?? "";
    $project->github_link  = $_POST['githubLink'] ?? "";
    $project->live_link    = $_POST['liveLink'] ?? "";

    // Existing files
    $prev = $project->files ? explode(',', $project->files) : [];
    $removed = !empty($_POST['removedFiles']) ? explode(',', $_POST['removedFiles']) : [];
    $keep = array_diff($prev, $removed);

    // New uploads
    $uploaded = [];
    if (!empty($_FILES['projectFiles']['name'][0])) {
        foreach ($_FILES['projectFiles']['tmp_name'] as $i => $tmp) {
            $info = ['name' => $_FILES['projectFiles']['name'][$i], 'tmp_name' => $tmp, 'error' => $_FILES['projectFiles']['error'][$i]];
            if ($file->doOp($info) === 1) {
                $uploaded[] = $file->file_id;
            }
        }
    }

    $allFiles = array_merge($keep, $uploaded);
    $project->files = implode(',', $allFiles);
    $project->status = 1;
    $ok = $project->update();

    $session::set('msg1', $ok ? 'Project updated.' : 'Update failed.');
    echo "<script>window.location.href='create-project.php';</script>";
    exit;
}

// Handle create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createProject'])) {
    $project->status        = 1;
    $project->title         = $_POST['projectTitle'];
    $project->description   = $_POST['projectDescription'];
    $project->project_type  = $_POST['projectType'];
    $project->github_link   = $_POST['githubLink'];
    $project->live_link     = $_POST['liveLink'];

    $newFiles = [];
    if (!empty($_FILES['projectFiles']['name'][0])) {
        foreach ($_FILES['projectFiles']['tmp_name'] as $i => $tmp) {
            $info = ['name' => $_FILES['projectFiles']['name'][$i], 'tmp_name' => $tmp, 'error' => $_FILES['projectFiles']['error'][$i]];
            if ($file->doOp($info) === 1) {
                $newFiles[] = $file->file_id;
            }
        }
    }
    $project->files = implode(',', $newFiles);

    $ok = $project->insert();
    $session::set('msg1', $ok ? 'Project added.' : 'Add failed.');
    echo "<script>window.location.href='create-project.php';</script>";
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
        <div class="container my-4">
            <?php if ($session::get('msg1')): ?>
                <div class="alert alert-success">
                    <?php echo $session::get('msg1');
                    $session::delete('msg1'); ?>
                </div>
            <?php endif; ?>
            <h3 class="text-center mb-4"><?php echo $isEdit ? 'Edit Project' : 'Create Project'; ?></h3>
            <form method="post" enctype="multipart/form-data">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="projectId" value="<?php echo $project->project_id; ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label>Title</label>
                    <input name="projectTitle" class="form-control" required value="<?php echo $isEdit ? htmlspecialchars($project->title) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="projectDescription" class="form-control" style="min-height:300px;" required><?php echo $isEdit ? htmlspecialchars($project->description) : ''; ?></textarea>
                </div>
                <div class="mb-3">
                    <label>Type</label>
                    <select name="projectType" class="form-control" required>
                        <option value="personal" <?php echo $isEdit && $project->project_type == 'personal' ? 'selected' : ''; ?>>Personal</option>
                        <option value="academic" <?php echo $isEdit && $project->project_type == 'academic' ? 'selected' : ''; ?>>Academic</option>
                    </select>
                </div>
                <div class="mb-3 row">
                    <div class="col">
                        <label>Github Link</label>
                        <input name="githubLink" class="form-control" value="<?php echo $isEdit ? htmlspecialchars($project->github_link) : ''; ?>">
                    </div>
                    <div class="col">
                        <label>Live Link</label>
                        <input name="liveLink" class="form-control" value="<?php echo $isEdit ? htmlspecialchars($project->live_link) : ''; ?>">
                    </div>
                </div>
                <?php if ($isEdit && $project->files): ?>
                    <div class="mb-3">
                        <label>Existing Files</label>
                        <ul class="list-group">
                            <?php foreach (explode(',', $project->files) as $fid): $file->getByFilters($fid); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="../uploads1/<?php echo $file->file_new_name; ?>" target="_blank"><?php echo $file->file_original_name; ?></a>
                                    <button type="button" class="btn btn-sm btn-danger remove-file" data-file-id="<?php echo $fid; ?>">&times;</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <input type="hidden" name="removedFiles" id="removedFiles" value="">
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label><?php echo $isEdit ? 'Add/Remove Files' : 'Upload Files'; ?></label>
                    <input type="file" name="projectFiles[]" multiple class="form-control">
                </div>
                <button type="submit" name="<?php echo $isEdit ? 'updateProject' : 'createProject'; ?>" class="btn btn-primary">
                    <?php echo $isEdit ? 'Update' : 'Create'; ?>
                </button>
            </form>
        </div>


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



    <script>
        document.querySelectorAll('.remove-file').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.fileId;
                let removed = document.getElementById('removedFiles').value.split(',').filter(x => x);
                removed.push(id);
                document.getElementById('removedFiles').value = removed.join(',');
                btn.closest('li').remove();
            });
        });
    </script>

</body>

</html>