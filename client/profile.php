<?php
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
include_once "../class-file/User.php";
include_once "../class-file/File.php";

$user = new User();
$file = new FileManager();

if ($session::get("user")) {
    $user->getByFilters($session::get("userId"));
    $file->getByFilters($user->profile_picture_id);
} else {
    echo "<script> window.alert('Please login to access this page.'); </script>";
    echo '<script> window.location.href="login.php";</script>';
    exit();
}

if (isset($_POST['updateImage'])) {
    $file->doOp($_FILES['image']);
    $user->profile_picture_id = $file->file_id;
    $user->update();
    $session::set("msg1", "Profile picture updated successfully.");
    echo "<script>window.location.href='profile.php';</script>";
    exit();
}
if (isset($_POST['updateProfile'])) {
    $user->full_name = $_POST['full_name'];
    $user->email = $_POST['email'];
    $user->contact_no = $_POST['contact_no'];
    $user->gender = $_POST['gender'];
    $user->update();
    $session::set("msg1", "Profile updated successfully.");
    echo "<script>window.location.href='profile.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        include_once "navbar-1.php";
        ?>

        <section class='user-profile-section'>
            <div class="container">
                <div class="profile-form-wrapper">
                    <div class="card__title-wrap mb-20">
                        <h3 class="table__heading-title mb-5 text-center">
                            <?php
                            if ($session::get("msg1")) {
                                echo $session::get("msg1");
                                $session::delete("msg1");
                            }
                            ?>
                        </h3>
                    </div>
                    <div class="card__title-wrap mb-20">
                        <h3 class="table__heading-title mb-5 text-center">Account Details</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" class="profile-page-form">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label mb-md-0">Profile Picture</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="d-inline-block position-relative me-4 mb-3 mb-lg-0 account-profile">
                                        <div class="avatar-preview rounded">
                                            <div id="imagePreview" class="rounded-4 profile-avatar"
                                                style="background-image: url(../uploads1/<?php echo $file->file_new_name; ?>);"></div>
                                        </div>
                                        <div class="upload-link" title="" data-toggle="tooltip" data-placement="right"
                                            data-original-title="update">
                                            <input type="file" class="update-flie" id="imageUpload" name="image"
                                                accept="image/*" required>
                                            <i class="fa-solid fa-pen-to-square fs-update"></i>
                                        </div>
                                    </div>
                                    <!-- add a Update bUTToN -->
                                    <button type="submit" class=" btn btn-primary ms-2" name="updateImage" value="<?php echo $user->user_id; ?>">Update</button>
                                </div>
                            </div>
                        </form>
                        <form action="" method="POST" class="mt-2">
                            <div class="row align-items-center mb-4">
                                <div class="col-md-6">
                                    <label class="form-label mb-md-2">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" value="<?php echo $user->full_name; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-md-2">Email</label>
                                    <input type="email" class="form-control" class="form-control" name="email"
                                        value="<?php echo $user->email; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="phone mb-md-2">Contact No</label>
                                    <input type="phone" class="form-control" class="form-control" name="contact_no" value="<?php echo $user->contact_no; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="male" <?php echo ($user->gender === 'male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="female" <?php echo ($user->gender === 'female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="other" <?php echo ($user->gender === 'other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class=" text-center mt-5">
                                <button type="submit" class=" btn btn-primary ms-2" name="updateProfile" value="<?php echo $user->user_id; ?>">Save Changes</button>
                            </div>
                        </form>
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