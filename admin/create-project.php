<?php
// Include session management and the Project class
include_once "../class-file/session.php";
include_once "../class-file/project.php";

$session = new SessionManager();

// Generate a new CSRF token if it doesn't exist
if ($session->exists('form_token') == false) {
    $session->set('form_token', bin2hex(random_bytes(32))); // Secure random token
}

// Initialize project variables
$project = new Project();
$editProjectData = null;
$isEdit = false;

// Check if editProject is passed from other page
if (isset($_GET['editProject'])) {
    $projectId = $_GET['editProject'];
    $project->project_id = $projectId;
    $project->getProjectById();
    $isEdit = true;
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && (isset($_POST["createProject"]) || isset($_POST["updateProject"]))) {
    // Validate the token
    if (isset($_POST['form_token']) && $_POST['form_token'] === $session->get('form_token')) {
        // Set form values to the class properties
        $project->status = 1; // Default active status
        $project->title = $_POST["projectName"];
        $project->description = $_POST["projectDescription"];
        $project->type = $_POST["projectType"];
        $project->github_link = $_POST["githubLink"];
        $project->live_link = $_POST["liveLink"];

        if (isset($_POST["updateProject"])) {
            // Update the project
            $projectId = $_POST["projectId"];
            $updated = $project->updateProject($projectId);

            $session->set("msg1", $updated ? "Project updated successfully." : "Failed to update project.");
        } else {
            // Insert new project
            $insertedId = $project->insertProject();
            $session->set("msg1", $insertedId ? "Project submission successful." : "Failed to submit project.");
        }

        $session->set("msg1d", 1);

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
                                <h4><?= $isEdit ? "Edit Project" : "Add Project"; ?></h4>
                            </div>
                            <div class="form-block">
                                <form action="" method="POST">
                                    <!-- Include the token in the form -->
                                    <input type="hidden" name="form_token" value="<?php echo $session->get('form_token') ?>">

                                    <!-- If editing, include project ID -->
                                    <?php if ($isEdit): ?>
                                        <input type="hidden" name="projectId" value="<?= htmlspecialchars($project->project_id); ?>">
                                    <?php endif; ?>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="projectName" class="form-label">Project Title</label>
                                            <input type="text" class="form-control" id="projectName" name="projectName"
                                                value="<?= $isEdit ? htmlspecialchars($project->title) : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="projectDescription" class="form-label">Project Description</label>
                                            <input type="text" class="form-control" id="projectDescription" name="projectDescription"
                                                value="<?= $isEdit ? htmlspecialchars($project->description) : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="projectType" class="form-label">Project Type</label>
                                            <select class="form-control" id="projectType" name="projectType" required>
                                                <option value="personal" <?= $isEdit && $project->type === 'personal' ? 'selected' : ''; ?>>Personal</option>
                                                <option value="academic" <?= $isEdit && $project->type === 'academic' ? 'selected' : ''; ?>>Academic</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="githubLink" class="form-label">Github Link</label>
                                            <input type="text" class="form-control" id="githubLink" name="githubLink"
                                                value="<?= $isEdit ? htmlspecialchars($project->github_link) : ''; ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="liveLink" class="form-label">Live Link</label>
                                            <input type="text" class="form-control" id="liveLink" name="liveLink"
                                                value="<?= $isEdit ? htmlspecialchars($project->live_link) : ''; ?>" required>
                                        </div>
                                    </div>

                                    <div class="button-wrapper text-center">
                                        <button type="submit" class="button" name="<?= $isEdit ? 'updateProject' : 'createProject'; ?>">
                                            <?= $isEdit ? "Update Project" : "Submit Project"; ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Project Section End -->



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