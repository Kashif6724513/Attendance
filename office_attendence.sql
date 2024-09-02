-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2024 at 04:27 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `office_attendence`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendences`
--

CREATE TABLE `attendences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attend_date` date NOT NULL,
  `checkin` varchar(200) DEFAULT NULL,
  `break_in` varchar(200) DEFAULT NULL,
  `break_out` varchar(200) DEFAULT NULL,
  `checkout` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendences`
--

INSERT INTO `attendences` (`id`, `user_id`, `attend_date`, `checkin`, `break_in`, `break_out`, `checkout`) VALUES
(6, 6, '2024-09-02', '08:42:59 AM', '08:43:05 AM', '08:43:09 AM', '08:43:13 AM'),
(7, 5, '2024-09-02', '09:20:38 AM', '09:20:42 AM', '09:20:46 AM', '09:20:49 AM');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(150) NOT NULL,
  `lname` varchar(150) NOT NULL,
  `desgination` varchar(200) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `role` enum('admin','employee') NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `desgination`, `address`, `phone`, `gender`, `role`, `username`, `email`, `password`) VALUES
(5, 'Muhammad', 'Kashif', 'Frontend Developer', '216,lhr', 12345678, 'Male', 'employee', 'Kashif123', 'kashif1@gmail.com', '$2y$10$KYGY8OHmqw2BvisfzC30HuENueACPZjO.T.CATkomMfKXQecLwy82'),
(6, 'Qmar', 'Rafhan', 'Backend Developer', '216,lhr', 1234567, 'Male', 'admin', 'qamarrafhan', 'qamar@gmail.com', '$2y$10$eI9qq1ONOCMdw6DGfflHW.DGiVx2H9DLxzsOd29pAxg3NLVkOFcLK'),
(7, 'Irfan', 'Qadir', 'Frontend Developer', '216,lhr', 123456, 'Male', 'employee', 'irfan', 'irfan@gmail.com', '$2y$10$kppLg4oWCe8uRQQAuVxbMupACndmrVNHWwYTI7tsprjBC5PMytYsW'),
(8, 'Mr', 'Salman', 'Backend Developer', '216,lhr', 2345123, 'Male', 'employee', 'salman', 'salman@gmail.com', '$2y$10$BUXyetIGioWrzRuvE6I4dOYlJir5dUlYP6MqwY.C70Rlu4IcgqHwm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendences`
--
ALTER TABLE `attendences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendences`
--
ALTER TABLE `attendences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
