<?php
// Include session management
include_once "../class-file/session.php";
include_once "../class-file/user.php";
include_once "../class-file/file.php";

$session = new SessionManager();
$user = new User();
$file = new File();

// Handle form submission for accepting or rejecting users
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['acceptUser'])) {
        $userId = $_POST['acceptUser'];
        $user->user_id = $userId;
        $user->status = 1; // Status indicating acceptance
        $user->updateStatus();
    } elseif (isset($_POST['rejectUser'])) {
        $userId = $_POST['rejectUser'];
        $user->user_id = $userId;
        $user->status = -1; // Status indicating rejection
        $user->updateStatus();
    }
}

$user->status = 0;
$user->user_type = "student";
$allPendingUsers = $user->getAllUsers();


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
            width: 48%;
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
                <?php foreach ($allPendingUsers as $user) { ?>
                    <div class="col-md-6">
                        <div class="card p-3">
                            <div class="d-flex align-items-center">
                                <?php
                                $file->file_id = $user['profile_picture_id'];
                                $file->getFileName();
                                $userImage = $file->file_name;
                                ?>
                                <img src="../store1/<?php echo $userImage ?>"
                                    class="profile-image rounded-circle me-3" alt="Profile Image">
                                <div>
                                    <h4 class="mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h4>
                                </div>
                            </div>
                            <ul class="list-unstyled mt-3">
                                <li><strong>User ID:</strong> <?php echo htmlspecialchars($user['user_id']); ?></li>
                                <li><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                                <li><strong>Contact No:</strong> <?php echo htmlspecialchars($user['contact_no']); ?></li>
                                <li><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></li>
                                <li><strong>User Type:</strong> <?php echo htmlspecialchars($user['user_type']); ?></li>
                                <li><strong>Time:</strong> <?php echo htmlspecialchars($user['created']); ?></li>
                            </ul>
                            <div class="mt-3 d-flex justify-content-between">
                                <form action="" method="post">
                                    <button type="submit" class="btn btn-shadow btn-accept" name="acceptUser" value="<?php echo $user['user_id']; ?>">Accept</button>
                                    <button type="submit" class="btn btn-shadow btn-reject" name="rejectUser" value="<?php echo $user['user_id']; ?>">Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>

        <!-- Footer -->
        <footer class="footer mt-5">
            <div class="container text-center">
                <p class="mb-0">&copy; 2024 Your Website Name. All Rights Reserved.</p>
                <small>Designed with ❤️ by Your Name</small>
            </div>
        </footer>
        <!-- Footer End -->
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