<?php
// Include session management and ResearchLab class
include_once "../class-file/session.php";
include_once "../class-file/research-lab.php";

$session = new SessionManager();
$researchLab = new ResearchLab();
$editMode = 0;

if (isset($_GET['editLab'])) {
    $researchLab->lab_id = $_GET['editLab'];
    $researchLab->status = 1;
    $editMode = $researchLab->getResearchLabById(); // Load data for editing
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Directly assign POST data to object properties
    $researchLab->status = 1; // Assuming status is always 1 for new labs
    $researchLab->lab_title = $_POST['labTitle'] ?? '';
    $researchLab->lab_about = $_POST['labAbout'] ?? '';
    $researchLab->lab_outcomes = $_POST['labOutcome'] ?? '';
    $researchLab->lab_head_name = $_POST['labHeadName'] ?? '';
    $researchLab->lab_head_details = $_POST['labHeadDetails'] ?? '';

    if (isset($_POST['lab-member-name']) && !empty($_POST['lab-member-name'])) {
        $filteredNames = [];
        $filteredDetails = [];

        // Loop through each name and check if it's not empty
        foreach ($_POST['lab-member-name'] as $index => $name) {
            if (!empty($name)) {
                $filteredNames[] = $name;
                $filteredDetails[] = $_POST['lab-member-details'][$index];
            }
        }

        // If we have valid names, store them, otherwise keep the empty string
        $researchLab->lab_members_name = !empty($filteredNames) ? implode('#', $filteredNames) : '';
        $researchLab->lab_members_details = !empty($filteredDetails) ? implode('#', $filteredDetails) : '';
    } else {
        // If no members are provided, assign empty strings
        $researchLab->lab_members_name = '';
        $researchLab->lab_members_details = '';
    }


    // Determine if it's an update or insert
    if (isset($_POST['updateLab']) && $_POST['updateLab'] > 0) {
        // Update operation
        $researchLab->lab_id = $_POST['updateLab'];
        $updateSuccess = $researchLab->updateResearchLab(); // Call update method

        if ($updateSuccess) {
            $session->set("msg1", "Lab updated successfully.");
        } else {
            $session->set("msg1", "Failed to update lab.");
        }
    } else {
        // Insert operation
        $saveSuccess = $researchLab->insertResearchLab(); // Call insert method

        if ($saveSuccess) {
            $session->set("msg1", "Lab created successfully.");
        } else {
            $session->set("msg1", "Failed to create lab.");
        }
    }
    $session->set("msg1d", 1);

    // Echoing each variable
    // echo "<h3>Lab Details:</h3>";
    // echo "<strong>Lab Title:</strong> " . $researchLab->lab_title . "<br>";
    // echo "<strong>Lab About:</strong> " . $researchLab->lab_about . "<br>";
    // echo "<strong>Lab Outcomes:</strong> " . $researchLab->lab_outcomes . "<br>";
    // echo "<strong>Lab Head Name:</strong> " . $researchLab->lab_head_name . "<br>";
    // echo "<strong>Lab Head Details:</strong> " . $researchLab->lab_head_details . "<br>";
    // echo "<strong>Lab Members Names:</strong> " . $researchLab->lab_members_name . "<br>";
    // echo "<strong>Lab Members Details:</strong> " . $researchLab->lab_members_details . "<br>";

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
    </style>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
    <div class="site-wrap">

        <?php include_once "sidebar.php"; ?>

        <div class="container py-4">
            <div class="lab-contents-wrapper">
                <h2 class="mb-4 text-center">Research Lab</h2>
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
                <form id="lab-form" method="POST" action="">
                    <div class="mb-3">
                        <label for="lab-title-input" class="form-label">Lab Title</label>
                        <input type="text" id="lab-title-input" name="labTitle" class="form-control" value="<?php echo $editMode ? $researchLab->lab_title : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="lab-desc-input" class="form-label">Lab About</label>
                        <input id="lab-desc-input" name="labAbout" class="form-control" value="<?php echo $editMode ? $researchLab->lab_about : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="lab-desc-input" class="form-label">Lab Outcomes</label>
                        <input id="lab-desc-input" name="labOutcome" class="form-control" value="<?php echo $editMode ? $researchLab->lab_outcomes : ''; ?>" required>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="lab-desc-input" class="form-label">Lab Head Name</label>
                        <input id="lab-desc-input" name="labHeadName" class="form-control" value="<?php echo $editMode ? $researchLab->lab_head_name : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lab-desc-input" class="form-label">Lab Head Details</label>
                        <input id="lab-desc-input" name="labHeadDetails" class="form-control" value="<?php echo $editMode ? $researchLab->lab_head_details : ''; ?>" required>
                    </div>

                    <hr>

                    <div id="lab-members-container">
                        <h5>Lab Members</h5>
                        <?php if ($editMode && !empty($researchLab->lab_members_name) && isset($researchLab->lab_members_name)): ?>
                            <?php
                            $names = explode('#', $researchLab->lab_members_name);
                            $details = explode('#', $researchLab->lab_members_details);
                            ?>
                            <?php foreach ($names as $index => $name): ?>
                                <?php if (!empty($name)): ?>
                                    <div class="lab-member-group mb-3">
                                        <div class="mb-2">
                                            <label for="lab-member-name-<?php echo $index; ?>" class="form-label">Member Name</label>
                                            <input
                                                type="text"
                                                id="lab-member-name-<?php echo $index; ?>"
                                                name="lab-member-name[]"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="mb-2">
                                            <label for="lab-member-details-<?php echo $index; ?>" class="form-label">Member Details</label>
                                            <input
                                                id="lab-member-details-<?php echo $index; ?>"
                                                name="lab-member-details[]"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($details[$index], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>



                    <div class="d-flex justify-content-between">
                        <button type="button" id="add-member-button" class="btn btn-primary mb-3">Add Lab Member(s)</button>
                    </div>
                    <div class="d-flex justify-content-between justify-content-md-center">
                        <button type="submit"
                            class="btn btn-success btn-secondary"
                            name="<?php echo $editMode ? 'updateLab' : 'createLab'; ?>"
                            value="<?php echo $editMode ? $researchLab->lab_id : 0; ?>">
                            <?php echo $editMode ? "Update" : "Create"; ?>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- Footer -->
        <footer class="footer mt-5">
            <div class="container text-center">
                <p class="mb-0">&copy; 2024 Your Website Name. All Rights Reserved.</p>
                <small>Designed with ❤️ by Your Name</small>
            </div>
        </footer>
        <!-- Footer End -->
    </div>

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        let memberCount = 1; // Track the number of lab member groups

        // Handle "Add More Member" button click
        document.getElementById('add-member-button').addEventListener('click', function() {
            memberCount++;

            // Create a new lab member group
            const memberGroup = document.createElement('div');
            memberGroup.classList.add('lab-member-group', 'mb-3');

            memberGroup.innerHTML = `
                <div class="mb-2">
                    <label for="lab-member-name-${memberCount}" class="form-label">Member Name</label>
                    <input type="text" id="lab-member-name-${memberCount}" name="lab-member-name[]" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="lab-member-details-${memberCount}" class="form-label">Member Details</label>
                    <input id="lab-member-details-${memberCount}" name="lab-member-details[]" class="form-control" rows="2"></input>
                </div>
            `;

            // Append the new member group to the container
            document.getElementById('lab-members-container').appendChild(memberGroup);
        });

        // Handle form submission (for demonstration purposes)
        // document.getElementById('lab-form').addEventListener('submit', function(event) {
        //     event.preventDefault();
        //     alert('Form submitted successfully!');
        // });
    </script>
</body>

</html>