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

include_once "../class-file/Lab.php";
include_once "../class-file/File.php";
$lab = new Lab();
$file = new FileManager();

$editMode = false;

if (isset($_GET['editLab'])) {
    $lab->lab_id = $_GET['editLab'];
    $lab->getByFilters($lab->lab_id);
    $editMode = true;
}

// Handle create 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createLab'])) {
    $lab->status = 1;
    $lab->lab_title = $_POST['labTitle'];
    $lab->lab_about = $_POST['labAbout'];
    $lab->lab_outcome = $_POST['labOutcome'];
    $lab->lab_head_name = $_POST['labHeadName'];
    $lab->lab_head_details = $_POST['labHeadDetails'];


    // Handle lab members to save names and details separately
    $labMembersName = $_POST['lab-member-name'] ?? [];
    $labMembersDetails = $_POST['lab-member-details'] ?? [];

    $lab->lab_members_name = json_encode(array_filter($labMembersName));
    $lab->lab_members_details = json_encode(array_filter($labMembersDetails));
    $lab->files = "";

    // Handle file uploads
    $uploaded = [];
    if (!empty($_FILES['labFiles']['name'][0])) {
        foreach ($_FILES['labFiles']['tmp_name'] as $i => $tmp) {
            $info = ['name' => $_FILES['labFiles']['name'][$i], 'tmp_name' => $tmp, 'error' => $_FILES['labFiles']['error'][$i]];
            if ($file->doOp($info)) {
                $uploaded[] = $file->file_id;
            }
        }
    }
    $lab->files = implode(',', $uploaded);
    $ok = $lab->insert();
    $session::set('msg1', $ok ? 'Lab created.' : 'Creation failed.');
    echo "<script>window.location.href='create-lab.php';</script>";
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateLab'])) {
    $lab->lab_id = $_POST['updateLab'];
    $lab->getByFilters($lab->lab_id);
    $lab->lab_title = $_POST['labTitle'];
    $lab->lab_about = $_POST['labAbout'];
    $lab->lab_outcome = $_POST['labOutcome'];
    $lab->lab_head_name = $_POST['labHeadName'];
    $lab->lab_head_details = $_POST['labHeadDetails'];

    // Handle lab members to save names and details separately
    $labMembersName = $_POST['lab-member-name'] ?? [];
    $labMembersDetails = $_POST['lab-member-details'] ?? [];

    $lab->lab_members_name = json_encode(array_filter($labMembersName));
    $lab->lab_members_details = json_encode(array_filter($labMembersDetails));

    // Handle file uploads
    $prevFiles = !empty($lab->files) ? explode(',', $lab->files) : [];
    $removedFiles = !empty($_POST['removedFiles']) ? explode(',', $_POST['removedFiles']) : [];
    $keepFiles = array_diff($prevFiles, $removedFiles);

    // New uploads
    $uploadedFiles = [];
    if (!empty($_FILES['labFiles']['name'][0])) {
        foreach ($_FILES['labFiles']['tmp_name'] as $i => $tmp) {
            $info = ['name' => $_FILES['labFiles']['name'][$i], 'tmp_name' => $tmp, 'error' => $_FILES['labFiles']['error'][$i]];
            if ($file->doOp($info)) {
                $uploadedFiles[] = $file->file_id;
            }
        }
    }

    // Combine existing and new files
    $allFiles = array_merge($keepFiles, $uploadedFiles);
    $lab->files = implode(',', $allFiles);

    // Update lab
    $ok = $lab->update();
    $session::set('msg1', $ok ? 'Lab updated.' : 'Update failed.');
    echo "<script>window.location.href='create-lab.php';</script>";
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pending Registration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .lab-contents-wrapper {
            padding: 2rem;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        }

        .lab-member-group {
    position: relative;
    padding: 1.5rem 1rem 1rem; /* top padding to clear the close button */
    border: 1px solid #ddd;
    border-radius: .25rem;
    margin-bottom: 1rem;
  }
  .remove-member {
    position: absolute;
    top: .5rem;
    right: .5rem;
    z-index: 10;
  }
    </style>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
    <div class="site-wrap">

        <?php include_once "sidebar.php"; ?>

        <div class="container py-4">
            <div class="lab-contents-wrapper">
                <h2 class="mb-4 text-center">Research Lab</h2>
                <?php
                if ($session::get('msg1')): ?>
                    <div class="alert alert-success">
                        <?php echo $session::get('msg1');
                        $session::delete('msg1'); ?>
                    </div>
                <?php endif; ?>
                <form id="lab-form" method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="lab-title-input" class="form-label">Lab Title</label>
                        <input
                            type="text"
                            id="lab-title-input"
                            name="labTitle"
                            class="form-control"
                            value="<?php echo $editMode ? htmlspecialchars($lab->lab_title, ENT_QUOTES) : ''; ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="lab-about-input" class="form-label">Lab About</label>
                        <textarea
                            id="lab-about-input"
                            name="labAbout"
                            class="form-control"
                            style="min-height: 200px;"
                            required><?php echo $editMode ? htmlspecialchars($lab->lab_about, ENT_QUOTES) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="lab-outcome-input" class="form-label">Lab Outcomes</label>
                        <textarea
                            id="lab-outcome-input"
                            name="labOutcome"
                            class="form-control"
                            style="min-height: 200px;"
                            required><?php echo $editMode ? htmlspecialchars($lab->lab_outcome, ENT_QUOTES) : ''; ?></textarea>
                    </div>

                    <hr>

                    <?php if ($editMode && $lab->files): ?>
                        <div class="mb-3">
                            <label>Existing Files</label>
                            <ul class="list-group">
                                <?php foreach (explode(',', $lab->files) as $fid): $file->getByFilters($fid); ?>
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
                        <label for="lab-files-input" class="form-label">Upload Files</label>
                        <input
                            type="file"
                            id="lab-files-input"
                            name="labFiles[]"
                            class="form-control"
                            multiple>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="lab-head-name-input" class="form-label">Lab Head Name</label>
                        <input
                            type="text"
                            id="lab-head-name-input"
                            name="labHeadName"
                            class="form-control"
                            value="<?php echo $editMode ? htmlspecialchars($lab->lab_head_name, ENT_QUOTES) : ''; ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="lab-head-details-input" class="form-label">Lab Head Details</label>
                        <textarea
                            id="lab-head-details-input"
                            name="labHeadDetails"
                            class="form-control"
                            rows="3"
                            required><?php echo $editMode ? htmlspecialchars($lab->lab_head_details, ENT_QUOTES) : ''; ?></textarea>
                    </div>

                    <hr>

                    <div id="lab-members-container">
                        <h5>Lab Members</h5>
                        <?php
                        if ($editMode && $lab->lab_members_name !== ''):
                            $names   = json_decode($lab->lab_members_name, true);
                            $details = json_decode($lab->lab_members_details, true);
                            foreach ($names as $i => $memberName):
                                if (trim($memberName) === '') continue;
                        ?>
                                <div class="lab-member-group mb-3 position-relative">
                                    <button type="button"
          class="remove-member btn btn-sm btn-outline-danger"
          aria-label="Remove">&times;</button>
                                    <div class="row g-2">
                                        <div class="col-md-12">
                                            <label class="form-label" for="member-name-<?php echo $i; ?>">Name</label>
                                            <input
                                                type="text"
                                                id="member-name-<?php echo $i; ?>"
                                                name="lab-member-name[]"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($names[$i], ENT_QUOTES); ?>">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="member-details-<?php echo $i; ?>">Details</label>
                                            <input
                                                type="text"
                                                id="member-details-<?php echo $i; ?>"
                                                name="lab-member-details[]"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($details[$i] ?? '', ENT_QUOTES); ?>">
                                        </div>
                                    </div>
                                </div>
                        <?php endforeach;
                        endif; ?>
                    </div>

                    <div class="mb-3 text-end">
                        <button type="button" id="add-member-button" class="btn btn-sm btn-secondary">
                            + Add Member
                        </button>
                    </div>

                    <hr>


                    <div class="text-center">
                        <button
                            type="submit"
                            name="<?php echo $editMode ? 'updateLab' : 'createLab'; ?>"
                            value="<?php echo $editMode ? (int)$lab->lab_id : 0; ?>"
                            class="btn btn-success">
                            <?php echo $editMode ? 'Update Lab' : 'Create Lab'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div> <!-- for sidebar -->
    </div>

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- handle file remove -->
    <script>
        $(document).on('click', '.remove-file', function() {
            const fileId = $(this).data('file-id');
            $(this).closest('.list-group-item').remove();
            const removedFiles = $('#removedFiles').val();
            $('#removedFiles').val(removedFiles ? removedFiles + ',' + fileId : fileId);
        });

        // Remove member group
        $(document).on('click', '.remove-member', function() {
            $(this).closest('.lab-member-group').remove();
        });

        let memberCount = $('.lab-member-group').length || 0;
        $('#add-member-button').on('click', function() {
            memberCount++;
            const html = `
      <div class="lab-member-group mb-3 position-relative">
        <button type="button"
          class="remove-member btn btn-sm btn-outline-danger"
          aria-label="Remove">&times;</button>
        <div class="row g-2">
          <div class="col-md-12">
            <label class="form-label" for="member-name-${memberCount}">Name</label>
            <input type="text"
                   id="member-name-${memberCount}"
                   name="lab-member-name[]"
                   class="form-control">
          </div>
          <div class="col-md-12">
            <label class="form-label" for="member-details-${memberCount}">Details</label>
            <input id="member-details-${memberCount}"
                   name="lab-member-details[]"
                   class="form-control"
                   rows="2"></input>
          </div>
        </div>
      </div>`;
            $('#lab-members-container').append(html);
        });
    </script>


</body>

</html>