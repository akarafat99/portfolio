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

$allProjects = $project->getByFilters();
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
        .file-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            /* space between items */
        }

        .file-wrapper {
            text-align: center;
            width: 100px;
            /* match your thumbnail size */
        }

        .file-wrapper img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }

        .file-wrapper .view-full {
            display: block;
            margin-top: 4px;
            font-size: 0.85rem;
            color: #007bff;
            text-decoration: none;
        }

        .file-wrapper .view-full:hover {
            text-decoration: underline;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
        }

        .pagination {
            list-style: none;
            display: flex;
            gap: 8px;
            padding: 0;
        }

        .page-item {
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .page-item a {
            text-decoration: none;
            color: #007bff;
        }

        .page-item.active {
            background-color: #007bff;
            color: white;
            pointer-events: none;
        }


        .page-item:hover {
            background-color: #f0f0f0;
            border-color: #007bff;
        }

        .filter-search-wrap {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-btn {
            cursor: pointer;
            margin-right: .5rem;
        }

        .filter-btn.active {
            font-weight: bold;
        }

        .file-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .file-wrapper {
            text-align: center;
            width: 80px;
        }

        .file-wrapper img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .pagination {
            list-style: none;
            display: flex;
            gap: 8px;
            padding: 0;
        }

        .page-item {
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .page-item a {
            display: block;
            padding: 0.5rem 0.75rem;
            color: #007bff;
            text-decoration: none;
        }

        .page-item.active {
            background-color: #007bff;
            pointer-events: none;
        }

        .page-item.active a {
            color: #fff;
        }

        .page-item:hover {
            background-color: #f0f0f0;
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

        <section class="section-courses">
            <div class="container">
                <!-- Section Heading -->
                <div class="section-heading-wrapper mb-4">
                    <div class="section-title-wrapper text-center">
                        <h2>Projects</h2>
                    </div>
                    <!-- Filter and Search -->
                    <div class="filter-search-wrap">
                        <div id="filterButtons">
                            <button class="filter-btn active" data-filter="all">All</button>
                            <button class="filter-btn" data-filter="personal">Personal</button>
                            <button class="filter-btn" data-filter="academic">Academic</button>
                        </div>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search projects...">
                    </div>
                </div>

                <!-- Listing -->
                <div class="row" id="projectContainer">
                    <?php foreach ($allProjects as $p): $project->setProperties($p); ?>
                        <div class="col-md-12 mb-4 project-card" data-type="<?php echo $project->project_type; ?>" data-title="<?php echo htmlspecialchars(strtolower($project->title)); ?>" data-desc="<?php echo htmlspecialchars(strtolower($project->description)); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <!-- a button on the right for edit -->
                                    <div class="d-flex justify-content-end">
                                        <a href="create-project.php?editProject=<?php echo $project->project_id; ?>" class="btn btn-lg btn-primary">Edit</a>
                                    </div>
                                    <h5><?php echo htmlspecialchars($project->title); ?></h5>
                                    <p><strong>Type:</strong> <?php echo htmlspecialchars($project->project_type); ?></p>
                                    <p style="text-align:justify;" class="card-text"><?php echo htmlspecialchars($project->description); ?></p>
                                    <!-- file part -->
                                    <?php if ($project->files): ?>
                                        <div class="file-container d-flex flex-wrap gap-2 mb-2">
                                            <?php
                                            // define which extensions to treat as images
                                            $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                                            foreach (explode(',', $project->files) as $fid):
                                                $file->getByFilters($fid);
                                                $path = "../uploads1/{$file->file_new_name}";
                                                // extract the extension from file_new_name
                                                $ext  = strtolower(pathinfo($file->file_new_name, PATHINFO_EXTENSION));
                                            ?>
                                                <div class="file-wrapper text-center" style="width:80px;">
                                                    <a href="<?php echo $path; ?>" target="_blank">
                                                        <?php if (in_array($ext, $imageExts)): ?>
                                                            <!-- thumbnail for images -->
                                                            <img src="<?php echo $path; ?>"
                                                                alt="<?php echo htmlspecialchars($file->file_original_name); ?>"
                                                                class="img-thumbnail"
                                                                style="width:80px;height:80px;object-fit:cover;">
                                                        <?php else: ?>
                                                            <!-- generic box for non-images -->
                                                            <div class="file-icon"
                                                                style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;border:1px solid #ccc;background-color:#f8f9fa;">
                                                                <?php echo strtoupper($ext); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </a>
                                                    <a href="<?php echo $path; ?>"
                                                        target="_blank"
                                                        class="d-block small mt-1">
                                                        <?php echo in_array($ext, $imageExts) ? 'View full' : 'Download'; ?>
                                                        <p class="small text-muted">
                                                            <?php echo htmlspecialchars($file->file_original_name); ?>
                                                        </p>
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <p>
                                        <strong>Published on:</strong>
                                        <?php echo date('F j, Y', strtotime($project->created_at)); ?>
                                    </p>
                                    <p>
                                        <strong>Last updated:</strong>
                                        <?php echo date('F j, Y', strtotime($project->modified_at)); ?>
                                    </p>
                                    <?php if ($project->github_link): ?><a href="<?php echo $project->github_link; ?>" class="btn btn-lg btn-outline-primary" target="_blank">GitHub</a><?php endif; ?>
                                    <?php if ($project->live_link): ?><a href="<?php echo $project->live_link; ?>" class="btn btn-lg btn-outline-success" target="_blank">Live</a><?php endif; ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <ul class="pagination" id="paginationNav"></ul>
            </div>

        </section>

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