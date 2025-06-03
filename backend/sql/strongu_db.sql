-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2025 at 02:50 PM
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
-- Database: `strongu_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_user` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_user` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `day`
--

CREATE TABLE `day` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `day`
--

INSERT INTO `day` (`id`, `name`) VALUES
(6, 'Friday'),
(2, 'Monday'),
(7, 'Saturday'),
(1, 'Sunday'),
(5, 'Thursday'),
(3, 'Tuesday'),
(4, 'Wednesday');

-- --------------------------------------------------------

--
-- Table structure for table `goal`
--

CREATE TABLE `goal` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goal`
--

INSERT INTO `goal` (`id`, `name`, `description`) VALUES
(1, 'Weight Loss', 'ini adalah deskripsi goal'),
(2, 'Firming & Toning', 'ini adalah deskripsi goal'),
(3, 'Increase Muscle', 'ini adalah deskripsi goal'),
(4, 'Strength', 'ini adalah deskripsi goal'),
(5, 'Endurance Training', 'ini adalah deskripsi goal'),
(6, 'Weight Gain', 'ini adalah deskripsi goal'),
(7, 'Flexibility', 'ini adalah deskripsi goal'),
(8, 'Aerobic Fitness', 'ini adalah deskripsi goal'),
(9, 'Body Building', 'ini adalah deskripsi goal');

-- --------------------------------------------------------

--
-- Table structure for table `personal_trainer`
--

CREATE TABLE `personal_trainer` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `name`) VALUES
(2, 'Afternoon'),
(3, 'Evening'),
(1, 'Morning');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `noTelp` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','pt') NOT NULL DEFAULT 'user',
  `level` enum('beginner','intermediate','advanced') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `profile_pict` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `email`, `noTelp`, `password`, `role`, `level`, `birthday`, `zipcode`, `profile_pict`, `created_at`) VALUES
(1, 'wisnu123', 'narendraraka2004@gmail.com', '081257577765', '$2y$10$29iZJsFIIa01hQmCuxIf0O8pOdZm8.jbzC6a6Ca4Yt7TdX46NICuW', 'admin', NULL, NULL, NULL, NULL, '2025-05-12 12:06:35'),
(10, 'aan123', 'rakamaster2004@gmail.com', '081257577768', '$2y$10$uq1Eb8y9WfiYrmCyFA7IiOgn153aI60XVz1FiROc/RnDsiBAOYEwm', 'user', 'beginner', '2025-05-09', '12345', NULL, '2025-05-13 16:24:27'),
(11, 'aan567', 'aan2004@gmail.com', '081257577760', '$2y$10$1TnVzOFaEG2F5FNp2SabI.H2wvRTLj3N1mZXIsB7FZiiktH9Olxiu', 'user', 'intermediate', '2025-05-01', '12345', NULL, '2025-05-14 03:51:20'),
(12, 'naren', 'naren@gmail.com', '0812', '$2y$10$YdxCSxPvXaIkFnq071YsBOFkGHJPLaoy1mEKm4Vr06gMIU1vEhvD.', 'user', 'intermediate', '2025-05-01', '12345', NULL, '2025-05-21 02:15:26'),
(13, 'Aandrawing', 'aan2005@gmail.com', '0812575777775', '$2y$10$xYpWQu2TjL8oCI7X7QBe0.JmuybogSThwhbUfCJ4M//N0BcSyWAle', 'user', 'intermediate', '2025-04-10', '12345', NULL, '2025-05-21 02:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_day`
--

CREATE TABLE `user_day` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_day` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_day`
--

INSERT INTO `user_day` (`id_user`, `id_day`) VALUES
(10, 1),
(11, 3),
(11, 4),
(12, 5),
(13, 1),
(13, 2),
(13, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_goal`
--

CREATE TABLE `user_goal` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_goal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_goal`
--

INSERT INTO `user_goal` (`id_user`, `id_goal`) VALUES
(10, 1),
(11, 1),
(11, 3),
(12, 3),
(13, 1),
(13, 2),
(13, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user_session`
--

CREATE TABLE `user_session` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_session` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_session`
--

INSERT INTO `user_session` (`id_user`, `id_session`) VALUES
(10, 1),
(11, 2),
(11, 3),
(12, 1),
(13, 1),
(13, 2),
(13, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `day`
--
ALTER TABLE `day`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `goal`
--
ALTER TABLE `goal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `personal_trainer`
--
ALTER TABLE `personal_trainer`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `noTelp` (`noTelp`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_day`
--
ALTER TABLE `user_day`
  ADD PRIMARY KEY (`id_user`,`id_day`),
  ADD KEY `id_day` (`id_day`);

--
-- Indexes for table `user_goal`
--
ALTER TABLE `user_goal`
  ADD PRIMARY KEY (`id_user`,`id_goal`),
  ADD KEY `id_goal` (`id_goal`);

--
-- Indexes for table `user_session`
--
ALTER TABLE `user_session`
  ADD PRIMARY KEY (`id_user`,`id_session`),
  ADD KEY `id_session` (`id_session`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `day`
--
ALTER TABLE `day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `goal`
--
ALTER TABLE `goal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `personal_trainer`
--
ALTER TABLE `personal_trainer`
  ADD CONSTRAINT `personal_trainer_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `user_day`
--
ALTER TABLE `user_day`
  ADD CONSTRAINT `user_day_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_day_ibfk_2` FOREIGN KEY (`id_day`) REFERENCES `day` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_goal`
--
ALTER TABLE `user_goal`
  ADD CONSTRAINT `user_goal_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_goal_ibfk_2` FOREIGN KEY (`id_goal`) REFERENCES `goal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_session`
--
ALTER TABLE `user_session`
  ADD CONSTRAINT `user_session_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_session_ibfk_2` FOREIGN KEY (`id_session`) REFERENCES `session` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
