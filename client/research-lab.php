<?php
// Include session management and ResearchLab class
include_once "../class-file/session.php";
include_once "../class-file/research-lab.php";

$session = new SessionManager();
$researchLab = new ResearchLab();

$researchLab->status = 1;
$allResearchLabs = $researchLab->getAllResearchLabs();

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

    <style>
        .search-right {
            display: flex;
            justify-content: flex-end;
        }
    </style>
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">

        <!-- Navbar and Header -->
        <?php
        // Include the navbar using __DIR__
        include_once "navbar.php";
        ?>

        <!-- Section Courser -->
        <section class="section-courses">
            <div class="container">
                <!-- Section Heading -->
                <div class="section-heading-wrapper mb-4">
                    <div class="section-title-wrapper text-center">
                        <h2>Research Labs</h2>
                    </div>
                    <div class="filter-search-wrap search-right">
                        <input id="search-input" type="text" placeholder="Search..">
                    </div>

                </div>

                <!-- Display all research labs -->
                <div class="row" id="courseContainer">
                    <?php if (!empty($allResearchLabs)): ?>
                        <?php foreach ($allResearchLabs as $lab): ?>
                            <?php
                            // Sanitize data to prevent XSS attacks
                            $labId = htmlspecialchars($lab['lab_id'], ENT_QUOTES, 'UTF-8');
                            $labTitle = htmlspecialchars($lab['lab_title'], ENT_QUOTES, 'UTF-8');
                            $createdDate = htmlspecialchars($lab['created'], ENT_QUOTES, 'UTF-8');
                            $modifiedDate = htmlspecialchars($lab['modified'], ENT_QUOTES, 'UTF-8');
                            ?>
                            <div class="col-md-6 mb-4">
                                <div class="course-card-wrapper">
                                    <div class="course-card">
                                        <h5 class="course-title"><?php echo $labTitle; ?></h5>
                                        <h7>Lab ID: <?php echo $labId; ?></h7>
                                        <p class="course-text mb-1"><strong>Created:</strong> <?php echo $createdDate; ?></p>
                                        <p class="course-text mb-1"><strong>Modified:</strong> <?php echo $modifiedDate; ?></p>
                                        <div class="button-wrapper mt-4">
                                            <a href="research-lab-dashboard.php?lab_id=<?php echo $labId; ?>" class="button">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No research labs available at the moment.</p>
                    <?php endif; ?>
                </div>


            </div>
        </section>
        <!-- Section Courser -->



        <!-- Footer -->
        <footer class="footer">
            <div class="container text-center">
                <p class="mb-0">© 2024 Your Website Name. All Rights Reserved.</p>
                <small>Designed with ❤️ by Your Name</small>
            </div>
        </footer>
        <!-- Footer -->




    </div> <!-- .site-wrap -->

    <script src=" js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/jquery.sticky.js"></script>


    <script src="js/main.js"></script>



    <!-- Add the search functionality script -->
    <script>
        // Get the search input and course container elements
        const searchInput = document.getElementById('search-input');
        const courseContainer = document.getElementById('courseContainer');

        // Add an event listener to the search input
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase(); // Convert search term to lowercase
            const cards = courseContainer.querySelectorAll('.col-md-6'); // Select all course cards

            cards.forEach(card => {
                const title = card.querySelector('.course-title').textContent.toLowerCase(); // Get course title
                const roomCode = card.querySelector('.course-text').textContent.toLowerCase(); // Get room code text

                // Check if the search term is included in the title or room code
                if (title.includes(searchTerm) || roomCode.includes(searchTerm)) {
                    card.style.display = ''; // Show card
                } else {
                    card.style.display = 'none'; // Hide card
                }
            });
        });
    </script>


</body>

</html>