-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 04:57 PM
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
-- Database: `social-db_php`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comments_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL,
  `reply_to_comment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comments_id`, `post_id`, `user_id`, `comment_text`, `created_at`, `parent_id`, `reply_to_comment_id`) VALUES
(18, 53, 6, 'test', '2024-10-27 15:46:03', NULL, 0),
(19, 53, 6, 'olaa', '2024-10-27 15:46:51', NULL, 0),
(20, 18, 6, 'hello test', '2024-10-27 15:55:56', NULL, 18),
(21, 18, 6, 'guys hello', '2024-10-27 15:56:14', NULL, 18),
(22, 52, 6, 'tes\\\\\\', '2024-10-27 15:56:22', NULL, 0),
(23, 51, 6, 'olaaa', '2024-10-27 16:06:04', NULL, NULL),
(24, 52, 6, 'hrergtwe', '2024-10-27 16:13:24', NULL, 22),
(25, 53, 6, 'gahiii', '2024-10-27 16:15:57', NULL, 18),
(26, 53, 6, 'ttww', '2024-10-30 15:27:49', NULL, 19),
(27, 47, 6, 'lah', '2024-10-30 15:29:09', NULL, NULL),
(28, 55, 6, 'hello', '2024-10-30 15:43:27', NULL, NULL),
(29, 55, 6, 'adasdad', '2024-10-30 15:43:36', NULL, 28),
(30, 55, 12, 'heiii', '2024-10-30 16:24:03', NULL, 29),
(31, 55, 12, 'helooo', '2024-10-30 16:46:12', NULL, 29),
(32, 47, 12, 'kok gitu?', '2024-10-30 16:46:30', NULL, 27),
(33, 48, 13, 'olaaaaa', '2024-10-31 22:16:30', NULL, NULL),
(34, 48, 13, 'olaa', '2024-10-31 22:20:33', NULL, 33),
(35, 48, 13, 'test', '2024-10-31 22:20:50', NULL, 33),
(36, 48, 13, 'hmpppp', '2024-10-31 22:23:45', NULL, 33),
(37, 55, 12, 'akame', '2024-10-31 22:30:05', NULL, NULL),
(38, 56, 12, 'haiiii', '2024-11-02 06:30:52', NULL, NULL),
(39, 56, 12, 'guys hello\\', '2024-11-02 06:31:02', NULL, 38),
(40, 57, 12, 'bjhjb', '2024-11-02 06:48:49', NULL, NULL),
(41, 56, 12, 'tes', '2024-11-02 07:27:02', NULL, NULL),
(42, 56, 6, 'hrergtwe hard enough', '2024-11-02 07:27:29', NULL, 38),
(43, 56, 6, 'test 22', '2024-11-02 07:39:41', NULL, 38),
(44, 56, 6, 'hiiii kamu bohong', '2024-11-02 07:45:32', NULL, 41),
(45, 57, 16, 'haiii', '2024-11-04 01:50:06', NULL, NULL),
(46, 55, 16, 'haloo', '2024-11-04 01:50:30', NULL, NULL),
(47, 58, 16, 'haii', '2024-11-04 01:52:06', NULL, NULL),
(48, 55, 17, 'olaa', '2024-11-04 21:15:36', NULL, NULL),
(49, 55, 17, 'haiii', '2024-11-04 21:19:41', NULL, 46),
(50, 51, 17, 'hello', '2024-11-04 21:20:00', NULL, NULL),
(51, 59, 17, 'haiii', '2024-11-04 21:50:12', NULL, NULL),
(52, 58, 17, 'heloo', '2024-11-04 21:52:40', NULL, 47),
(53, 59, 6, 'wkkwkwk', '2024-11-07 00:13:41', NULL, 51),
(54, 60, 6, 'hello', '2024-11-07 00:15:35', NULL, NULL),
(55, 60, 6, 'wuhjs', '2024-11-07 00:15:43', NULL, 54),
(56, 60, 6, 'testt', '2024-11-08 04:37:23', NULL, NULL),
(57, 60, 6, 'olaa', '2024-11-08 04:37:31', NULL, 56),
(58, 59, 6, 'hello', '2024-11-10 11:20:16', NULL, NULL),
(59, 58, 6, 'bjhjb', '2024-11-10 11:20:29', NULL, NULL),
(60, 63, 15, 'haloo', '2024-11-10 12:22:26', NULL, NULL),
(61, 60, 15, 'hello', '2024-11-10 12:24:09', NULL, 56),
(62, 64, 18, 'tes134', '2024-11-10 12:26:58', NULL, NULL),
(63, 64, 6, 'hello', '2024-11-12 14:12:45', NULL, 62),
(64, 60, 6, 'test', '2024-11-16 15:18:48', NULL, 54),
(65, 60, 6, 'bjhjb', '2024-11-16 15:44:24', NULL, NULL),
(66, 60, 6, 'hello', '2024-11-16 15:45:27', NULL, 56),
(67, 60, 6, 'tetst', '2024-11-16 15:45:50', NULL, 65),
(68, 60, 6, 'test from share post', '2024-11-16 15:46:29', NULL, NULL),
(69, 69, 6, 'haloo', '2024-11-16 16:30:06', NULL, NULL),
(70, 69, 6, 'guys hello gggg', '2024-11-16 16:30:20', NULL, 69),
(71, 60, 6, 'test dari share', '2024-11-17 14:05:58', NULL, NULL),
(72, 60, 6, 'test dari share', '2024-11-17 14:06:05', NULL, NULL),
(73, 60, 6, 'test dari share lagiiiii', '2024-11-17 14:11:10', NULL, NULL),
(74, 60, 6, 'test dari share lagiiiii ', '2024-11-17 14:12:37', NULL, NULL),
(75, 60, 6, 'haloo', '2024-11-17 14:18:34', NULL, NULL),
(76, 65, 6, 'hallo hallo', '2024-11-17 14:22:32', NULL, NULL),
(77, 60, 6, 'hello guys', '2024-11-17 14:24:16', NULL, NULL),
(78, 60, 6, 'hello guys', '2024-11-17 14:24:17', NULL, NULL),
(79, 60, 6, 'test dari share', '2024-11-17 14:53:58', NULL, NULL),
(80, 65, 6, 'hello test', '2024-11-17 15:27:14', NULL, 76),
(81, 60, 6, 'hello test share', '2024-11-17 15:31:46', NULL, 56),
(82, 60, 6, 'guys hello', '2024-11-17 15:31:55', NULL, 65),
(83, 60, 6, '???', '2024-11-17 15:32:02', NULL, 56);

-- --------------------------------------------------------

--
-- Table structure for table `dislikes`
--

CREATE TABLE `dislikes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dislikes`
--

INSERT INTO `dislikes` (`id`, `user_id`, `post_id`) VALUES
(1, 6, 47),
(2, 11, 51),
(13, 6, 62);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(13, 52, 6, '2024-10-27 16:39:55'),
(15, 56, 16, '2024-11-04 01:50:45'),
(26, 64, 6, '2024-11-15 17:02:32'),
(27, 63, 6, '2024-11-15 17:02:36'),
(30, 61, 6, '2024-11-16 16:25:52'),
(31, 60, 6, '2024-11-17 15:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text DEFAULT NULL,
  `media` varchar(255) DEFAULT NULL,
  `shared_from_post_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `text`, `media`, `shared_from_post_id`, `created_at`) VALUES
(11, 1, 'hello\r\n', NULL, NULL, '2024-09-14 11:37:26'),
(12, 1, 'test', NULL, NULL, '2024-09-14 11:38:23'),
(13, 1, 'this is my poto\r\n', NULL, NULL, '2024-09-14 16:59:13'),
(14, 1, 'ral?', NULL, NULL, '2024-09-14 17:02:36'),
(15, 1, 'potoooo', '54c601682388aeb8a586ccbb4fbd34a3.jpeg', NULL, '2024-09-14 17:28:18'),
(16, 1, 'test', NULL, NULL, '2024-09-14 17:41:36'),
(17, 1, 'nugget', NULL, NULL, '2024-09-14 17:44:28'),
(18, 1, 'test', NULL, NULL, '2024-09-14 17:53:51'),
(19, 1, 'nugghet\r\n', NULL, NULL, '2024-09-14 17:55:12'),
(20, 1, 'test\r\n', NULL, NULL, '2024-09-14 17:59:52'),
(21, 1, 'really?\r\n', NULL, NULL, '2024-09-14 18:00:51'),
(22, 1, 'photo\r\n', '3cdc9004dbe7c5aa94b23dae9d54fc65.jpg', NULL, '2024-09-14 18:09:00'),
(23, 1, 'chiks', '86ea02d12c21165ed4ff4205e56f2811.jpg', NULL, '2024-09-14 18:22:39'),
(24, 7, 'klk', 'd51ed84eb60502e0544c7d86e516bda3.jpg', NULL, '2024-09-14 18:33:28'),
(25, 1, 'hallo\r\n', '2b4e6804c8bc360807d0aefde64a7ba0.jpg', NULL, '2024-09-15 08:34:18'),
(27, 1, 'test', '896aaa24dfcf473d01a13845f8374a35.jpeg', NULL, '2024-09-15 08:35:59'),
(28, 1, 'hai\r\n', '725739f9d81db87b5862fce9b8515e9b.jpg', NULL, '2024-09-15 08:51:47'),
(29, 1, 'test', '3ec290ad822a42d7c6935224fca71f49.jpg', NULL, '2024-09-15 08:55:53'),
(30, 1, 'ddd', 'aeaf6fda8a3b0cde7b5ec7fe47544f0a.jpg', NULL, '2024-09-15 09:06:34'),
(31, 1, 'ttt', 'b1982ce873c267844be2e9a6cf33fb62.jpeg', NULL, '2024-09-15 09:08:59'),
(32, 1, 're', NULL, NULL, '2024-09-15 09:09:20'),
(33, 1, 'r', '8aca3d1e1ced98bd1dde34ed4750aed5.jpg', NULL, '2024-09-15 09:10:16'),
(34, 7, 'd', 'cc1f48a58f02a58e6b717b3e1775faa4.jpg', NULL, '2024-09-15 09:15:30'),
(35, 7, 'hh', '6b8485d4a72a493a42e2a8ab61d2ffc0.jpeg', NULL, '2024-09-15 09:20:32'),
(36, 7, 'ff', 'c49bd51e98ba1c8a6f25ab74225f9a92.jpeg', NULL, '2024-09-15 10:30:35'),
(37, 7, 'dddd', '6d2d2b3b126febf5b6cde96b048be26f.jpg', NULL, '2024-09-15 10:32:14'),
(38, 1, 'aa', NULL, NULL, '2024-09-15 11:20:37'),
(39, 6, 'ccc', NULL, NULL, '2024-09-29 15:03:39'),
(40, 6, 'rwsgfg', '2b169f7776d934e4b9e0bf5611b56728.jpg', NULL, '2024-09-30 01:57:05'),
(44, 6, 'fufufafa', '17c2aa5823fdd29182ee227b127cadac.mp4', NULL, '2024-10-05 00:35:10'),
(45, 6, 'fufufafa', '891caf22185cbe88a8cddecbc8704495.mp4', NULL, '2024-10-05 12:00:00'),
(46, 9, 'test', NULL, NULL, '2024-10-05 12:10:49'),
(47, 6, 'test', '88e7399cae7acd8c39e75e00dcdc51a9.mp4', NULL, '2024-10-08 16:22:43'),
(48, 10, 'burung kuntul', NULL, NULL, '2024-10-10 01:34:34'),
(49, 6, 'test\r\n', '8f7dcc78fa5d5cfc6d9de87e72921dbb.mp4', NULL, '2024-10-14 04:01:19'),
(50, 6, 'kiss\r\n', NULL, NULL, '2024-10-14 04:05:21'),
(51, 11, 'suciiiii hari ini mengajarrrr', NULL, NULL, '2024-10-14 04:40:21'),
(52, 6, 'test', NULL, NULL, '2024-10-17 00:37:48'),
(53, 13, 'hai guys', NULL, NULL, '2024-10-20 23:47:03'),
(55, 6, 'yoooo\r\n', '70582a7c6ed499308141d6afe78f8280.mp4', NULL, '2024-10-30 15:40:45'),
(56, 12, 'k', '538812ab0cfa2b4e60b4719f153a0bc2.dll', NULL, '2024-10-31 22:29:04'),
(57, 12, 'k', '538812ab0cfa2b4e60b4719f153a0bc2.dll', NULL, '2024-10-31 22:29:04'),
(58, 16, 'halooo guys akuu nargis rumaisha\r\n', NULL, NULL, '2024-11-04 01:51:57'),
(59, 16, 'ini dia hasil design kita\r\n', '379333cc7c09e0b7b3e45e21405cedfa.png', NULL, '2024-11-04 01:54:17'),
(60, 17, 'ee', 'fe75fdf518ac6095e8156b68b1a94b5a.mp3', NULL, '2024-11-04 22:19:56'),
(61, 6, 'yoshh', '43d15a599a926f150917596d1a9d89b5.mp3', NULL, '2024-11-08 04:03:05'),
(62, 6, 'www', NULL, NULL, '2024-11-08 04:06:47'),
(63, 13, 'uyyyy', '4255a39d4de4caac30469ebc630c35ef.mp3', NULL, '2024-11-08 06:46:04'),
(64, 18, 'eqw', '2ee1f9bcba6bbbb400214103eb93ed9a.mp3', NULL, '2024-11-10 12:26:23'),
(65, 1, 'Ini adalah postingan asli', 'foto.jpg', NULL, '2024-11-16 07:04:50'),
(67, 1, 'Ini adalah postingan contoh', NULL, NULL, '2024-11-16 07:09:54'),
(68, 6, '', '', NULL, '2024-11-16 01:47:12'),
(69, 6, '', '', NULL, '2024-11-16 01:47:53'),
(70, 6, '', '', NULL, '2024-11-16 01:50:53');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `liked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

CREATE TABLE `shares` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shared_to` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bio` text DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `bio`, `full_name`, `profile_picture`, `updated_at`, `profile_image`) VALUES
(1, 'brody', 'alokfake@exdonuts.com', '$2y$10$iWFNMb2VSLF7BCL7KX8nyeMDrq3iTkxm.l6nzaWwPknnwq3bNUAOm', '2024-09-14 10:03:46', 'nothing', 'mugget', NULL, '2024-10-08 15:12:05', NULL),
(6, 'staff', 'orii@gmail.com', '$2y$10$93/t.4Pb52jCiZN7X7lKSefEUUtI8eLHT4hxfCtQNHCWS5pG16lH.', '2024-09-14 12:13:38', 'hello', 'like', '♱.jpeg', '2024-11-12 00:24:22', NULL),
(7, 'ggggg', '', '$2y$10$374txxCtNj16OC4XErxoSOPiG9GhFe/oJHhm8NXW8jnJTBebYBTla', '2024-09-14 12:17:42', NULL, '', NULL, '2024-10-08 15:12:05', NULL),
(8, 'staf', '', '$2y$10$Se6x2xG/mprGPX2KwQTwcul5Fs5bQI20rzxLQuttG83tzpfnRAUge', '2024-10-01 15:35:24', NULL, '', NULL, '2024-10-08 15:12:05', NULL),
(9, 'tira', 'alokfake@exdonuts.com3', '$2y$10$EdPLw6WVUJY39XfWd68a8.CvWkyqDE/o6g9hP.1gXG5mPehPGS.9S', '2024-10-05 11:59:15', NULL, '', NULL, '2024-10-08 15:12:05', NULL),
(10, 'konan', 'prayogotristan20@gmail.com', '$2y$10$Aeskh/60xo/pf0ZjDZrf1O36FRyzSO7vzdEV49SZJu811LkA72qFm', '2024-10-10 01:34:00', NULL, '', NULL, '2024-10-10 01:34:00', NULL),
(11, 'manuli', 'uchihakanaku@gmail.com', '$2y$10$PDIJW6p9kinygwvmh2Hoy.uwxXRNOamIdpc9G6a0SxeYjRX4/913e', '2024-10-14 04:38:05', NULL, '', NULL, '2024-10-14 04:38:05', NULL),
(12, 'manusia landak', 'sonic@gmail.com', '$2y$10$P.QfHnLpFu4JpdVWuYVoVe3l9rYpvHP2jQD8BXxaIXJMCXwEgDnKW', '2024-10-15 17:04:06', '', 'trusty.people', NULL, '2024-11-02 07:23:18', NULL),
(13, 'manusia kadal', 'antman@gmail.com', '$2y$10$byv0/i/71RoYWPe7RV/54.YzYHxK8GbSek.fwNhaBv9I2YuGE90Oe', '2024-10-15 17:06:25', 'haloo gesss', 'pramodya', NULL, '2024-10-20 23:57:51', NULL),
(14, 'ppp', 'ppp@gmail.com', '$2y$10$kM0IFPhH0hZhK.FHgHRB0.H4EL2ayz4OkENY09OLG7Rz0ajLuCY.G', '2024-10-22 07:19:03', NULL, '', NULL, '2024-10-22 07:19:03', NULL),
(15, 'will_jump', 'jumping@gmail.com', '$2y$10$40pP2DEZtz.gSMOrEXIDXOtMzEbxm0LIr0uL076MmZ89CCbyTygXK', '2024-11-02 06:42:07', NULL, '', NULL, '2024-11-02 06:42:07', NULL),
(16, 'nargis', 'nargisrumaisha@gmail.com', '$2y$10$2rdysumDAPZi4BL8MhN6E.KBdbzSokEzsaDU4G2IoVleGL.zNg1BW', '2024-11-04 01:49:43', NULL, '', NULL, '2024-11-04 01:49:43', NULL),
(17, 'tusk', 'kuro@gmail.com', '$2y$10$wDQU7.yWViP2zv92z11FeOsUH.PXou89WMeQ9gSzeYNukuF2J3F/W', '2024-11-04 21:15:08', NULL, '', NULL, '2024-11-04 21:15:08', NULL),
(18, 'test_this_out', 'brhhhh@gmail.com', '$2y$10$P9dvS9PqKG0okTYBHoG2GupgwHNgSzqz10jl06obsLHuqsZlWitKe', '2024-11-10 12:24:58', NULL, '', NULL, '2024-11-10 12:24:58', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comments_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dislikes`
--
ALTER TABLE `dislikes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shares`
--
ALTER TABLE `shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comments_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `dislikes`
--
ALTER TABLE `dislikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shares`
--
ALTER TABLE `shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `dislikes`
--
ALTER TABLE `dislikes`
  ADD CONSTRAINT `dislikes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dislikes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `shares`
--
ALTER TABLE `shares`
  ADD CONSTRAINT `shares_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shares_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
