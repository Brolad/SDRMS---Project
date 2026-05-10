-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 01:53 AM
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
-- Database: `disciplinary_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `infractions`
--

CREATE TABLE `infractions` (
  `infraction_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `offense_type` varchar(100) DEFAULT NULL,
  `points_deducted` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_recorded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `infractions`
--

INSERT INTO `infractions` (`infraction_id`, `student_id`, `staff_id`, `offense_type`, `points_deducted`, `description`, `date_recorded`) VALUES
(1, 1, 1, 'Fighting', 20, 'Fought with a security gaurd', '2026-04-26 16:16:13'),
(2, 1, 1, 'Noise Making', 10, '', '2026-04-26 16:18:20'),
(3, 1, 1, 'Noise Making', 10, '', '2026-04-26 16:20:31'),
(4, 1, 1, 'Noise Making', 10, '', '2026-04-26 16:23:04'),
(5, 1, 1, 'Noise Making', 10, '', '2026-04-26 16:25:48'),
(6, 1, 1, 'Noise Making', 10, '', '2026-04-26 16:29:20'),
(7, 1, 1, 'Noise Making', 10, '', '2026-04-26 16:37:07'),
(8, 1, 1, 'Noise Making', 10, '', '2026-04-26 16:37:29'),
(9, 1, 1, 'Noise Making', 2, '', '2026-04-26 16:38:34'),
(10, 1, 1, 'Noise Making', 1, '', '2026-04-26 16:38:52'),
(11, 1, 1, 'Noise Making', 0, '', '2026-04-26 16:48:28'),
(12, 1, 1, 'Noise Making', 0, '', '2026-04-26 16:50:08'),
(13, 1, 1, 'Noise Making', 0, '', '2026-04-26 16:50:33'),
(14, 1, 1, 'Noise Making', 0, '', '2026-04-26 16:51:10'),
(15, 1, 1, 'Noise Making', 0, '', '2026-04-26 16:51:21'),
(16, 1, 1, 'Noise Making', 0, '', '2026-04-26 16:51:29'),
(17, 1, 1, 'Noise Making', 0, '', '2026-04-26 16:53:07'),
(18, 1, 1, 'Noise Making', 90, '', '2026-04-26 16:55:01'),
(19, 1, 1, 'Noise Making', -50, '', '2026-04-26 16:55:12'),
(20, 1, 1, 'Noise Making', 63, '', '2026-04-26 16:55:21'),
(21, 1, 1, 'Noise Making', -20, '', '2026-04-26 16:59:04'),
(22, 1, 1, 'Noise Making', 10, '', '2026-04-26 16:59:17'),
(23, 1, 1, 'Noise Making', -100, '', '2026-04-26 16:59:25'),
(24, 1, 1, 'Noise Making', 100, '', '2026-04-26 16:59:33'),
(25, 1, 1, 'Noise Making', -10, '', '2026-04-26 17:00:45'),
(26, 1, 1, 'Noise Making', -10, '', '2026-04-26 17:01:23'),
(27, 1, 1, 'Late to Class', -20, '', '2026-04-26 17:22:40'),
(28, 1, 1, 'Category C', 40, '', '2026-04-26 17:29:53'),
(29, 1, 1, 'Category B', -30, '0% Class Attendance', '2026-04-26 17:30:38'),
(30, 1, 1, 'Category C', -10, 'Fighting', '2026-04-26 17:45:26'),
(31, 1, 1, 'Category C', 40, '', '2026-04-26 17:49:21'),
(32, 1, 1, 'Category S', -100, '', '2026-04-26 17:51:33'),
(33, 1, 1, 'Category C', 100, 'Good behaviour', '2026-05-04 12:54:20'),
(34, 1, 1, 'Category C', -100, '', '2026-05-04 12:57:29'),
(35, 1, 1, 'Category C', 100, '', '2026-05-04 13:08:11'),
(36, 1, 1, 'Category C', -10, '', '2026-05-04 13:09:44'),
(37, 1, 1, 'Category C', -90, '', '2026-05-04 13:16:22'),
(38, 1, 1, 'Category C', 100, 'Fan of Bayern', '2026-05-04 13:18:49'),
(39, 1, 0, 'Major Misconduct', -20, 'Testing', '2026-05-04 14:06:43'),
(40, 1, 0, 'Major Misconduct', -20, 'Testing', '2026-05-04 14:08:28'),
(41, 1, 0, 'Major Misconduct', -20, 'Testing', '2026-05-04 14:09:04'),
(42, 1, 0, 'Major Misconduct', -20, 'Testing', '2026-05-04 14:10:16'),
(43, 1, 0, 'Minor Disruption', 80, 'Nice vibe', '2026-05-04 14:13:06'),
(44, 1, 1, 'Major Misconduct', -20, 'Testing', '2026-05-04 14:14:13'),
(45, 2, 1, 'Minor Disruption', -50, 'betting ', '2026-05-04 14:25:43'),
(46, 3, 1, 'Minor Disruption', -100, 'No compliment', '2026-05-04 14:26:22'),
(47, 3, 1, 'Exceptional Commendation', 100, 'Nice ehaviour', '2026-05-05 15:26:51'),
(48, 3, 1, 'Minor Disruption', -20, 'theft', '2026-05-05 22:01:18'),
(49, 1, 0, 'Minor Disruption', -50, 'Bad dressing', '2026-05-05 22:03:05'),
(50, 1, 1, 'Major Misconduct', -30, 'Testing', '2026-05-05 22:03:53'),
(51, 1, 0, 'Exceptional Commendation', 100, 'testing', '2026-05-05 22:04:51'),
(52, 1, 1, 'Severe Violation', -100, 'Dress code', '2026-05-09 23:25:28');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(100) DEFAULT NULL,
  `staff_id_no` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `staff_id_no`, `password`) VALUES
(1, 'Micheal Blane', 'STFF/001', '123'),
(2, 'Tessa Blane', 'STFF/002', '000');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `matric_no` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `behavior_points` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `full_name`, `matric_no`, `password`, `behavior_points`) VALUES
(1, 'David Blane', 'UNI/2000/001', '1234', 0),
(2, 'Esther Blane', 'UNI/2000/002', '12345', 50),
(3, 'Tom Blane', 'UNI/2000/003', '123456', 80),
(4, 'Micheal Blane', 'UNI/2000/06', '9876', 100);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `infractions`
--
ALTER TABLE `infractions`
  ADD PRIMARY KEY (`infraction_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `staff_id_no` (`staff_id_no`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `matric_no` (`matric_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `infractions`
--
ALTER TABLE `infractions`
  MODIFY `infraction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
