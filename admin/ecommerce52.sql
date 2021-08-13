-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2021 at 09:50 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce52`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(4) NOT NULL,
  `author_name` varchar(121) NOT NULL,
  `blogs_type` varchar(121) NOT NULL,
  `nationality` varchar(121) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `author_name`, `blogs_type`, `nationality`, `added_at`) VALUES
(1, 'walid', 'drama', 'egypt', '2021-08-06 05:11:06'),
(2, 'hossam', 'comics', 'usa', '2021-08-04 10:05:08'),
(3, 'ebrahim', 'history', 'uae', '2021-08-04 10:05:48'),
(4, 'osama', 'kids', 'egypt', '2021-08-04 10:05:48'),
(5, 'pierre', 'history', 'Germany', '2021-08-05 08:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `blog_id` int(4) NOT NULL,
  `title` varchar(121) NOT NULL,
  `descrip` varchar(121) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `author` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`blog_id`, `title`, `descrip`, `created_at`, `author`) VALUES
(4, 'title 8', 'article description 2', '2021-08-06 06:29:57', 3),
(5, 'title 3', 'article description 3', '2021-08-04 10:08:35', 4),
(12, 'test', 'test', '2021-08-06 05:26:47', 2),
(14, 'test', 'test', '2021-08-06 06:47:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(4) NOT NULL,
  `role_name` varchar(121) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'admin'),
(2, 'moderator'),
(3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(121) NOT NULL,
  `password` varchar(121) NOT NULL,
  `fullname` varchar(121) NOT NULL,
  `email` varchar(121) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` int(4) NOT NULL,
  `image` varchar(121) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `fullname`, `email`, `created_at`, `role`, `image`) VALUES
(2, 'pieere', '00fd4b4549a1094aae926ef62e9dbd3cdcc2e456', 'pieer', 'pieer@info.com', '2021-07-23 07:18:31', 2, ''),
(3, 'hossam', 'd6a9450dc08555d6ecfaf7162e5267f401e6dd9a', 'hossam', 'hossam@info.com', '2021-07-23 07:19:37', 2, ''),
(4, 'AMIT', '011c945f30ce2cbafc452f39840f025693339c42', 'AMIT', 'amit@info.com', '2021-07-30 08:13:26', 1, ''),
(5, 'test', '7bd2d3137d80e81fe8a2781e82bc0f777fe87b01', 'AMIT', 'amit@info.com', '2021-07-23 08:29:18', 1, ''),
(17, 'walid', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'walid', 'walid@info.com', '2021-08-13 07:04:22', 3, '95_learning.jpg'),
(18, 'hossam2', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'hossam', 'hossam@info.com', '2021-08-13 07:05:41', 3, '20_amit.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blog_id`),
  ADD KEY `blog_author` (`author`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_user` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `author_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blog_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blog_author` FOREIGN KEY (`author`) REFERENCES `authors` (`author_id`) ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `role_user` FOREIGN KEY (`role`) REFERENCES `roles` (`role_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
