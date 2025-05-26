<?php
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
$session::ensureSessionStarted();

if($session::get("admin") == null) {
    echo "<script> window.alert('Please login to access this page.'); </script>";
    $session::set('msg1', "Please login to access this page.");
    echo '<script> window.location.href="login.php"; </script>';
    exit();
}

include_once "../class-file/Department.php";

$department = new Department();
// Handle form submission to update or add a department
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Update existing department
    if (!empty($_POST['update_department_id']) && isset($_POST['department_name_update'])) {
        $department->department_id = intval($_POST['update_department_id']);
        $department->department_name = $_POST['department_name_update'];
        $department->update();
    }
    // Add new department
    elseif (!empty($_POST['department_name'])) {
        $department->department_name = $_POST['department_name'];
        $department->status = 1; // default active status
        $department->insert();
    }
    echo "<script>alert('Department updated/added successfully!');</script>";
    // Redirect to avoid form resubmission
    echo "<script>window.location.href = 'department.php';</script>";
    exit();
}

// Always fetch all departments for client-side search
$allDepartment = $department->getByFilters();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Departments</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS links -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">
        <?php include_once "sidebar.php"; ?>

        <div class="container mt-5">
            <!-- Add Department Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Add New Department</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="department_name">Department Name</label>
                            <input type="text" name="department_name" id="department_name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Add Department</button>
                    </form>
                </div>
            </div>

            <!-- Client-side Search -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Search Departments</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <input type="text" id="search_input" class="form-control" placeholder="Type to search department...">
                    </div>
                </div>
            </div>

            <!-- List of Departments with inline update -->
            <div class="card mb-4" min-height="100vh">
                <div class="card-header">
                    <h4>All Departments</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="department_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Department Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($allDepartment)): ?>
                                <?php foreach ($allDepartment as $dept): ?>
                                    <tr>
                                        <form method="post" action="">
                                            <td><?php echo intval($dept['department_id']); ?></td>
                                            <td>
                                                <input type="text"
                                                    name="department_name_update"
                                                    value="<?php echo htmlspecialchars($dept['department_name']); ?>"
                                                    class="form-control dept-name-input">
                                            </td>
                                            <td>
                                                <input type="hidden" name="update_department_id" value="<?php echo intval($dept['department_id']); ?>">
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No departments found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.site-wrap -->

    <!-- JS scripts -->
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search_input');
            const table = document.getElementById('department_table');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();

                Array.from(rows).forEach(row => {
                    const nameInput = row.querySelector('.dept-name-input');
                    if (!nameInput) return;
                    const text = nameInput.value.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });
    </script>
</body>

</html>