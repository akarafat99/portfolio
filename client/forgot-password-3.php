<?php
include_once "../class-file/SessionManager.php";
include_once "../class-file/User.php";
include_once "../class-file/File.php";
include_once "../class-file/EmailSender.php";

$session = SessionStatic::class;
$session::ensureSessionStarted();
$emailSender = new EmailSender();


// Check already logged in
if ($session::get("user_id") != null) {
    echo '<script> window.location.href="index.php"; </script>';
    exit();
}

if (isset($_POST['submitPassword'])) {
    $newPassword = $_POST['new-password'];
    $userId = $session::get("t_user_id");

    $user = new User();
    $user->getByFilters($userId);
    $user->password = $user->hashPassword($newPassword);
    $result = $user->update();
    $result ?
        $session::set("msg1", "Password updated successfully!") :
        $session::set("msg1", "Failed to update password!");

    echo '<script> window.location.href = "login.php"; </script>';
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

    <style>
        .input-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">

        <?php
        // Include the navbar using __DIR__
        include_once "navbar-2.php";
        ?>

        <div class="form-section">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-6">
                        <div class="signup-form-wrapper">
                            <div class="section-title text-center mb-5">
                                <div class="signup-title">
                                    <h2>Enter new password</h2>
                                </div>
                            </div>
                            <form id="resetForm" method="POST" action="">
                                <div class="input-wrapper mb-4">
                                    <span><img src="../Images/lock.png" alt=""></span>
                                    <input
                                        type="password"
                                        id="newPassword"
                                        name="new-password"
                                        placeholder="New password"
                                        required />
                                    <div class="password-toggle" onclick="togglePasswordVisibility('newPassword','toggleNew')">
                                        <i id="toggleNew" class="fas fa-eye"></i>
                                    </div>
                                </div>
                                <small id="newPassError" class="text-danger d-block mb-3"></small>

                                <div class="input-wrapper mb-4">
                                    <span><img src="../Images/lock.png" alt=""></span>
                                    <input
                                        type="password"
                                        id="confirmPassword"
                                        name="confirm-password"
                                        placeholder="Confirm password"
                                        required />
                                    <div class="password-toggle" onclick="togglePasswordVisibility('confirmPassword','toggleConfirm')">
                                        <i id="toggleConfirm" class="fas fa-eye"></i>
                                    </div>
                                </div>
                                <small id="confirmPassError" class="text-danger d-block mb-3"></small>

                                <div class="submit-btn-wrapper">
                                    <button
                                        class="signup-btn btn-1"
                                        type="submit"
                                        name="submitPassword"
                                        id="resetBtn"
                                        disabled>Reset Password</button>
                                    <small id="submitInfo" class="text-info d-block mt-2"></small>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>


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




    </div>

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
        // grab elements
        const newPwdInput = document.getElementById('newPassword');
        const confirmPwdInput = document.getElementById('confirmPassword');
        const resetBtn = document.getElementById('resetBtn');

        const newPassError = document.getElementById('newPassError');
        const confirmPassError = document.getElementById('confirmPassError');
        const submitInfo = document.getElementById('submitInfo');

        // strength regex
        const strengthRe = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;

        function validatePasswords() {
            const newVal = newPwdInput.value;
            const confVal = confirmPwdInput.value;

            // check strength
            const strong = strengthRe.test(newVal);
            newPassError.textContent = strong ?
                '' :
                'Password must be ≥8 chars, include 1 uppercase, 1 digit & 1 special.';

            // check match (only if strength OK and user typed something in confirm)
            const match = newVal === confVal;
            confirmPassError.textContent = (confVal && !match) ?
                'Passwords do not match.' :
                '';

            // decide overall
            const allGood = strong && match;
            resetBtn.disabled = !allGood;
            submitInfo.textContent = allGood ?
                '' :
                'Fix the above issues to enable resetting.';
        }

        // toggle visibility (works for both fields)
        function togglePasswordVisibility(fieldId, iconId) {
            const fld = document.getElementById(fieldId);
            const icn = document.getElementById(iconId);
            if (fld.type === 'password') {
                fld.type = 'text';
                icn.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                fld.type = 'password';
                icn.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // listen for input on both fields
        [newPwdInput, confirmPwdInput].forEach(el =>
            el.addEventListener('input', validatePasswords)
        );

        // initial run
        validatePasswords();
    </script>





</body>

</html>