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

$allLabs = $lab->getByFilters();

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

        .section-block {
            border-left: 4px solid #0d6efd;
            padding-left: 1rem;
        }

        .section-title {
            font-size: 1rem;
            margin-bottom: .5rem;
            color: #0d6efd;
            font-weight: 600;
        }

        .section-text {
            font-size: .9rem;
            color: #333;
            line-height: 1.4;
        }

        .file-card {
            transition: transform .2s, box-shadow .2s;
        }

        .file-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .file-icon {
            width: 100%;
            height: 140px;
            background: #f8f9fa;
            font-size: 2rem;
            color: #6c757d;
        }
    </style>
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">

        <!-- Navbar and Header -->
        <?php
        // Include the navbar using __DIR__
        include_once "sidebar.php";
        ?>

        <!-- Section Courser -->
        <div class="container my-4">
            <!-- Section Heading -->
            <div class="section-heading-wrapper mb-4">
                <div class="section-title-wrapper text-center">
                    <h2>Lab Management</h2>
                </div>

                <!-- New search container -->
                <div class="search-by-title text-center mb-3">
                    <p class="mb-1 fw-semibold">Search by Title</p>
                    <div class="d-flex justify-content-center">
                        <input
                            id="search-input"
                            type="text"
                            class="form-control"
                            style="max-width: 400px;"
                            placeholder="Enter lab title...">
                    </div>
                </div>
            </div>


            <!-- Display all labs -->
            <div class="row" id="courseContainer">
                <?php if (!empty($allLabs)): ?>
                    <?php foreach ($allLabs as $row): ?>
                        <?php
                        // Hydrate object
                        $lab->setProperties($row);
                        // Prepare member arrays
                        $names   = json_decode($lab->lab_members_name, true) ?: [];
                        $details = json_decode($lab->lab_members_details, true) ?: [];
                        ?>
                        <div class="col-12 mb-4 lab-card" data-title="<?php echo strtolower($lab->lab_title); ?>">
                            <div class="card h-100 shadow-sm position-relative" style="text-align: justify;">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><?php echo htmlspecialchars($lab->lab_title); ?></h5>
                                    <a href="create-lab.php?editLab=<?php echo $lab->lab_id; ?>"
                                        class="btn btn-lg btn-light text-primary fw-semibold"
                                        style="font-size:1rem; padding:.5rem 1.25rem;"
                                        aria-label="Edit Lab">
                                        ✎ Edit Lab
                                    </a>
                                </div>


                                <div class="card-body px-4">
                                    <!-- About -->
                                    <div class="mb-4 section-block">
                                        <h6 class="section-title">About</h6>
                                        <p class="section-text"><?php echo nl2br(htmlspecialchars($lab->lab_about)); ?></p>
                                    </div>

                                    <!-- Outcomes -->
                                    <div class="mb-4 section-block">
                                        <h6 class="section-title">Outcomes</h6>
                                        <p class="section-text"><?php echo nl2br(htmlspecialchars($lab->lab_outcome)); ?></p>
                                    </div>

                                    <!-- Lab Head -->
                                    <div class="mb-4 section-block">
                                        <h6 class="section-title">Lab Head</h6>
                                        <p class="section-text">
                                            <strong><?php echo htmlspecialchars($lab->lab_head_name); ?></strong><br>
                                            <?php echo nl2br(htmlspecialchars($lab->lab_head_details)); ?>
                                        </p>
                                    </div>

                                    <!-- Members (styled like Head) -->
                                    <?php if (!empty($names)): ?>
                                        <div class="mb-4 section-block">
                                            <h6 class="section-title">Members</h6>
                                            <p class="section-text">
                                                <?php foreach ($names as $i => $member): ?>
                                                    <strong><?php echo htmlspecialchars($member); ?></strong>
                                                    <?php if (!empty($details[$i])): ?>
                                                        — <?php echo htmlspecialchars($details[$i]); ?>
                                                    <?php endif; ?>
                                                    <br>
                                                <?php endforeach; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Files -->
                                    <?php if ($lab->files): ?>
                                        <div class="mb-4 section-block">
                                            <h6 class="section-title">Files</h6>
                                            <div class="row g-3">
                                                <?php
                                                $imgExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                                                foreach (explode(',', $lab->files) as $fid):
                                                    $file->getByFilters($fid);
                                                    $path = "../uploads1/{$file->file_new_name}";
                                                    $ext  = strtolower(pathinfo($file->file_new_name, PATHINFO_EXTENSION));
                                                ?>
                                                    <div class="col-12 col-sm-6 col-md-4">
                                                        <div class="card file-card h-100 text-center">
                                                            <div class="card-body p-2">
                                                                <?php if (in_array($ext, $imgExts)): ?>
                                                                    <img src="<?php echo $path; ?>"
                                                                        class="img-fluid mb-2"
                                                                        style="height:140px; object-fit:cover;"
                                                                        alt="">
                                                                <?php else: ?>
                                                                    <div class="file-icon d-flex align-items-center justify-content-center mb-2">
                                                                        <?php echo strtoupper($ext); ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="card-footer bg-white py-2">
                                                                <div class="small text-truncate"><?php echo htmlspecialchars($file->file_original_name); ?></div>
                                                                <a href="<?php echo $path; ?>" target="_blank"
                                                                    class="btn btn-sm btn-outline-primary mt-1">
                                                                    <?php echo in_array($ext, $imgExts) ? 'View' : 'Download'; ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>


                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">No research labs available at the moment.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination-wrapper text-center mt-4">
                <ul class="pagination justify-content-center" id="paginationNav"></ul>
            </div>
        </div>
        <!-- Section Courser -->


    </div> <!-- sidebar -->

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

    <!-- <script>
        const cards = document.querySelectorAll('#courseContainer > .lab-card');
        document.getElementById('search-input').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            cards.forEach(card => {
                card.style.display = card.dataset.title.includes(q) ? '' : 'none';
            });
        });
    </script> -->

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const allCards = Array.from(document.querySelectorAll('#courseContainer > .lab-card'));
            let filtered = allCards.slice();
            const perPage = 2;
            let currentPage = 1;
            const nav = document.getElementById('paginationNav');

            function renderPage(page) {
                const start = (page - 1) * perPage;
                const end = start + perPage;
                allCards.forEach(card => card.style.display = 'none');
                filtered.slice(start, end).forEach(card => card.style.display = '');
            }

            function buildPagination() {
                nav.innerHTML = '';
                const totalPages = Math.max(1, Math.ceil(filtered.length / perPage));
                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item${i === currentPage ? ' active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    li.addEventListener('click', e => {
                        e.preventDefault();
                        currentPage = i;
                        renderPage(currentPage);
                        nav.querySelectorAll('.page-item').forEach(el => el.classList.remove('active'));
                        li.classList.add('active');
                    });
                    nav.appendChild(li);
                }
            }

            function doFilter(query) {
                filtered = allCards.filter(card =>
                    card.dataset.title.includes(query)
                );
                currentPage = 1;
                buildPagination();
                renderPage(currentPage);
            }

            // Initial setup
            buildPagination();
            renderPage(1);

            // Wire up search
            document.getElementById('search-input').addEventListener('input', e => {
                const q = e.target.value.trim().toLowerCase();
                doFilter(q);
            });
        });
    </script>






</body>

</html>