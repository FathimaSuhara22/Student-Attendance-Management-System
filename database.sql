-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2026 at 08:29 AM
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
create Database attendance_db;

USE attendance_db;


-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

CREATE TABLE `attendance_records` (
  `attendance_id` int(11) NOT NULL,
  `lecture_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `verified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('present','absent') DEFAULT 'present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`attendance_id`, `lecture_id`, `student_id`, `verified_at`, `status`) VALUES
(227, 19, 27, '2026-11-07 07:21:37', 'present'),
(228, 19, 31, '2026-11-07 07:21:37', 'absent'),
(229, 19, 28, '2026-11-07 07:21:37', 'absent'),
(230, 19, 29, '2026-11-07 07:21:37', 'absent'),
(231, 19, 26, '2026-11-07 07:21:37', 'absent'),
(232, 19, 30, '2026-11-07 07:21:37', 'present'),
(233, 19, 32, '2026-11-07 07:21:37', 'present');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(150) NOT NULL,
  `lecturer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_code`, `course_name`, `lecturer_id`) VALUES
(1, 'IT2133', 'Advanced Web Programming', 20),
(2, 'IT2122', 'Software Engineering', 21),
(3, 'IT2114', 'Data Structures', 22),
(4, 'IT2143', 'Visual Programming', 23),
(5, 'IT2153', 'Computer Graphics', 24);

-- --------------------------------------------------------

--
-- Table structure for table `course_enrollments`
--

CREATE TABLE `course_enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_enrollments`
--

INSERT INTO `course_enrollments` (`enrollment_id`, `course_id`, `student_id`) VALUES
(49, 1, 26),
(54, 1, 27),
(59, 1, 28),
(64, 1, 29),
(69, 1, 30),
(74, 1, 31),
(79, 1, 32),
(50, 2, 26),
(55, 2, 27),
(60, 2, 28),
(65, 2, 29),
(70, 2, 30),
(75, 2, 31),
(80, 2, 32),
(51, 3, 26),
(56, 3, 27),
(61, 3, 28),
(66, 3, 29),
(71, 3, 30),
(76, 3, 31),
(81, 3, 32),
(52, 4, 26),
(57, 4, 27),
(62, 4, 28),
(67, 4, 29),
(72, 4, 30),
(77, 4, 31),
(82, 4, 32),
(53, 5, 26),
(58, 5, 27),
(63, 5, 28),
(68, 5, 29),
(73, 5, 30),
(78, 5, 31),
(83, 5, 32);

-- --------------------------------------------------------

--
-- Table structure for table `lectures`
--

CREATE TABLE `lectures` (
  `lecture_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lecture_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lectures`
--

INSERT INTO `lectures` (`lecture_id`, `course_id`, `lecture_date`, `start_time`, `end_time`) VALUES
(19, 3, '2026-11-07', '00:00:00', '13:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `otp_sessions`
--

CREATE TABLE `otp_sessions` (
  `otp_id` int(11) NOT NULL,
  `lecture_id` int(11) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_sessions`
--

INSERT INTO `otp_sessions` (`otp_id`, `lecture_id`, `otp_code`, `generated_at`, `expires_at`) VALUES
(5, 19, '182337', '2026-11-07 07:12:29', '2026-11-07 07:17:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','lecturer','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `email`, `role`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@vau.ac.lk', 'admin'),
(20, 'lec_perera', '$2y$10$VH6ucVt9SHSicdlI4vzTNuHNI5tOdcn9E.gyBVXFNAX1njkgQ/w5.', 'perera@vau.ac.lk', 'lecturer'),
(21, 'lec_silva', '$2y$10$6c1yRVdFwmROgQPAbJ9WV.VjQoSCu.a59cdswUFy/marWqVVjxhym', 'silva@vau.ac.lk', 'lecturer'),
(22, 'lec_piris', '$2y$10$FWOOUmwXbUaFcfRhZHvLKOX2c9xNqzVXpM6PbQy11PRy7j0A35.LK', 'piris@vau.ac.lk', 'lecturer'),
(23, 'lec_bandara', '$2y$10$fRJEUmsx2Y9m/8W5S9q1CeDXmJ5moVoOl9zjsK3o8TblkvStgBYKG', 'bandara@vau.ac.lk', 'lecturer'),
(24, 'lec_samarakoon', '$2y$10$gaVNfVQu6bvsRY/Bta0pU.dxGN.WdCZJYC0P6sJJyUXeJYxbg8zyK', 'samarakoon@vau.ac.lk', 'lecturer'),
(26, '2023/ict/100', '$2y$10$vWDXm/KTYKaPRFowR8wNK.4TIVV2hDUQ2OR.sVb3tcXgdemhtrzMm', '2023ict100@vau.ac.lk', 'student'),
(27, '2023/ict/020', '$2y$10$AJRcGvooqqtd1FxUJXUGheWsgUl8X0l5tMKq6hOZjFhOEmbkLwWlq', '2023ict020@vau.ac.lk', 'student'),
(28, '2023/ict/041', '$2y$10$Mo3EGiIjc1Z76ufReKq1qejRpsSewV/l7S.0.ME484.IxWbvWrzoi', '2023ict041@vau.ac.lk', 'student'),
(29, '2023/ict/073', '$2y$10$3bUfCDxgRguSQ6yvQWo3iewl5c6rYXBSLVfZUK16X6d5vKGey5UU.', '2023ict073@vau.ac.lk', 'student'),
(30, '2023/ict/103', '$2y$10$gzgvNTIST.Fa6kNX0JXFne69XmdaoBTGI5klZPUp/G3TsbV/wCD92', '2023ict103@vau.ac.lk', 'student'),
(31, '2023/ict/024', '$2y$10$btj5qsRGPkk/Qu0P2t8gFevq7zWUWasva9qLfzjkS5VytYHNt/L2S', '2023ict024@vau.ac.lk', 'student'),
(32, '2023/ict/124', '$2y$10$/igJbAdtn/Z4hH8jbELfaebHrsw0QRaWzkJoRhb1NOu2f5jC4y4we', '2023ict124@vau.ac.lk', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`attendance_id`),
  ADD UNIQUE KEY `lecture_id` (`lecture_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course_code` (`course_code`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD UNIQUE KEY `course_id` (`course_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `lectures`
--
ALTER TABLE `lectures`
  ADD PRIMARY KEY (`lecture_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `otp_sessions`
--
ALTER TABLE `otp_sessions`
  ADD PRIMARY KEY (`otp_id`),
  ADD KEY `lecture_id` (`lecture_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `lectures`
--
ALTER TABLE `lectures`
  MODIFY `lecture_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `otp_sessions`
--
ALTER TABLE `otp_sessions`
  MODIFY `otp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD CONSTRAINT `attendance_records_ibfk_1` FOREIGN KEY (`lecture_id`) REFERENCES `lectures` (`lecture_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_records_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD CONSTRAINT `course_enrollments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `lectures`
--
ALTER TABLE `lectures`
  ADD CONSTRAINT `lectures_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `otp_sessions`
--
ALTER TABLE `otp_sessions`
  ADD CONSTRAINT `otp_sessions_ibfk_1` FOREIGN KEY (`lecture_id`) REFERENCES `lectures` (`lecture_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
