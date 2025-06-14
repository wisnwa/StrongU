-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 07:15 PM
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
-- Table structure for table `certificate`
--

CREATE TABLE `certificate` (
  `id_certif` int(11) NOT NULL,
  `id_pt` int(11) UNSIGNED NOT NULL,
  `filepath` varchar(500) NOT NULL,
  `upload_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate`
--

INSERT INTO `certificate` (`id_certif`, `id_pt`, `filepath`, `upload_at`) VALUES
(1, 11, 'sertif_trainer/sertif1_11.png', '2025-06-09 17:42:46'),
(2, 11, 'sertif_trainer/sertif2_11.png', '2025-06-10 00:36:18');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `classID` int(11) NOT NULL,
  `id_pt` int(11) UNSIGNED NOT NULL,
  `sessionCount` int(11) NOT NULL,
  `pricePerSession` decimal(10,2) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`classID`, `id_pt`, `sessionCount`, `pricePerSession`, `createdAt`) VALUES
(1, 11, 4, 200000.00, '2025-06-09 10:43:08'),
(2, 11, 8, 120000.00, '2025-06-09 17:36:44'),
(3, 11, 12, 100000.00, '2025-06-09 17:36:58');

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
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goal`
--

INSERT INTO `goal` (`id`, `name`, `description`) VALUES
(1, 'Weight Loss', 'This goal is tailored for individuals aiming to reduce excess body fat and achieve a healthier body composition. The program typically combines calorie-controlled nutrition plans with a variety of physical activities, including cardio workouts, high-intensity interval training (HIIT), and strength training. The primary focus is on creating a consistent calorie deficit in a safe and sustainable way to promote gradual fat loss. Progress is monitored not just through weight, but also changes in bod'),
(2, 'Firming & Toning', 'Ideal for those who want a lean, defined look without significantly increasing muscle mass. The firming and toning program emphasizes high-repetition resistance training using lighter weights or bodyweight exercises to target specific muscle groups. Combined with moderate cardio and a balanced diet, this approach helps tighten the body, reduce sagging areas, and enhance muscle visibility. It is especially popular among individuals seeking aesthetic improvements such as a flatter stomach, tighter'),
(3, 'Increase Muscle', 'This goal focuses on hypertrophy, the growth and enlargement of muscle fibers through structured strength training and increased caloric intake. Participants engage in progressive overload using heavier weights, lower repetitions, and compound movements like squats, deadlifts, and bench presses. Proper recovery, sleep, and a protein-rich diet are critical components of this goal. It is suited for individuals looking to build size, strength, and a more muscular physique over time.'),
(4, 'Strength', 'The strength program is designed to enhance the maximum amount of force your muscles can produce. Unlike muscle-building routines that focus on volume, strength training prioritizes lifting heavier weights with lower repetitions and longer rest periods. It often includes powerlifting elements and compound movements, aiming to improve functional performance, bone density, and overall resilience. This goal is ideal for those who want to lift heavier, move better, and improve their physical power.'),
(5, 'Endurance Training', 'Targeted at those wanting to improve stamina and cardiovascular efficiency, endurance training involves sustained physical activity over extended periods. Whether it\'s long-distance running, swimming, cycling, or circuit workouts, the goal is to increase aerobic capacity and delay fatigue. It enhances the efficiency of your heart, lungs, and muscles, enabling you to perform daily tasks or sports activities with greater ease. This program often appeals to athletes, runners, and anyone training fo'),
(6, 'Weight Gain', 'This program is ideal for individuals who are underweight or have difficulty gaining weight due to a fast metabolism or other factors. The approach combines a high-calorie, nutrient-dense diet with resistance training to ensure that the weight gained is predominantly muscle mass rather than fat. The goal is to reach a healthier, more balanced body weight while building strength and improving overall body composition. It is carefully structured to avoid unhealthy bulking practices.'),
(7, 'Flexibility', 'Flexibility training improves the range of motion in joints and the elasticity of muscles and tendons. The goal is to reduce stiffness, improve posture, and prevent injury by increasing your body\'s ability to move comfortably and efficiently. This is achieved through dynamic stretches, static holds, and mobility drills. Flexibility is often underestimated but is essential for athletes, office workers, and older adults alike. It\'s also a perfect complement to strength or cardio programs.'),
(8, 'Aerobic Fitness', 'Aerobic fitness focuses on improving your heart and lung capacity through continuous, rhythmic physical activity. This program includes exercises like running, cycling, brisk walking, or aerobic classes that elevate your heart rate over a sustained period. Benefits include enhanced cardiovascular health, lower blood pressure, increased lung capacity, and better endurance. It\'s suitable for anyone looking to improve general fitness, manage stress, and boost daily energy levels.'),
(9, 'Body Building', 'A highly disciplined and aesthetic-driven goal, bodybuilding focuses on maximizing muscle symmetry, definition, and overall physique. The training involves advanced weightlifting techniques, muscle isolation exercises, and strategic meal planning to support muscle growth and fat reduction. Bodybuilding requires strict consistency, a clear understanding of macronutrient timing, and often cycles of bulking and cutting phases. It\'s ideal for those who are serious about transforming their body into ');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `messageID` int(11) NOT NULL,
  `roomID` int(11) NOT NULL,
  `senderID` int(11) UNSIGNED NOT NULL,
  `messageText` text NOT NULL,
  `sentAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `isRead` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`messageID`, `roomID`, `senderID`, `messageText`, `sentAt`, `isRead`) VALUES
(1, 1, 10, 'test tod', '2025-06-13 17:09:22', 1),
(2, 1, 10, 'anjay', '2025-06-13 17:10:30', 1),
(3, 1, 11, 'krene', '2025-06-13 17:11:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentID` int(11) NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_trainer` int(11) UNSIGNED NOT NULL,
  `id_class` int(11) NOT NULL,
  `method` varchar(50) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `adminFee` decimal(10,2) NOT NULL,
  `totalPayment` decimal(12,2) NOT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `paidAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentID`, `id_user`, `id_trainer`, `id_class`, `method`, `subtotal`, `adminFee`, `totalPayment`, `status`, `paidAt`) VALUES
(1, 10, 11, 1, 'QRIS', 800000.00, 40000.00, 840000.00, 'success', '2025-06-13 15:52:01');

-- --------------------------------------------------------

--
-- Table structure for table `personal_trainer`
--

CREATE TABLE `personal_trainer` (
  `ptID` int(11) UNSIGNED NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_trainer`
--

INSERT INTO `personal_trainer` (`ptID`, `address`, `experience`, `description`) VALUES
(11, 'Surabaya', 3, 'Saya adalah seorang personal trainer profesional dengan spesialisasi dalam weight gain dan body building. Dengan pendekatan yang berbasis sains dan pengalaman bertahun-tahun, saya membantu klien mencapai tubuh yang lebih kuat, berotot, dan ideal sesuai dengan tujuan mereka. Program saya dirancang secara personal untuk memastikan progres yang optimal, menciptakan pola latihan yang efektif, nutrisi yang tepat, serta strategi pemulihan yang maksimal.'),
(14, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `roomID` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`roomID`, `createdAt`) VALUES
(1, '2025-06-13 17:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `room_participants`
--

CREATE TABLE `room_participants` (
  `roomID` int(11) NOT NULL,
  `userID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_participants`
--

INSERT INTO `room_participants` (`roomID`, `userID`) VALUES
(1, 10),
(1, 11);

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
(10, 'aan123', 'rakamaster2004@gmail.com', '081257577768', '$2y$10$uq1Eb8y9WfiYrmCyFA7IiOgn153aI60XVz1FiROc/RnDsiBAOYEwm', 'user', 'beginner', '2025-05-09', '12345', 'foto_profile/profile_10.jpg', '2025-05-13 16:24:27'),
(11, 'aan567', 'aan2004@gmail.com', '081257577760', '$2y$10$1TnVzOFaEG2F5FNp2SabI.H2wvRTLj3N1mZXIsB7FZiiktH9Olxiu', 'pt', 'intermediate', '2025-05-01', '12345', 'foto_profile/profile_11.png', '2025-05-14 03:51:20'),
(14, 'aan987', 'rakamaster2005@gmail.com', '081257577750', '$2y$10$o3myayz8obhuYEgHOECPMeTXR5y82RGkV8z7KzYrxRTIQiDK1fgUe', 'pt', 'beginner', '2025-06-03', '12345', 'foto_profile/profile_14.jpg', '2025-06-09 10:44:26');

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
(14, 2);

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
(11, 6),
(11, 7),
(11, 9),
(14, 7);

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
(14, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `certificate`
--
ALTER TABLE `certificate`
  ADD PRIMARY KEY (`id_certif`),
  ADD KEY `idx_id_pt` (`id_pt`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`classID`),
  ADD KEY `idx_id_pt` (`id_pt`);

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageID`),
  ADD KEY `idx_room` (`roomID`),
  ADD KEY `fk_message_sender` (`senderID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_trainer` (`id_trainer`),
  ADD KEY `id_class` (`id_class`);

--
-- Indexes for table `personal_trainer`
--
ALTER TABLE `personal_trainer`
  ADD PRIMARY KEY (`ptID`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`roomID`);

--
-- Indexes for table `room_participants`
--
ALTER TABLE `room_participants`
  ADD PRIMARY KEY (`roomID`,`userID`),
  ADD KEY `fk_participant_user` (`userID`);

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
-- AUTO_INCREMENT for table `certificate`
--
ALTER TABLE `certificate`
  MODIFY `id_certif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `classID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `messageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `roomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `certificate`
--
ALTER TABLE `certificate`
  ADD CONSTRAINT `fk_certificate_trainer` FOREIGN KEY (`id_pt`) REFERENCES `personal_trainer` (`ptID`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_message_room` FOREIGN KEY (`roomID`) REFERENCES `rooms` (`roomID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_message_sender` FOREIGN KEY (`senderID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_class` FOREIGN KEY (`id_class`) REFERENCES `class` (`classID`),
  ADD CONSTRAINT `fk_payment_trainer` FOREIGN KEY (`id_trainer`) REFERENCES `personal_trainer` (`ptID`),
  ADD CONSTRAINT `fk_payment_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`userID`);

--
-- Constraints for table `personal_trainer`
--
ALTER TABLE `personal_trainer`
  ADD CONSTRAINT `fk_trainer_user` FOREIGN KEY (`ptID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `room_participants`
--
ALTER TABLE `room_participants`
  ADD CONSTRAINT `fk_participant_room` FOREIGN KEY (`roomID`) REFERENCES `rooms` (`roomID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_participant_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

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
