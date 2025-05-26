<?php
include_once "class-file/SessionManager.php";
$session = SessionStatic::class;
$session::ensureSessionStarted();

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
  <link rel="stylesheet" href="fonts/icomoon/style.css">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">

  <link rel="stylesheet" href="css/jquery.fancybox.min.css">

  <link rel="stylesheet" href="css/bootstrap-datepicker.css">

  <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

  <link rel="stylesheet" href="css/aos.css">

  <link rel="stylesheet" href="css/style.css">

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

  <?php
  if($session::get("user")) {
      include_once "client/navbar-1.php";
  } else {
      include_once "client/navbar-2.php";
  }
  ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="hero-contents-wrapper">
        <div class="hero-image-wrapper">
          <img src="./images/monishanker-halder-1.png" alt="">
        </div>
        <div class="hero-intro-content-wrapper">
          <p class="hero-greeting">HELLO I'M</p>
          <h1 class="hero-name">Monishanker Halder</h1>
          <p class="hero-role">Assistant Professor, CSE at JUST</p>
          <div class="intro-description">
            <p>Monishanker Halder completed his B.Sc. (Engg.) and M.Sc. (Engg.) in Computer Science and Engineering
              from Jashore University of Science and Technology in 2013 and 2015 respectively. From April, 2017 he
              joined as a Lecturer in the department of Computer Science and Engineering at Jashore University of
              Science and Technology, Bangladesh. His research interest includes Deep learning, Computer Vision, Image
              Processing, Software Defined Networking, Internet of Things etc. </p>
          </div>
          <!-- <div class="button-wrapper">
              <a href="#" class="button-outline">About Me</a> -->

          <div class="social-wrapper">
            <p>Follow Me:</p>
            <div class="social-icon-wrap">
              <a href="#" target="_blank" class="social-link">
                <img src="./images/facebook.png" alt="">
              </a>
              <a href="#" target="_blank" class="social-link">
                <img src="./images/github.png" alt="">
              </a>
              <a href="https://scholar.google.com/citations?user=z1Fj-dMAAAAJ&hl=en" target="_blank" class="social-link">
                <img src="./images/graduation-cap.png" alt="">
              </a>
              <!-- </div> -->
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- Hero Section -->

  <!-- Expertise Section -->
  <section class="expertise-section" id="expertise">
    <div class="container">
      <div class="expertise-heading-wrapper">
        <h2 class="expertise-heading">Expertise</h2>
        <p class="section-sub-text">I have a wide range of expertise in the field of Computer Science and Engineering.
          Here are some of my expertise.</p>
      </div>
      <div class="timeline">
        <!-- Timeline Item -->
        <div class="timeline-item">
          <div class="timeline-icon">
            <svg width="25" height="40" fill="currentColor" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512 512">
              <path
                d="M184 48H328c4.4 0 8 3.6 8 8V96H176V56c0-4.4 3.6-8 8-8zm-56 8V96H64C28.7 96 0 124.7 0 160v96H192 320 512V160c0-35.3-28.7-64-64-64H384V56c0-30.9-25.1-56-56-56H184c-30.9 0-56 25.1-56 56zM512 288H320v32c0 17.7-14.3 32-32 32H224c-17.7 0-32-14.3-32-32V288H0V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V288z">
              </path>
            </svg>
          </div>
          <div class="timeline-item-content">
            <h4>M.Sc. (Engg.) in Computer Science and Engineering<small></small></h4>
            <p>Jashore University of Science and Technology</p>
            <small>Duration: 2015 - 2018</small>
          </div>
        </div>
        <!-- Timeline Item -->
        <div class="timeline-item left">
          <div class="timeline-icon">
            <svg width="25" height="44" fill="currentColor" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 640 512">
              <path
                d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9v28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5V291.9c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z">
              </path>
            </svg>
          </div>
          <div class="timeline-item-content">
            <h4>B.Sc. (Engg.) in Computer Science and Engineering<small></small></h4>
            <p>Jashore University of Science and Technology</p>
            <small>Duration: 2010 - 2015</small>
          </div>
        </div>
        <!-- Timeline Item -->
        <div class="timeline-item">
          <div class="timeline-icon">
            <svg width="25" height="40" fill="currentColor" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512 512">
              <path
                d="M184 48H328c4.4 0 8 3.6 8 8V96H176V56c0-4.4 3.6-8 8-8zm-56 8V96H64C28.7 96 0 124.7 0 160v96H192 320 512V160c0-35.3-28.7-64-64-64H384V56c0-30.9-25.1-56-56-56H184c-30.9 0-56 25.1-56 56zM512 288H320v32c0 17.7-14.3 32-32 32H224c-17.7 0-32-14.3-32-32V288H0V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V288z">
              </path>
            </svg>
          </div>
          <div class="timeline-item-content">
            <h4>HSC <small></small></h4>
            <p>Government M. M. City College, Khulna</p>
            <small>Duration: 2007 - 2009</small>
          </div>
        </div>
        <!-- Timeline Item -->
        <div class="timeline-item left max-width-76">
          <div class="timeline-icon">
            <svg width="25" height="44" fill="currentColor" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 640 512">
              <path
                d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9v28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5V291.9c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z">
              </path>
            </svg>
          </div>
          <div class="timeline-item-content">
            <h4>SSC<small></small></h4>
            <p>Betaga United High School</p>
            <small>Duration: 2005 - 2007</small>
          </div>
        </div>
        
      </div>
    </div>
  </section>
  <!-- Expertise Section -->

  <!-- Research and Publication Section -->
  <section class="section-publication py-5">
    <div class="container">
      <!-- Section Heading -->
      <div
        class="section-heading-wrapper section-notice-heading-wrapper  d-flex justify-content-between align-items-center">
        <div class="section-title-wrapper">
          <h2 class="fw-bold">Research and Publications</h2>
        </div>
        <div class="button-icon-wrapper">
          <a class="button-icon  d-flex align-items-center" href="client/research.php">
            See More
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 31.49 31.49" xml:space="preserve"
              class="arrow-icon ms-2" style="width: 16px; height: 16px;">
              <path d="M21.205,5.007c-0.429-0.444-1.143-0.444-1.587,0c-0.429,0.429-0.429,1.143,0,1.571l8.047,8.047H1.111
                      C0.492,14.626,0,15.118,0,15.737c0,0.619,0.492,1.127,1.111,1.127h26.554l-8.047,8.032c-0.429,0.444-0.429,1.159,0,1.587
                      c0.444,0.444,1.159,0.444,1.587,0l9.952-9.952c0.444-0.429,0.444-1.143,0-1.571L21.205,5.007z"
                style="fill:#1E201D;"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Content Section -->
      <div class="row align-items-center">
        <!-- Left Column (Image) -->
        <div class="col-lg-6 mb-4 mb-lg-0">
          <img src="./images/resarch.jpg" alt="Research Image" class="img-fluid rounded shadow-sm">
        </div>
        <!-- Right Column (Description) -->
        <div class="col-lg-6">
          <h3 class="course-left-title mb-3">Innovative Research for the Future</h3>
          <p class="course-left-description mb-4">
            Explore groundbreaking studies and insights from our team of experts. Our publications reflect our
            commitment
            to innovation and excellence, addressing real-world challenges and advancing knowledge in diverse fields.
          </p>
          <div class="button-wrapper">
            <a href="client/research.php" class="button">See More</a>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Section Course -->
  <section class="section-courses py-5">
    <div class="container">
      <!-- Section Heading -->
      <div class="section-heading-wrapper">
        <div class="section-title-wrapper text-center">
          <h2 class="fw-bold">Courses</h2>
        </div>
      </div>

      <!-- Two-Column Grid -->
      <div class="row align-items-center">
        <!-- Left Column -->
        <div class="col-lg-6 mb-4 mb-lg-0  order-2 order-lg-1">
          <h3 class="course-left-title mb-3">Learn From the Best</h3>
          <p class="course-left-description mb-4">
            Our courses are designed to help you master new skills, enhance your career, and achieve your goals. With
            expert instructors and a comprehensive curriculum, you'll be set up for success.
          </p>
          <div class="button-wrapper">
            <a href="client/course.php" class="button">See More</a>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6 order-1 order-lg-2 mb-lg-0 mb-4">
          <img src="./images/course.jpg" alt="Courses Image" class="img-fluid rounded shadow-sm">
        </div>
      </div>
    </div>
  </section>


  <!-- Contact section -->
  <div class="contact-section">
    <div class="container">
      <div class="contact-contents-wrapper">
        <div class="contact-heading-wrapper">
          <h2 class="contact-heading">Contact Me</h2>
          <p class="section-sub-text contact-description">If you have any questions or want to know more about me,
            feel free to
            contact me.</p>
        </div>
        <div class="contact-cards-address-wrapper">
          <div class="contact-card">
            <div class="contact-card-icon-wrap">
              <svg width="25" height="34" fill="currentColor" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 512 512">
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
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-mail">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
            </div>
            <div class="contact-address-content-wrap">
              <h3>Email Me</h3>
              <div class="contact-address-link-wrap">
                <a href="mailto:+8801712345678" class="contact-address-link">m.halder@just.edu.bd</a>
              </div>
            </div>
          </div>
          <div class="contact-card">
            <div class="contact-card-icon-wrap">
              <svg width="32" height="34" fill="currentColor" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 384 512">
                <path
                  d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 256c-35.3 0-64-28.7-64-64s28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64z">
                </path>
              </svg>
            </div>
            <div class="contact-address-content-wrap">
              <h3>Address</h3>
              <div class="contact-address-link-wrap">
                <p>Room No-216, Department of Computer Science and Engineering, <br>Jashore University of Science
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



  <!-- Custom JavaScript for Filtering -->
  <script>
    document.querySelectorAll('#courseFilter .filter-btn').forEach(button => {
      button.addEventListener('click', function() {
        const filterValue = this.value; // Get the value of the clicked button
        const cards = document.querySelectorAll('#courseContainer .col-md-6');

        // Loop through the cards and toggle visibility
        cards.forEach(card => {
          const category = card.getAttribute('data-category');
          if (filterValue === 'all' || filterValue === category) {
            card.style.display = ''; // Show matching cards
          } else {
            card.style.display = 'none'; // Hide non-matching cards
          }
        });
      });
    });
  </script>

</body>

</html>