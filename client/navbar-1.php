<?php


// Original code: removes the document root from the current file's directory.
$navbarDir = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__));

// Replace any backslashes with forward slashes
$navbarDir = str_replace('\\', '/', $navbarDir);

// Append a trailing slash
$navbarDir .= '/';

// echo $navbarDir; // Outputs: /subfolder/
?>

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
                <div class="site-logo mr-auto w-20">
                    <a class="logo-text" href="/">M<span>H </span></a>
                </div>

                <div class="mx-auto text-center">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block m-0 p-0">
                            <li><a href="<?= $navbarDir ?>../index.php" class="nav-link">Home</a></li>
                            <li><a href="<?= $navbarDir ?>../index.php#expertise" class="nav-link">About</a></li>
                            <li><a href="<?= $navbarDir ?>course.php" class="nav-link">Course</a></li>
                            <li><a href="<?= $navbarDir ?>research.php" class="nav-link">Research</a></li>
                            <li><a href="<?= $navbarDir ?>projects.php" class="nav-link">Projects</a></li>
                            <li><a href="<?= $navbarDir ?>lab.php" class="nav-link">Labs</a></li>
                        </ul>
                    </nav>
                </div>

                <div class="ml-auto w-20">
                    <!-- Add Here Start-->
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                            <li class="dropdown cta">
                                <a href="#" class="nav-btn dropdown-toggle" id="userMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>Account</span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="userMenu">
                                    <a class="dropdown-item" href="<?= $navbarDir ?>profile.php">Profile</a>
                                    <a class="dropdown-item" href="<?= $navbarDir ?>logout.php">Logout</a>
                                </div>
                            </li>
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

</div>