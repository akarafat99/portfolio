<?php
// Include session management
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
$session::destroy();
$session::ensureSessionStarted();

include_once "../class-file/User.php";
$user = new User();

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $result = $user->checkUserEmailWithStatus($email, $password, "admin");
    if ($result[0] == "1") {
        $session::set("admin", "admin");
        $session::set("admin_id", $user->user_id);
        $session::storeObject("admin_obj", $user);
        $session::set("msg1", "Login successful");
        echo '<script> window.location.href = "dashboard.php";</script>';
        exit();
    } else {
        $session::set("msg1", $result[1]);
        echo '<script> window.location.href = "login.php";</script>';
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
    <link rel="stylesheet" href="fonts/icomoon/style.css">

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

        <!-- Add Section Start -->
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="form-wrapper">
                            <div class="form-header text-center mb-4">
                                <?php if ($session::get("msg1")): ?>
                                    <div class="alert alert-danger"><?php echo $session::get("msg1"); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-header text-center mb-4">
                                <h4>Admin Login</h4>
                            </div>
                            <div class="form-block">
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>
                                    </div>
                                    <!-- 
                                    <div class="text-center">
                                        <button type="submit" class="button" style="cursor: pointer;">Submit</button>
                                    </div> -->
                                    <div
                                        class="button-wrapper d-flex align-items-center justify-content-center text-center">
                                        <button type="submit" class="btn btn-primary" name="login">Login</button>
                                    </div>

                                    <!-- reset password -->
                                    <div class="text-center mt-3">
                                        <a href="reset-password-1.php" class="text-decoration-none">Forgot Password?</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Registration Section End -->



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
        document.getElementById('courseFilter').addEventListener('change', function() {
            const filterValue = this.value;
            const cards = document.querySelectorAll('#courseContainer .col-md-6');

            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filterValue === 'all' || filterValue === category) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>