<?php
// Include session management and Research class
include_once "../class-file/session.php";
include_once "../class-file/research.php";

// Create a new instance of the Research class
$research = new Research();
$allResearch = $research->getAllResearch(); // Fetch all research records
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
    <link rel="stylesheet" href="../css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="../fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="../css/aos.css">
    <link rel="stylesheet" href="../css/style.css">




</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">

        <!-- Navbar and Header -->
        <?php
        // Include the navbar using __DIR__
        include_once "sidebar.php";
        ?>

        <!-- Research Section -->
        <section class="section-courses">
            <div class="container">
                <!-- Section Heading -->
                <div class="section-heading-wrapper mb-4">
                    <div class="section-title-wrapper text-center">
                        <h2>Our Research</h2>
                    </div>
                    <div class="filter-search-wrap">
                        <div class="filter-button-wrap" id="courseFilter">
                            <button class="filter-btn active" value="all">All</button>
                            <button class="filter-btn" value="thesis">Thesis</button>
                            <button class="filter-btn" value="journal">Journal</button>
                            <button class="filter-btn" value="book">Book</button>
                            <button class="filter-btn" value="conference paper">Conference Paper</button>
                            <button class="filter-btn" value="other">Other</button>
                        </div>
                        <input id="search-input" type="text" placeholder="Search..">
                    </div>
                </div>

                <!-- Course Cards -->
                <div class="row" id="courseContainer">
                    <?php if ($allResearch): ?>
                        <?php foreach ($allResearch as $research): ?>
                            <div class="col-md-6" data-category="<?php echo strtolower($research['research_type']); ?>">
                                <div class="course-card-wrapper">
                                    <div class="course-card">
                                        <!-- Edit Button -->
                                        <div class="button-wrapper edit mb-3">
                                            <a href="create-research.php?edit_research=<?php echo $research['research_id']; ?>" class="button edit-btn">Edit</a>
                                        </div>

                                        <!-- Research Title -->
                                        <div class="research-card-heading-content">
                                            <h5 class="course-title"><?php echo htmlspecialchars($research['research_title']); ?></h5>
                                        </div>

                                        <!-- Research Details -->
                                        <p><strong>Research Type:</strong> <?php echo htmlspecialchars($research['research_type']); ?></p>
                                        <p><strong>Short description:</strong> <?php echo htmlspecialchars($research['comment']); ?></p>
                                        <p><strong>Published on:</strong>
                                            <?php
                                            $publishedDate = new DateTime($research['published_on']);
                                            echo htmlspecialchars($publishedDate->format('Y-m-d'));
                                            ?>
                                        </p>

                                        <!-- Research Link -->
                                        <?php if (!empty($research['research_link'])): ?>
                                            <div class="button-wrapper mb-3">
                                                <a href="<?php echo htmlspecialchars($research['research_link']); ?>" target="_blank" class="button">Research Link</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No research data available.</p>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Pagination Navigation -->
            <div class="pagination-wrapper text-center mt-4">
                <ul class="pagination" id="paginationNav">
                    <!-- Pagination buttons will be dynamically generated here -->
                </ul>
            </div>
        </section>


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
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                const filterValue = this.value;

                // Toggle active class on buttons
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                // Filter the cards
                const cards = document.querySelectorAll('#courseContainer .col-md-6');
                cards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    const searchQuery = document.querySelector('#search-input').value.toLowerCase();
                    const cardText = card.querySelector('.course-title').innerText.toLowerCase();

                    // Filter by both category and search query
                    const matchesCategory = filterValue === 'all' || filterValue === category;
                    const matchesSearch = searchQuery === '' || cardText.includes(searchQuery);

                    if (matchesCategory && matchesSearch) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Search Functionality
        document.querySelector('#search-input').addEventListener('input', function() {
            const searchQuery = this.value.toLowerCase();
            const filterValue = document.querySelector('.filter-btn.active').value;

            // Filter the cards
            const cards = document.querySelectorAll('#courseContainer .col-md-6');
            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                const cardText = card.querySelector('.course-title').innerText.toLowerCase();

                // Filter by both category and search query
                const matchesCategory = filterValue === 'all' || filterValue === category;
                const matchesSearch = searchQuery === '' || cardText.includes(searchQuery);

                if (matchesCategory && matchesSearch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>