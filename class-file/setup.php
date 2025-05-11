<?php
include_once "connect.php";
include_once "user.php";
include_once "file.php";
include_once "course-details.php";
include_once "course.php";
include_once "research.php";
include_once "project.php";
include_once "research-lab.php";
// include_once ""

$conn = new Connect();

$user = new User();
$user->createTable();

$file = new File();
$file->createTable();

$course = new Course();
$course->createTable();

$courseDetails = new CourseDetails();
$courseDetails->createTable();

$research = new Research();
$research->createTable();

$project = new Project();
$project->createTable();

$researchLab = new ResearchLab();
$researchLab->createTable();


?>

<!-- END -->