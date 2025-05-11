<?php
include_once __DIR__ . "/../class-file/user.php";
include_once __DIR__ . "/../class-file/session.php";

$session = new SessionManager();
// $session->destroy();
$user_logged_in = 0;

// Check already logged in
if ($session->exists("user")) {
    // Retrieve and unserialize the user object
    $serializedUser = $session->get("user");
    $user = unserialize($serializedUser);
    $user_logged_in = 1;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

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
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>

</head>

<body>

    <div class="site-wrap">

        <div class="site-mobile-menu site-navbar-target">
            <div class="site-mobile-menu-header">
                <div class="site-mobile-menu-close mt-3">
                    <span class="icon-close2 js-menu-toggle"></span>
                </div>
            </div>
            <div class="site-mobile-menu-body"></div>
        </div>

        <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">

            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <div class="site-logo mr-auto w-25">
                        <a class="logo-text" href="/">M<span>H </span></a>
                    </div>

                    <div class="mx-auto text-center">
                        <nav class="site-navigation position-relative text-right" role="navigation">
                            <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block m-0 p-0">
                                <li><a href="../index.php" class="nav-link">Home</a></li>
                                <li><a href="../index.php#expertise" class="nav-link">About</a></li>
                                <li><a href="../client/course.php" class="nav-link">Course</a></li>
                                <li><a href="../client/research.php" class="nav-link">Research</a></li>
                                <li><a href="../client/projects.php" class="nav-link">Projects</a></li>
                                <li><a href="../client/research-lab.php" class="nav-link">Research Labs</a></li>
                            </ul>
                        </nav>
                    </div>

                    <!-- <div class="ml-auto w-25">
                       
                        <nav class="site-navigation position-relative text-right" role="navigation">
                            <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                                <?php if ($user_logged_in === 1): ?>
                                    <li class="cta">
                                        <a href="../client/logout.php" class="nav-btn">
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="cta">
                                        <a href="../client/login.php" class="nav-btn">
                                            <span>Login</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right">
                            <span class="icon-menu h3"></span>
                        </a>
                    </div> -->
                    <div class="ml-auto w-25">
                        <!-- Add Here Start-->
                        <nav class="site-navigation position-relative text-right" role="navigation">
                            <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                                <?php if ($user_logged_in === 1): ?>
                                    <li class="dropdown cta">
                                        <a href="#" class="nav-btn dropdown-toggle" id="userMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span>Account</span>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="userMenu">
                                            <a class="dropdown-item" href="../client/profile.php">Profile</a>
                                            <a class="dropdown-item" href="../client/logout.php">Logout</a>
                                        </div>
                                    </li>
                                <?php else: ?>
                                    <li class="cta">
                                        <a href="../client/login.php" class="nav-btn" id="guestMenu" aria-haspopup="true" aria-expanded="false">
                                            <span>Login</span>
                                        </a>
                                        <!-- <div class="dropdown-menu" aria-labelledby="guestMenu">
                                            <a class="dropdown-item" href="../client/login.php">Login</a>
                                            <a class="dropdown-item" href="../client/register.php">Register</a>
                                        </div> -->
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <!-- Add Here End-->
                        <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right">
                            <span class="icon-menu h3"></span>
                        </a>
                    </div>

                </div>
            </div>

        </header>

</body>

</html>