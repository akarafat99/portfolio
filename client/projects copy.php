<?php
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
$session::ensureSessionStarted();
$loggedIn = false;
if ($session::get("user")) {
    $loggedIn = true;
}

include_once "../class-file/Lab.php";
include_once "../class-file/File.php";
$lab = new Lab();
$file = new FileManager();

if (isset($_GET['labId'])) {
    $lab->lab_id = $_GET['labId'];
    $lab->getByFilters($lab->lab_id);
} else {
    echo "<script>window.alert('Lab ID not provided.');</script>";
    echo "<script>window.location.href = 'lab.php';</script>";
    exit();
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
        if ($loggedIn) {
            include_once "navbar-1.php";
        } else {
            include_once "navbar-2.php";
        }
        ?>

        <!-- Section lab -->
        <section class="section-courses">
            <div class="container">
                <!-- Section Heading -->
                <div class="section-heading-wrapper mb-4">
                    <div class="section-title-wrapper text-center">
                        <h2><?php echo htmlspecialchars($lab->lab_title); ?></h2>
                    </div>
                </div>

                <!-- Display all labs -->
                <div class="row" id="courseContainer">
                    <?php
                    // Prepare member arrays
                    $names   = json_decode($lab->lab_members_name, true) ?: [];
                    $details = json_decode($lab->lab_members_details, true) ?: [];
                    ?>
                    <div class="col-12 mb-4 lab-card" data-title="<?php echo strtolower($lab->lab_title); ?>">
                        <div class="card lab-card h-100 shadow-sm position-relative" style="text-align: justify;">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo htmlspecialchars($lab->lab_title); ?></h5>
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
                                <?php else: ?>
                                    <div class="mb-4 section-block">
                                        <h6 class="section-title">Files</h6>
                                        <p class="section-text">No files available.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>
        <!-- Section lab -->

        <!-- Contact section -->
        <div class="contact-section" id="contact">
            <div class="container">
                <div class="contact-contents-wrapper">
                    <div class="contact-heading-wrapper">
                        <h2 class="contact-heading">Contact Me</h2>
                        <p class="section-sub-text contact-description">If you have any questions or want to know more
                            about me,
                            feel free to
                            contact me.</p>
                    </div>
                    <div class="contact-cards-address-wrapper">
                        <div class="contact-card">
                            <div class="contact-card-icon-wrap">
                                <svg width="25" height="34" fill="currentColor" stroke="currentColor"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path
                                        d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z">
                                    </path>
                                </svg>
                            </div>
                            <div class="contact-address-content-wrap">
                                <h3>Call Me</h3>
                                <div class="contact-address-link-wrap">
                                    <a href="tel:+8801712345678" class="contact-address-link">+880 1712345678</a>
                                    <a href="tel:+8801712345678" class="contact-address-link">+880 1712345678</a>
                                </div>
                            </div>
                        </div>
                        <div class="contact-card">
                            <div class="contact-card-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-mail">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <div class="contact-address-content-wrap">
                                <h3>Email Me</h3>
                                <div class="contact-address-link-wrap">
                                    <a href="mailto:+8801712345678"
                                        class="contact-address-link">m.halder@just.edu.bd</a>
                                </div>
                            </div>
                        </div>
                        <div class="contact-card">
                            <div class="contact-card-icon-wrap">
                                <svg width="32" height="34" fill="currentColor" stroke="currentColor"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                    <path
                                        d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 256c-35.3 0-64-28.7-64-64s28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64z">
                                    </path>
                                </svg>
                            </div>
                            <div class="contact-address-content-wrap">
                                <h3>Address</h3>
                                <div class="contact-address-link-wrap">
                                    <p>Room No-216, Department of Computer Science and Engineering, <br>Jashore
                                        University of Science
                                        and
                                        Technology, Jashore</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Wrapper -->
                <div class="map-wrapper">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3666.2652186562736!2d89.125418!3d23.233434!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ff1857827d6cb7%3A0xecab69d917b1a29b!2sJashore%20University%20of%20Science%20and%20Technology!5e0!3m2!1sen!2sus!4v1733252892050!5m2!1sen!2sus"
                        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        <!-- Contact section -->

        <!-- Footer -->
        <footer class="footer">
            <div class="container text-center">
                <p class="mb-0">© 2025 Monishanker Halder. All Rights Reserved.</p>
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
        $(function() {
            const $buttons = $('#filterButtons .filter-btn');
            const $cards = $('.project-card');
            let filtered = $cards;
            const itemsPerPage = 5;
            let currentPage = 1;

            function filterAndSearch() {
                const type = $buttons.filter('.active').data('filter');
                const query = $('#searchInput').val().toLowerCase();
                filtered = $cards.filter(function() {
                    const $c = $(this);
                    const matchesType = (type === 'all') || ($c.data('type') === type);
                    const title = $c.data('title'),
                        desc = $c.data('desc');
                    const matchesSearch = title.includes(query) || desc.includes(query);
                    return matchesType && matchesSearch;
                });
                setupPagination();
                showPage(1);
            }

            function setupPagination() {
                const totalPages = Math.ceil(filtered.length / itemsPerPage) || 1;
                const $nav = $('#paginationNav').empty();
                for (let i = 1; i <= totalPages; i++) {
                    const activeClass = i === currentPage ? 'active' : '';
                    $nav.append(`<li class="page-item ${activeClass}"><a href="#">${i}</a></li>`);
                }
                $nav.find('.page-item').on('click', function(e) {
                    e.preventDefault();
                    currentPage = Number($(this).text());
                    showPage(currentPage);
                    $nav.find('.page-item').removeClass('active');
                    $(this).addClass('active');
                });
            }

            function showPage(page) {
                $cards.hide();
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                filtered.slice(start, end).show();
            }

            $buttons.on('click', function() {
                $buttons.removeClass('active');
                $(this).addClass('active');
                filterAndSearch();
            });
            $('#searchInput').on('input', filterAndSearch);
            filterAndSearch();
        });
    </script>

</body>

</html>