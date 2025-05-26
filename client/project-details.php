<?php
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
$session::ensureSessionStarted();

$loggedIn = false;
if ($session::get("user")) {
    $loggedIn = true;
}

if (isset($_GET["project_id"])) {
    $projectId = intval($_GET["project_id"]);
} else {
    echo "<script>window.alert('Invalid project ID.');</script>";
    exit;
}

include_once "../class-file/Project.php";
include_once "../class-file/File.php";

// Initialize objects
$project = new Project();
$file = new FileManager();

// Auto set all value to its properties
$project->getByFilters($projectId);
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
        .section-courses {
            padding: 2rem 0;
            background: #f8f9fa;
        }

        .project-card-wrapper {
            background: #fff;
            border-radius: .5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .project-details-content h3 {
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .project-details-content p {
            margin-bottom: .75rem;
        }

        .file-section h4 {
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .file-card {
            transition: transform .2s;
        }

        .file-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .file-section h4 {
            margin-bottom: 1rem;
        }

        .file-card {
            height: 100%;
        }

        .file-card img,
        .file-card .file-icon {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
            margin-bottom: .5rem;
            border-radius: .25rem;
            background-color: #f8f9fa;
        }

        .file-card .file-icon {
            font-size: 3rem;
            line-height: 120px;
            color: #6c757d;
            text-align: center;
        }

        .file-card .card-title {
            font-size: .85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

        <section class="section-courses">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="display-4">Project</h2>
                </div>

                <div class="project-card-wrapper">
                    <!-- Details (full width) -->
                    <div class="project-details-content mb-4">
                        <h3><?php echo htmlspecialchars($project->title); ?></h3>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($project->project_type); ?></p>
                        <p style="text-align:justify;">
                            <strong>Description:</strong>
                            <?php echo htmlspecialchars($project->description); ?>
                        </p>
                        <?php if ($project->github_link): ?>
                            <p><strong>GitHub:</strong>
                                <a href="<?php echo htmlspecialchars($project->github_link); ?>" target="_blank">
                                    <?php echo htmlspecialchars($project->github_link); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ($project->live_link): ?>
                            <p><strong>Live:</strong>
                                <a href="<?php echo htmlspecialchars($project->live_link); ?>" target="_blank">
                                    <?php echo htmlspecialchars($project->live_link); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Files (full width below) -->
                    <div class="file-section">
                        <h4>Files</h4>
                        <?php if ($project->files): ?>
                            <div class="row">
                                <?php
                                $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                                foreach (explode(',', $project->files) as $fid):
                                    $file->getByFilters($fid);
                                    $path = "../uploads1/{$file->file_new_name}";
                                    $ext  = strtolower(pathinfo($file->file_new_name, PATHINFO_EXTENSION));
                                ?>
                                    <div class="col-6 col-sm-4 col-md-3 mb-4">
                                        <div class="card file-card h-100">
                                            <a href="<?php echo $path; ?>" target="_blank">
                                                <?php if (in_array($ext, $imageExts)): ?>
                                                    <img src="<?php echo $path; ?>"
                                                        class="card-img-top"
                                                        style="height:120px; object-fit:cover;">
                                                <?php else: ?>
                                                    <div class="file-icon d-flex align-items-center justify-content-center"
                                                        style="height:120px;">
                                                        <?php echo strtoupper($ext); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </a>
                                            <div class="card-body p-2 text-center">
                                                <h6 class="card-title text-truncate mb-2">
                                                    <?php echo htmlspecialchars($file->file_original_name); ?>
                                                </h6>
                                                <a href="<?php echo $path; ?>"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <?php echo in_array($ext, $imageExts) ? 'View' : 'Download'; ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No files attached.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>


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

    <!-- at bottom of page, initialize Fancybox -->
    <script>
        $(document).ready(function() {
            $('[data-fancybox="gallery"]').fancybox({
                buttons: [
                    'zoom',
                    'slideShow',
                    'thumbs',
                    'close'
                ]
            });
        });
    </script>
</body>

</html>