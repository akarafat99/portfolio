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

include_once "../class-file/User.php";
include_once "../class-file/File.php";

$user = new User();
$file = new FileManager();

if(isset($_POST['disableUser'])) {
    $userId = $_POST['disableUser'];
    $user->updateStatus($userId, 2); // Status indicating disabled
    echo "<script>window.location.href='user-management.php';</script>";
    exit();
} elseif (isset($_POST['enableUser'])) {
    $userId = $_POST['enableUser'];
    $user->updateStatus($userId, 1); // Status indicating enabled
    echo "<script>window.location.href='user-management.php';</script>";
    exit();
}

// Fetch all pending users
$allUsers = $user->getByFilters(null, [1, 2], null, "client");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pending Registration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <style>
        .profile-image {
            width: 100px;
            height: 100px;
        }

        .card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 12px;
            margin-bottom: 20px;
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateZ(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .btn-shadow {
            border: none;
            transition: all 0.3s ease-in-out;
            font-weight: 600;
            padding: 12px 20px;
            /* width: 48%; */
        }

        .btn-shadow:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-accept {
            background-color: #28a745;
            color: #fff;
        }

        .btn-accept:hover {
            background-color: #218838;
        }

        .btn-reject {
            background-color: #ff4d4f;
            color: #fff;
        }

        .btn-reject:hover {
            background-color: #d9363e;
        }
    </style>
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">

        <?php include_once "sidebar.php"; ?>

        <div class="container py-4">
            <!-- Search Box -->
            <div class="mb-4">
                <input type="text" id="searchBox" class="form-control" placeholder="Search...">
            </div>

            <!-- Cards Row -->
            <div class="row" id="cardContainer">
                <?php foreach ($allUsers as $u) { ?>
                    <div class="col-md-6">
                        <div class="card p-3">
                            <div class="d-flex align-items-center">
                                <?php
                                $user->setProperties($u);
                                $file->getByFilters($user->profile_picture_id);
                                ?>
                                <img src="../uploads1/<?php echo $file->file_new_name; ?>"
                                    class="profile-image rounded-circle me-3" alt="Profile Image">
                                <div>
                                    <h4 class="mb-1"><?php echo $user->full_name; ?></h4>
                                </div>
                            </div>
                            <ul class="list-unstyled mt-3">
                                <li><strong>User ID:</strong> <?php echo $user->user_id; ?></li>
                                <li><strong>Full Name:</strong> <?php echo $user->full_name; ?></li>
                                <li><strong>Email:</strong> <?php echo $user->email; ?></li>
                                <li><strong>Contact No:</strong> <?php echo $user->contact_no; ?></li>
                                <li><strong>Gender:</strong> <?php echo $user->gender; ?></li>
                                <li><strong>User Type:</strong> <?php echo $user->user_type; ?></li>
                                <li><strong>Time:</strong> <?php echo $user->created_at; ?></li>
                            </ul>
                            <div class="mt-3 d-flex justify-content-between">
                                <div class="mt-3">
                                    <form action="" method="post">
                                        <?php if ($user->status == 1):?>
                                            <button
                                                type="submit"
                                                name="disableUser"
                                                value="<?php echo $user->user_id; ?>"
                                                class="btn btn-shadow btn-reject">Disable</button>
                                        <?php elseif ($user->status == 2): ?>
                                            <button
                                                type="submit"
                                                name="enableUser"
                                                value="<?php echo $user->user_id; ?>"
                                                class="btn btn-shadow btn-accept"">Enable</button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>

    </div> <!-- close #content of sidebar.php -->
    </div>

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#searchBox").on("input", function() {
                const searchValue = $(this).val().toLowerCase();
                $("#cardContainer .card").each(function() {
                    const cardText = $(this).text().toLowerCase();
                    if (cardText.includes(searchValue)) {
                        $(this).parent().show();
                    } else {
                        $(this).parent().hide();
                    }
                });
            });
        });
    </script>
</body>

</html>