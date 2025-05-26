<?php
include_once "DatabaseConnector.php";
include_once "User.php";
include_once "Admin.php";
include_once "File.php";
include_once "Department.php";
include_once "Course.php";
include_once "CourseDetails.php";
include_once "Project.php";
include_once "Research.php";
include_once "Lab.php";


//session 1
// $db = new DatabaseConnector();
// $db->createDatabase();
// echo "Database created successfully";
// echo "<br><br><br>";

// $user = new User();
// $user->createTableMinimal();
// $user->alterTableAddColumns();
// echo "User table created successfully";
// echo "<br><br><br>";

// $admin = new Admin();
// $admin->insertAdmin();
// echo "Admin created successfully";
// echo "<br><br><br>";

// $file = new FileManager();
// $file->createTableMinimal();
// $file->alterTableAddColumns();
// echo "File table created successfully";
// echo "<br><br><br>";

// $department = new Department();
// $department->createTableMinimal();
// $department->alterTableAddColumns();
// echo "Department table created successfully";
// echo "<br><br><br>";

// $course = new Course();
// $course->createTableMinimal();
// $course->alterTableAddColumns();
// echo "Course table created successfully";
// echo "<br><br><br>";

// $courseDetails = new CourseDetails();
// $courseDetails->createTableMinimal();
// $courseDetails->alterTableAddColumns();
// echo "Course Details table created successfully";
// echo "<br><br><br>";

// $project = new Project();
// $project->createTableMinimal();
// $project->alterTableAddColumns();
// echo "Project table created successfully";
// echo "<br><br><br>";

// $research = new Research();
// $research->createTableMinimal();
// $research->alterTableAddColumns();
// echo "Research table created successfully";
// echo "<br><br><br>";

$lab = new Lab();
$lab->createTableMinimal();
$lab->alterTableAddColumns();
echo "Lab table created successfully";
echo "<br><br><br>";

?>
<!-- end -->