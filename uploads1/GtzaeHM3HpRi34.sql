-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 02:27 PM
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
-- Database: `agritech`
--

-- --------------------------------------------------------

--
-- Table structure for table `loan_history`
--

CREATE TABLE `loan_history` (
  `loan_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL,
  `loan_amount` decimal(12,2) NOT NULL,
  `loan_source` text DEFAULT NULL,
  `loan_receive_year` int(11) NOT NULL,
  `is_loan_paid` tinyint(1) NOT NULL DEFAULT 0,
  `remaining_loan_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monthly_installment` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_history`
--

INSERT INTO `loan_history` (`loan_id`, `status`, `user_id`, `loan_amount`, `loan_source`, `loan_receive_year`, `is_loan_paid`, `remaining_loan_amount`, `monthly_installment`, `created_at`, `modified_at`) VALUES
(1, 1, 1, 15000.00, 'সরকারি', 2023, 1, 0.00, 0.00, '2025-05-15 17:49:47', '2025-05-15 17:49:47'),
(2, 1, 1, 123000.00, 'সমবায়', 2025, 0, 100000.00, 1000.00, '2025-05-15 17:50:18', '2025-05-15 17:50:18');

-- --------------------------------------------------------

--
-- Table structure for table `production_history`
--

CREATE TABLE `production_history` (
  `production_id` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `user_id` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `products` text DEFAULT NULL,
  `invested` decimal(15,2) DEFAULT 0.00,
  `is_profit` tinyint(1) DEFAULT 0,
  `return_amount` decimal(15,2) DEFAULT 0.00,
  `reason` text DEFAULT NULL,
  `agent_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_history`
--

INSERT INTO `production_history` (`production_id`, `status`, `user_id`, `year`, `products`, `invested`, `is_profit`, `return_amount`, `reason`, `agent_note`, `created_at`, `modified_at`) VALUES
(1, 1, 1, 2022, '\"[{\\\"category\\\":\\\"\\u0995\\u09c3\\u09b7\\u09bf\\\",\\\"subcategory\\\":\\\"\\u09a7\\u09be\\u09a8\\\"},{\\\"category\\\":\\\"\\u0995\\u09c3\\u09b7\\u09bf\\\",\\\"subcategory\\\":\\\"\\u0997\\u09ae\\\"}]\"', 100000.00, 1, 110000.00, 'ভালো ফলন হয়েছে। 0\'s', 'no\'s', '2025-05-15 10:42:52', '2025-05-15 10:42:52'),
(2, 1, 1, 1974, '\"[{\\\"category\\\":\\\"\\u09ae\\u09ce\\u09b8\\u09cd\\u09af\\\",\\\"subcategory\\\":\\\"\\u0995\\u09be\\u0981\\u0995\\u09a1\\u09bc\\u09be\\\"},{\\\"category\\\":\\\"\\u09ae\\u09ce\\u09b8\\u09cd\\u09af\\\",\\\"subcategory\\\":\\\"\\u09ae\\u09be\\u099b\\\"}]\"', 55.00, 1, 38.00, 'Reiciendis error rer', 'Nai.', '2025-05-15 10:43:54', '2025-05-15 10:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 0,
  `full_name` text DEFAULT NULL,
  `father_name` text DEFAULT NULL,
  `husband_name` text DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `nid_number` text DEFAULT NULL,
  `contact_no` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `blood_group` text DEFAULT NULL,
  `postal_code` text DEFAULT NULL,
  `village` text DEFAULT NULL,
  `union` text DEFAULT NULL,
  `upazila` text DEFAULT NULL,
  `district` text DEFAULT NULL,
  `division` text DEFAULT NULL,
  `work_sector` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `status`, `full_name`, `father_name`, `husband_name`, `gender`, `nid_number`, `contact_no`, `date_of_birth`, `blood_group`, `postal_code`, `village`, `union`, `upazila`, `district`, `division`, `work_sector`, `created_at`, `modified_at`) VALUES
(1, 1, 'আরাফাত', 'আক্তারুজ্জামান', '', 'পুরুষ', '৮৭১১২৩১২৩২', '০১৫১২৩১২৩১২', '1992-02-22', 'A-', '২৩১১', 'দুবচাচিয়া', 'বগুড়া', 'বন্দর', 'Bogura', 'Rajshahi', 'কৃষি, ফল', '2025-05-14 18:20:23', '2025-05-14 18:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `user_land`
--

CREATE TABLE `user_land` (
  `land_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 1,
  `user_id` int(11) DEFAULT NULL,
  `land_location` text DEFAULT NULL,
  `land_area` double DEFAULT 0,
  `land_type` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_land`
--

INSERT INTO `user_land` (`land_id`, `status`, `user_id`, `land_location`, `land_area`, `land_type`, `created_at`, `modified_at`) VALUES
(1, 1, 1, 'location 1', 123.11, 'নিজস্ব', '2025-05-14 20:05:23', '2025-05-14 20:05:23'),
(2, 1, 1, 'বন্দর, নারায়ণগঞ্জ।', 124.33, 'ভাড়া', '2025-05-14 20:07:17', '2025-05-14 20:07:17'),
(3, 1, 1, 'বন্দর, নারায়ণগঞ্জ', 122.99, 'দাদন', '2025-05-14 20:22:06', '2025-05-14 20:22:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `loan_history`
--
ALTER TABLE `loan_history`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `production_history`
--
ALTER TABLE `production_history`
  ADD PRIMARY KEY (`production_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_land`
--
ALTER TABLE `user_land`
  ADD PRIMARY KEY (`land_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `loan_history`
--
ALTER TABLE `loan_history`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `production_history`
--
ALTER TABLE `production_history`
  MODIFY `production_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_land`
--
ALTER TABLE `user_land`
  MODIFY `land_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
