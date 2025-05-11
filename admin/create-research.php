<?php
// Include session management
include_once "../class-file/session.php";
include_once "../class-file/research.php";

$session = new SessionManager();

// Generate a new CSRF token if it doesn't exist
if ($session->exists('form_token') == false) {
    $session->set('form_token', bin2hex(random_bytes(32))); // Secure random token
}

// Initialize edit mode and pre-fill values if `edit_research` is set
$editMode = false;
$research = new Research();

if (isset($_GET['edit_research'])) {
    $editMode = true;
    $research->research_id = $_GET['edit_research'];
    $research->getResearchById();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate the token
    if (isset($_POST['form_token']) && $_POST['form_token'] === $session->get('form_token')) {
        // Create an instance of the Research class
        $research = new Research();

        // Set the form values to the class properties
        $research->status = 1;
        $research->owner = 0;
        $research->research_type = $_POST["researchType"];
        $research->research_title = $_POST["researchTitle"];
        $research->research_link = $_POST["researchLink"];
        $research->comment = $_POST["comment"];
        $research->published_on = $_POST["publishedOn"];

        // Check if the form was submitted to update or create
        if (isset($_POST['updateResearch'])) {
            // Use the ID from the form value to update
            $research->research_id = $_POST['updateResearch']; // Use the ID passed via the form
            $updateResult = $research->updateResearch();

            if ($updateResult) {
                $session->set("msg1", "Research updated successfully.");
                $session->set("msg1d", 1);
            } else {
                $session->set("msg1", "Failed to update research. Please try again.");
                $session->set("msg1d", 1);
            }
        } elseif (isset($_POST['createResearch'])) {
            // Insert new research
            $insertedId = $research->insertResearch();
            if ($insertedId) {
                $session->set("msg1", "Research submitted successfully.");
                $session->set("msg1d", 1);
            } else {
                $session->set("msg1", "Failed to submit research. Please try again.");
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
                                <h4>Add Research and Publication</h4>
                            </div>
                            <div class="form-block">
                                <form action="" method="POST">
                                    <input type="hidden" name="form_token" value="<?php echo $session->get('form_token'); ?>">

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="researchTitle" class="form-label">Research Title</label>
                                            <input type="text" class="form-control" id="researchTitle" name="researchTitle"
                                                value="<?php echo $editMode ? htmlspecialchars($research->research_title) : ''; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="researchType" class="form-label">Research Type</label>
                                            <select class="form-control" id="researchType" name="researchType" required>
                                                <option value="thesis" <?php echo $editMode && $research->research_type === 'thesis' ? 'selected' : ''; ?>>Thesis</option>
                                                <option value="journal" <?php echo $editMode && $research->research_type === 'journal' ? 'selected' : ''; ?>>Journal</option>
                                                <option value="book" <?php echo $editMode && $research->research_type === 'book' ? 'selected' : ''; ?>>Book</option>
                                                <option value="conference paper" <?php echo $editMode && $research->research_type === 'conference paper' ? 'selected' : ''; ?>>Conference Paper</option>
                                                <option value="other" <?php echo $editMode && $research->research_type === 'other' ? 'selected' : ''; ?>>Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="publishedOn" class="form-label">Published On</label>
                                            <input type="date" class="form-control" id="publishedOn" name="publishedOn"
                                                value="<?php echo $editMode ? htmlspecialchars(substr($research->published_on, 0, 10)) : ''; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="researchLink" class="form-label">Research Link</label>
                                            <input type="text" class="form-control" id="researchLink" name="researchLink"
                                                value="<?php echo $editMode ? htmlspecialchars($research->research_link) : ''; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="comment" class="form-label">Comment</label>
                                            <input type="text" class="form-control" id="comment" name="comment"
                                                value="<?php echo $editMode ? htmlspecialchars($research->comment) : ''; ?>">
                                        </div>
                                    </div>

                                    <div class="button-wrapper d-flex align-items-center justify-content-center text-center">
                                        <button type="submit" class="button" name="<?php echo $editMode ? 'updateResearch' : 'createResearch'; ?>" value="<?php echo $editMode ? $research->research_id : ''; ?>">
                                            <?php echo $editMode ? 'Update Research' : 'Submit'; ?>
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