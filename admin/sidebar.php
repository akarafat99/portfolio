    <style>
        /* 1) Base sidebar sizing */
#sidebar {
  width: 250px;
}

/* 2) Desktop: sidebar always visible, content shifted */
@media (min-width: 992px) {
  #sidebar {
    position: fixed;
    top: 0;
    left: 0;
    transform: translateX(0) !important;
  }
  #content {
    margin-left: 250px;
  }
  .toggle-btn {
    display: none;
  }
}

/* 3) Mobile: off-canvas by default, slides in when .active added */
@media (max-width: 991.98px) {
  #sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    transform: translateX(-100%);
    transition: transform 0.3s ease-in-out;
    z-index: 2000;
  }
  #sidebar.active {
    transform: translateX(0);
  }
  #content {
    margin-left: 0;
  }
  /* ensure the toggle button is visible */
  .toggle-btn {
    display: block;
    padding: .5rem;
  }
}

/* hide default close btn on desktop */
#sidebarClose { 
    display: none;

    right: -1px;
    /* background-color: red; */
    padding: 12px;
    top: 10px;
    background-color: white;
    padding: 1px;
    top: 4px;
    font-size: 18px;

}

/* mobile: show it */
@media (max-width: 991.98px) {
  #sidebarClose {
    display: block;
    z-index: 2100; /* above the sidebar */
  }
}

    </style>
    
    <!-- Sidebar Start -->
    <nav id="sidebar" class="sidebar bg-dark text-white">
        <div class="sidebar-header text-center py-4">
            <h4>Admin Panel</h4>
            <!-- close btn, only on mobile -->
    <button id="sidebarClose" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 d-lg-none" aria-label="Close sidebar">❌</button>
        </div>

        <ul class="list-unstyled components">
            <li><a href="dashboard.php" class="text-white">Admin</a></li>
            <li><a href="profile.php" class="text-white">Profile</a></li>
            <li><a href="pending-registration.php" class="text-white">Pending Registration</a></li>
            <li><a href="user-management.php" class="text-white">User Management</a></li>
            <li><a href="department.php" class="text-white">Department Management</a></li>
            <li><a href="course-management.php" class="text-white">Course Management</a></li>
            <li><a href="create-course.php" class="text-white">Create Course</a></li>
            <li><a href="research-management.php" class="text-white">Research Management</a></li>
            <li><a href="create-research.php" class="text-white">Create Research</a></li>
            <li><a href="project-management.php" class="text-white">Project Management</a></li>
            <li><a href="create-project.php" class="text-white">Create Project</a></li>
            <li><a href="lab-management.php" class="text-white">Lab Management</a></li>
            <li><a href="create-lab.php" class="text-white">Create Lab</a></li>
        </ul>
        <ul class="list-unstyled components">
            <li><a href="logout.php" class="text-white">Logout</a></li>
        </ul>
    </nav>
    <!-- Sidebar End -->

    <!-- Main Content Start -->
    <div id="content" class="flex-grow-1">
        <!-- Toggle Button for Sidebar -->
        <div class="toggle-btn d-lg-none  toggle-bg text-white">
            <button id="sidebarToggle" class="btn btn-sm btn-light">☰ Menu</button>
        </div>

        <!-- Not closed the Main Content End for content -->

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

        <script>
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
            });

            document.getElementById('sidebarClose').addEventListener('click', () => {
  document.getElementById('sidebar').classList.remove('active');
});
        </script>