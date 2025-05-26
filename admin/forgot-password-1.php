<?php
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
$session::ensureSessionStarted();

include_once "../class-file/User.php";
include_once "../class-file/File.php";
include_once "../class-file/EmailSender.php";


// Check already logged in
if ($session::get("admin_id") != null) {
    echo "<script> window.alert('You are already logged in.'); </script>";
    echo '<script> window.location.href="dashboard.php"; </script>';
    exit();
}

if (isset($_POST['resetPassword'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $user1 = new User();
    $user1->email = $email;
    $result = $user1->isEmailAvailable($email, 1, "admin");

    if ($result == 1) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $emailSender = new EmailSender();
        $emailSender->sendMail($email, "OTP for Password Reset", "Your OTP is: $otp");
        $session::set("t_otp", $otp);
        $session::set("t_email", $email);
        $session::set("t_user_id", $user1->user_id);
        echo '<script> window.location.href="forgot-password-2.php?otp=' . $otp . '"; </script>';
        exit();
    } else {
        $session::set("msg1", "Email not found");
        echo '<script>window.location.href="forgot-password-1.php?error=Email not found";</script>';
        exit();
    }
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
        <!-- Signin Section Form Start-->
        <div class="form-section">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-6">
                        <div class="signup-form-wrapper">
                            <div class="section-title text-center mb-5">
                                <?php if ($session::get("msg1")) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php
                                        echo $session::get("msg1");
                                        $session::delete("msg1");
                                        ?>
                                    </div>
                                <?php } ?>
                                <div class="signup-title">
                                    <h2>Reset Password</h2>
                                </div>
                            </div>
                            <!-- New info alert -->
                            <div class="alert alert-info" role="alert">
                                <strong>Note:</strong> Only <span class="fw-semibold">active</span> user can reset password.
                            </div>
                            <form id="loginForm" action="" method="POST">
                                <div class="input-wrapper mb-4">
                                    <span><img src="../Images/email.png" alt=""></span>
                                    <input type="email" id="email" placeholder="Email" name="email" required>
                                </div>
                                <div class="submit-btn-wrapper">
                                    <button class="signup-btn btn-1" type="submit" name="resetPassword">Send OTP</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Registration Section Form End -->

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





</body>

</html>