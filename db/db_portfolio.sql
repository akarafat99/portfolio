-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 03:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`user_id`, `email`, `password`, `created`, `modified`) VALUES
(1, 'admin@admin', '$2y$10$BbedX7UpRAsQGi3bDg44VeiAuQhR2.g88L9KDCP2.GoQq4D32u3Le', '2024-12-10 20:07:52', '2024-12-10 20:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_course`
--

CREATE TABLE `tbl_course` (
  `course_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 1,
  `course_name` varchar(255) DEFAULT NULL,
  `course_code` varchar(50) DEFAULT NULL,
  `course_details` varchar(255) DEFAULT NULL,
  `program_name` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_course`
--

INSERT INTO `tbl_course` (`course_id`, `status`, `course_name`, `course_code`, `course_details`, `program_name`, `created`, `modified`) VALUES
(1, 1, 'Operating system', 'CSE3101', 'Theoretical course', 'bsc', '2024-12-10 20:09:07', '2024-12-10 20:09:07'),
(2, 1, 'Operating system Lab', 'CSE3102', 'Lab course', 'bsc', '2024-12-10 20:09:29', '2024-12-10 20:09:29'),
(3, 1, 'Distribution System and Cloud Computing', 'CSE4203', 'Theoretical course to understand the Distribution System and Cloud Computing.', 'bsc', '2024-12-10 20:10:26', '2024-12-10 20:10:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_course_details`
--

CREATE TABLE `tbl_course_details` (
  `details_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `day_no` int(11) DEFAULT 0,
  `content_details` text DEFAULT NULL,
  `resource_files` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_course_details`
--

INSERT INTO `tbl_course_details` (`details_id`, `course_id`, `day_no`, `content_details`, `resource_files`, `created`, `modified`) VALUES
(1, 3, 0, 'Introduction class', '', '2024-12-10 20:12:01', '2024-12-10 20:12:01'),
(2, 3, 0, 'Chapter 1', 'image2.jpg', '2024-12-10 20:12:26', '2024-12-10 20:12:26'),
(3, 3, 0, 'Chapter 2', 'photo_2024-12-01_18-20-30.jpg,photo_2024-12-01_18-20-07.jpg,photo_2024-12-01_18-19-50.jpg', '2024-12-10 20:12:58', '2024-12-10 20:12:58'),
(4, 1, 0, 'Orientation', '', '2024-12-10 20:13:14', '2024-12-10 20:13:14'),
(5, 1, 0, 'Chapter 1 intro', 'image2.jpg,Screenshot 2024-11-27 232136.png', '2024-12-10 20:13:42', '2024-12-10 20:13:42');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_research`
--

CREATE TABLE `tbl_research` (
  `research_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 1,
  `research_type` varchar(100) DEFAULT NULL,
  `research_title` varchar(255) DEFAULT NULL,
  `research_link` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_research`
--

INSERT INTO `tbl_research` (`research_id`, `status`, `research_type`, `research_title`, `research_link`, `created`, `modified`) VALUES
(1, 1, 'thesis', 'Operating system principles', 'https://dl.acm.org/doi/abs/10.5555/540365', '2024-12-10 20:14:50', '2024-12-10 20:14:50'),
(2, 1, 'thesis', 'Operating system principles 2', 'https://dl.acm.org/doi/abs/10.5555/5403653311', '2024-12-10 20:15:06', '2024-12-10 20:15:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_course`
--
ALTER TABLE `tbl_course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `tbl_course_details`
--
ALTER TABLE `tbl_course_details`
  ADD PRIMARY KEY (`details_id`);

--
-- Indexes for table `tbl_research`
--
ALTER TABLE `tbl_research`
  ADD PRIMARY KEY (`research_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_course`
--
ALTER TABLE `tbl_course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_course_details`
--
ALTER TABLE `tbl_course_details`
  MODIFY `details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_research`
--
ALTER TABLE `tbl_research`
  MODIFY `research_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
