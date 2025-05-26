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

include_once "../class-file/Research.php";
include_once "../class-file/File.php";

// Initialize objects
$research = new Research();
$file = new FileManager();

// Fetch all research data
$allResearch = $research->getByFilters();
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
        .filter-search-wrap {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .filter-btn {
            cursor: pointer;
            padding: .375rem .75rem;
            border: 1px solid #007bff;
            border-radius: .25rem;
            background: #fff;
            color: #007bff;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background-color: #007bff;
            color: #fff;
        }

        .card-hover {
            transition: transform .2s, box-shadow .2s;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .file-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: .25rem;
            background: #f8f9fa;
        }

        .file-icon {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            font-size: 2rem;
            color: #6c757d;
            border-radius: .25rem;
        }

        .card-title {
            font-size: 1.25rem;
            margin-bottom: .5rem;
        }

        .research-meta {
            font-size: .9rem;
            margin-bottom: .75rem;
            color: #555;
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
        // Include the navbar using __DIR__
        include_once "sidebar.php";
        ?>

        <!-- Research Section -->
        <div class="container my-4">
            <h2 class="mb-4 text-center">Our Research</h2>

            <!-- Filter + Search -->
            <div class="d-flex align-items-center mb-4">
                <div id="filterButtons" class="btn-group">
                    <button class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
                    <?php
                    include_once "../class-file/ResearchType.php";
                    $types = getResearchTypes();
                    foreach ($types as $type):
                    ?>
                        <button class="btn btn-outline-primary filter-btn"
                            data-filter="<?php echo strtolower($type); ?>">
                            <?php echo ucfirst($type); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="ms-auto my-4">
                <label for="searchInput" class="form-label">Search:</label>
                <input id="searchInput"
                    class="form-control ms-auto"
                    placeholder="Search...">
            </div>

            <!-- Research List -->
            <div id="researchContainer" class="list-group">
                <?php foreach ($allResearch as $r): $research->setProperties($r); ?>
                    <div
                        class="list-group-item list-group-item-action mb-3 shadow-sm rounded research-card"
                        data-type="<?php echo strtolower($research->research_type); ?>"
                        data-title="<?php echo htmlspecialchars(strtolower($research->research_title)); ?>"
                        data-abstract="<?php echo htmlspecialchars(strtolower($research->abstract)); ?>">
                        <!-- add a button for edit -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <a href="create-research.php?editResearch=<?php echo $research->research_id; ?>"
                                class="btn btn-lg btn-outline-secondary">Edit</a>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="my-4" style="text-align: justify;"><?php echo htmlspecialchars($research->research_title); ?></h4>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Type:</strong> <?php echo htmlspecialchars($research->research_type); ?></div>
                            <div class="col-sm-4"><strong>Published:</strong> <?php echo htmlspecialchars($research->published_date); ?></div>
                            <div class="col-sm-4"><strong>Accepted:</strong> <?php echo htmlspecialchars($research->accepted_date); ?></div>
                            <div class="col-sm-4"><strong>Journal:</strong> <?php echo htmlspecialchars($research->journal_name); ?></div>
                            <div class="col-sm-4"><strong>DOI:</strong> <?php echo htmlspecialchars($research->doi); ?></div>
                            <div class="col-sm-4"><strong>Publisher:</strong> <?php echo htmlspecialchars($research->publisher); ?></div>
                        </div>

                        <div class="mb-3" style="text-align: justify;">
                            <h6>Abstract</h6>
                            <p class="small text-muted"><?php echo nl2br(htmlspecialchars($research->abstract)); ?></p>
                        </div>

                        <div class="mb-3">
                            <?php if ($research->website_link): ?>
                                <a href="<?php echo htmlspecialchars($research->website_link); ?>"
                                    class="btn btn-sm btn-outline-primary me-2"
                                    target="_blank">Website</a>
                            <?php endif; ?>
                            <?php if ($research->file_url): ?>
                                <a href="<?php echo htmlspecialchars($research->file_url); ?>"
                                    class="btn btn-sm btn-outline-info"
                                    target="_blank">External File</a>
                            <?php endif; ?>
                        </div>

                        <div class="file-section">
                            <h4>Files</h4>
                            <?php if ($research->files): ?>
                                <div class="row">
                                    <?php
                                    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                                    foreach (explode(',', $research->files) as $fid):
                                        $file->getByFilters($fid);
                                        $path = "../uploads1/{$file->file_new_name}";
                                        $ext  = strtolower(pathinfo($file->file_new_name, PATHINFO_EXTENSION));
                                    ?>
                                        <div class="col-6 col-sm-4 col-md-3 mb-4">
                                            <div class="card file-card h-100 shadow-sm rounded">
                                                <a href="<?php echo $path; ?>" target="_blank">
                                                    <?php if (in_array($ext, $imageExts)): ?>
                                                        <img src="<?php echo $path; ?>"
                                                            class="card-img-top rounded-top"
                                                            style="height:120px; object-fit:cover;">
                                                    <?php else: ?>
                                                        <div class="file-icon d-flex align-items-center justify-content-center rounded-top"
                                                            style="height:120px; background:#f8f9fa; border-bottom:1px solid #dee2e6;">
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
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper text-center mt-4">
                <ul class="pagination pagination-lg justify-content-center" id="paginationNav"></ul>
            </div>
        </div>



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

    <!-- Custom JavaScript for Filtering -->
    <script>
        $(function() {
            const $buttons = $('#filterButtons .filter-btn');
            const $cards = $('.research-card');
            let filtered = $cards;
            const perPage = 5; // Number of items per page
            let current = 1;

            function filterSearch() {
                const type = $buttons.filter('.active').data('filter');
                const q = $('#searchInput').val().toLowerCase();
                filtered = $cards.filter(function() {
                    const $c = $(this);
                    const okType = (type === 'all') || ($c.data('type') === type);
                    const okSearch = $c.data('title').includes(q) || $c.data('abstract').includes(q);
                    return okType && okSearch;
                });
                setupPagination();
                showPage(1);
            }

            function setupPagination() {
                const pages = Math.ceil(filtered.length / perPage) || 1;
                const $nav = $('#paginationNav').empty();
                for (let i = 1; i <= pages; i++) {
                    $nav.append(
                        `<li class="page-item ${i===current?'active':''}"><a class="page-link" href="#">${i}</a></li>`
                    );
                }
                $nav.find('.page-item').on('click', function(e) {
                    e.preventDefault();
                    current = +$(this).text();
                    showPage(current);
                    $nav.find('.page-item').removeClass('active');
                    $(this).addClass('active');
                });
            }

            function showPage(page) {
                $cards.hide();
                filtered.slice((page - 1) * perPage, page * perPage).show();
            }

            $buttons.on('click', function() {
                $buttons.removeClass('active');
                $(this).addClass('active');
                filterSearch();
            });
            $('#searchInput').on('input', filterSearch);
            filterSearch();
        });
    </script>

</body>

</html>