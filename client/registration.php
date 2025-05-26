<?php
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
$session::destroy();
$session::ensureSessionStarted();

include_once "../class-file/User.php";
include_once "../class-file/File.php";

$user = new User();
$file = new FileManager();

if (isset($_POST['signup'])) {
    $user->email = $_POST['email'] ?? "";
    $user->password = $_POST['password'] ?? "";
    $user->user_type = "client";
    $user->full_name = $_POST['full_name'] ?? "";
    $user->contact_no = $_POST['phone_number'] ?? "";
    $user->gender = $_POST['gender'] ?? "";
    $user->insert();

    $file->file_owner_id = $user->user_id;
    $file->doOp($_FILES['image']);
    $user->profile_picture_id = $file->file_id;
    $user->update();

    $session::set("msg1", "Registration successful. Please login.");
    echo "<script>window.location.href='login.php';</script>";
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
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">

    <link rel="stylesheet" href="../css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="../fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="../css/aos.css">

    <link rel="stylesheet" href="../css/style.css">



</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">

        <div class="site-mobile-menu site-navbar-target">
            <div class="site-mobile-menu-header">
                <div class="site-mobile-menu-close mt-3">
                    <span class="icon-close2 js-menu-toggle"></span>
                </div>
            </div>
            <div class="site-mobile-menu-body"></div>
        </div>

        <?php
        // Include the navbar using __DIR__
        include_once "navbar-2.php";
        ?>

        <!-- Registration Section Form STart-->
        <div class="form-section">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-6">
                        <div class="signup-form-wrapper">
                            <div class="section-title text-center mb-5">
                                <div class="signup-title">
                                    <h2>Create Your Account</h2>
                                </div>
                            </div>
                            <form id="registrationForm" method="POST" action="" enctype="multipart/form-data">
                                <!-- File input + error hint -->
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Upload profile picture</label>
                                    <input
                                        class="form-control"
                                        type="file"
                                        id="formFile"
                                        name="image"
                                        accept="image/*"
                                        required />
                                </div>
                                <small id="fileError" class="text-danger d-block mb-3"></small>

                                <div class="input-wrapper mb-4">
                                    <span><img src="../Images/user.png" alt=""></span>
                                    <input type="text" name="full_name" id="fullName" placeholder="Your Name" required>
                                </div>
                                <div class="input-wrapper mb-4">
                                    <span><img src="../Images/email.png" alt=""></span>
                                    <input type="email" name="email" id="email" placeholder="Email" required>
                                </div>
                                <!-- Phone number field + error hint -->
                                <div class="input-wrapper mb-4">
                                    <span><img src="../Images/call.png" alt=""></span>
                                    <input
                                        type="number"
                                        id="phoneNumber"
                                        pattern="[0-9]{11}"
                                        title="Phone number must be exactly 11 digits."
                                        name="phone_number"
                                        placeholder="Phone Number"
                                        required
                                        inputmode="numeric" />
                                </div>
                                <small id="phoneError" class="text-danger d-block mb-3"></small>
                                <div class="input-wrapper mb-4">
                                    <span><img src="../Images/user.png" alt=""></span>
                                    <select class="user-type-input" name="gender" id="" required>
                                        <option value="male" selected>Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <!-- Password field + error hint -->
                                <div class="input-wrapper mb-4">
                                    <span><img src="../Images/lock.png" alt=""></span>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Password"
                                        required />
                                    <div class="password-toggle" onclick="togglePasswordVisibility()">
                                        <i id="toggleIcon" class="fas fa-eye"></i>
                                    </div>
                                </div>
                                <small id="passwordError" class="text-danger d-block mb-3"></small>

                                <div class="submit-btn-wrapper">
                                    <button class="signup-btn btn-1" type="submit" id="signupBtn" name="signup">Sign Up</button>
                                </div>
                            </form>

                            <div class="form-footer">
                                <p>Already have account? <a href="login.php">Login</a></p>
                            </div>

                            <!-- Toast Container -->
                            <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                                <!-- Toasts will be added here dynamically -->
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Registration Section Form End -->


    <!-- Contact section -->
    <div class="contact-section">
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


    <script>
        const phoneInput = document.getElementById('phoneNumber');
        const passInput = document.getElementById('password');
        const fileInput = document.getElementById('formFile');
        const signupBtn = document.getElementById('signupBtn');

        const phoneError = document.getElementById('phoneError');
        const passError = document.getElementById('passwordError');
        const fileError = document.getElementById('fileError');

        function validateAll() {
            // checks
            const phoneOK = /^\d{11}$/.test(phoneInput.value);
            const passOK = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/.test(passInput.value);
            const fileOK = fileInput.files.length > 0 &&
                fileInput.files[0].type.startsWith('image/');

            // error messages
            phoneError.textContent = phoneOK ?
                '' :
                'Phone must be exactly 11 digits.';
            passError.textContent = passOK ?
                '' :
                'Password needs at least 8 characters, 1 uppercase, 1 digit & 1 special.';
            fileError.textContent = fileOK ?
                '' :
                'Please select a valid image (JPG, PNG, etc).';

            // enable button only if all OK
            signupBtn.disabled = !(phoneOK && passOK && fileOK);
        }

        // re-validate on every user change
        [phoneInput, passInput].forEach(el =>
            el.addEventListener('input', validateAll)
        );
        fileInput.addEventListener('change', validateAll);

        // initial check
        validateAll();

        // reuse your toggle function
        function togglePasswordVisibility() {
            const icon = document.getElementById('toggleIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>






</body>

</html>