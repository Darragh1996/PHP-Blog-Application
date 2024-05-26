-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 27, 2024 at 12:14 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Database: `blog_app`
--

-- --------------------------------------------------------
--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `edit_date` datetime DEFAULT NULL,
  `title` varchar(40) NOT NULL,
  `text` text NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (
    `id`,
    `user_id`,
    `date`,
    `edit_date`,
    `title`,
    `text`
  )
VALUES (
    5,
    25,
    '2024-05-22 17:55:35',
    NULL,
    'my second blog updated',
    'Hello, World...Again!!! This is my first blog.'
  ),
  (
    7,
    25,
    '2024-05-22 17:59:14',
    '2024-05-26 21:02:38',
    'my fourtieth blog updated',
    'Hello, World...Again!!! This is my first blog.'
  ),
  (
    10,
    26,
    '2024-05-24 20:38:20',
    NULL,
    'gerry\'s first blog',
    'hello everyone!'
  ),
  (
    11,
    25,
    '2024-05-26 20:21:14',
    NULL,
    'Made in the browser',
    'This is the first blog made using the browser api.'
  );
-- --------------------------------------------------------
--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `blog_id`, `user_id`, `text`, `date`)
VALUES (4, 7, 25, 'new comment', '2024-05-26 23:09:00');
-- --------------------------------------------------------
--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `email` varchar(40) NOT NULL,
  `hashed_password` varchar(60) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `hashed_password`)
VALUES (
    25,
    'darragh',
    'darragh@email.com',
    '$2y$10$mSRmc494pIoEc2O8tgW2meA1wuYDKDoLYCqtIlSX180p2SV4ROBb.'
  ),
  (
    26,
    'gerry',
    'gerry@email.com',
    '$2y$10$HL1EdzpqvGhQ92ALZpRJoe66b3uymd1ogZWGAWDLQCBpQuHvT82Ke'
  ),
  (
    27,
    'bobby',
    'bobby@email.com',
    '$2y$10$wX9Y6hit5CzyErPLBsq74eFswLnqt0MsnjeR4cv5us9QNm6Blukhi'
  );
--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
ADD PRIMARY KEY (`id`);
--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
ADD PRIMARY KEY (`id`),
  ADD KEY `fk_blog` (`blog_id`);
--
-- Indexes for table `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 12;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 28;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
ADD CONSTRAINT `fk_blog` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;