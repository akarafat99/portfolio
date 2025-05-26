<?php
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
$session::ensureSessionStarted();

$loggedIn = false;
if ($session::get("user")) {
  $loggedIn = true;
}

include_once "../class-file/Research.php";
include_once "../class-file/File.php";

// Initialize objects
$research = new Research();
$file = new FileManager();

// Fetch all research data
$allResearch = $research->getByFilters(null, 1);
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
    $loggedIn ? include_once "navbar-1.php" : include_once "navbar-2.php";
    ?>

    <!-- Research Section -->
    <div class="container my-4 section-courses">
      <h2 class="mb-4 text-center">Researches</h2>

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

            <!-- print almost 100 characters of abstract -->
            <div class="mb-3" style="text-align: justify;">
              <h6>Abstract</h6>
              <p class="small text-muted"><?php echo nl2br(substr($research->abstract, 0, 100)); ?>...</p>
            </div>

            <!-- a button to view details -->
            <div class="text-center">
              <a href="research-details.php?researchId=<?php echo $research->research_id; ?>"
                class="btn btn-primary">View Details</a>
            </div>

          </div>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper text-center mt-4">
        <ul class="pagination pagination-lg justify-content-center" id="paginationNav"></ul>
      </div>
    </div>

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