-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 22, 2018 at 12:03 PM
-- Server version: 5.6.41-84.1-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minditzc_mabwe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `forgetPass` varchar(255) NOT NULL DEFAULT '0',
  `deviceToken` varchar(255) NOT NULL DEFAULT '0',
  `deviceType` int(11) NOT NULL DEFAULT '0' COMMENT '1 for Android, 2 for IOS, 0 for web',
  `authToken` varchar(255) NOT NULL DEFAULT '0',
  `profileImage` varchar(200) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fullName`, `email`, `userName`, `password`, `forgetPass`, `deviceToken`, `deviceType`, `authToken`, `profileImage`, `status`, `crd`, `upd`) VALUES
(1, 'Rakesh Patel', 'admin@mabwe.com', 'admin@mabwe.com', '$2y$10$xPf7w2ag3gjgJs.MLBpRD.LkRITSX/cBuYgDApo37Nn4dW2VXIcte', '', '', 0, '', '1591bc65d42f534a83981dc5cf818924.jpg', 1, '2018-04-20 08:04:50', '2018-10-11 10:10:16');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryId` bigint(20) NOT NULL,
  `categoryName` varchar(100) NOT NULL,
  `categoryImage` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for active, 0 for deactive',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryId`, `categoryName`, `categoryImage`, `status`, `crd`, `upd`) VALUES
(1, 'Connect', '1a66b53fff166259b864363ccbe48d1e.png', 1, '2018-08-29 08:36:25', '0000-00-00 00:00:00'),
(2, 'Career', '8a05e9a965c69a8628df00c3a99ca33c.png', 1, '2018-09-22 13:52:17', '0000-00-00 00:00:00'),
(3, 'Marketplace', 'ac674469db7deda9788442c9dcacc2df.png', 1, '2018-09-24 14:05:49', '0000-00-00 00:00:00'),
(4, 'Mabwe411', 'cb2b69a4cc493baa10f4e8eda00d9420.png', 1, '2018-09-24 14:27:25', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentId` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for active comment, 0 for deleted',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `groupId` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `groupName` varchar(100) NOT NULL,
  `groupImage` varchar(200) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for active, 0 for deactive',
  `isAdmin` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`groupId`, `user_id`, `groupName`, `groupImage`, `category_id`, `status`, `isAdmin`, `crd`, `upd`) VALUES
(132, 41, '0', 'ba0dc6eabe9634a1bcad3e9f421c4c1e.jpg', 1, 1, 1, '2018-10-16 14:12:06', '2018-10-17 06:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `group_comments`
--

CREATE TABLE `group_comments` (
  `commentId` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for active, 0 for deactive',
  `comment` text NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_comments`
--

INSERT INTO `group_comments` (`commentId`, `user_id`, `group_id`, `status`, `comment`, `crd`, `upd`) VALUES
(294, 41, 132, 1, 'shjs', '2018-10-16 14:13:05', '2018-10-16 14:13:05'),
(295, 22, 132, 1, 'xjdj', '2018-10-16 14:13:08', '2018-10-16 14:13:08'),
(296, 22, 132, 1, 'Wow !', '2018-10-17 12:19:59', '2018-10-17 12:19:59');

-- --------------------------------------------------------

--
-- Table structure for table `group_likes`
--

CREATE TABLE `group_likes` (
  `likeId` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for like, 0 for unlike ',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_likes`
--

INSERT INTO `group_likes` (`likeId`, `user_id`, `group_id`, `status`, `crd`, `upd`) VALUES
(102, 22, 132, 1, '2018-10-16 14:12:30', '2018-10-16 14:12:30'),
(103, 25, 132, 1, '2018-10-17 06:11:10', '2018-10-17 06:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `groupMemberId` bigint(20) NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`groupMemberId`, `group_id`, `user_id`, `crd`, `upd`) VALUES
(1, 42, 22, '2018-09-18 12:35:48', '2018-09-18 12:35:48'),
(2, 42, 25, '2018-09-19 07:25:14', '2018-09-19 07:25:14'),
(3, 43, 22, '2018-09-19 13:19:46', '2018-09-19 13:19:46'),
(4, 44, 22, '2018-09-19 13:43:46', '2018-09-19 13:43:46'),
(5, 45, 22, '2018-09-19 14:01:24', '2018-09-19 14:01:24'),
(6, 46, 22, '2018-09-19 14:09:36', '2018-09-19 14:09:36'),
(7, 47, 22, '2018-09-19 14:18:36', '2018-09-19 14:18:36'),
(8, 48, 22, '2018-09-19 14:19:40', '2018-09-19 14:19:40'),
(9, 49, 25, '2018-09-20 05:32:31', '2018-09-20 05:32:31'),
(10, 49, 22, '2018-09-20 05:33:07', '2018-09-20 05:33:07'),
(11, 50, 25, '2018-09-20 05:38:30', '2018-09-20 05:38:30'),
(12, 51, 25, '2018-09-20 06:29:24', '2018-09-20 06:29:24'),
(13, 52, 25, '2018-09-20 06:29:59', '2018-09-20 06:29:59'),
(14, 53, 25, '2018-09-20 06:30:47', '2018-09-20 06:30:47'),
(15, 54, 25, '2018-09-20 06:31:14', '2018-09-20 06:31:14'),
(16, 54, 22, '2018-09-20 06:31:43', '2018-09-20 06:31:43'),
(17, 53, 22, '2018-09-20 06:32:03', '2018-09-20 06:32:03'),
(18, 52, 22, '2018-09-20 06:33:16', '2018-09-20 06:33:16'),
(19, 55, 25, '2018-09-20 06:59:44', '2018-09-20 06:59:44'),
(20, 56, 25, '2018-09-20 07:00:50', '2018-09-20 07:00:50'),
(21, 57, 25, '2018-09-20 07:12:43', '2018-09-20 07:12:43'),
(22, 58, 25, '2018-09-20 07:12:59', '2018-09-20 07:12:59'),
(23, 59, 25, '2018-09-20 07:18:02', '2018-09-20 07:18:02'),
(24, 60, 25, '2018-09-20 07:18:22', '2018-09-20 07:18:22'),
(25, 61, 25, '2018-09-20 07:18:50', '2018-09-20 07:18:50'),
(26, 62, 25, '2018-09-20 07:20:49', '2018-09-20 07:20:49'),
(27, 63, 25, '2018-09-20 07:30:58', '2018-09-20 07:30:58'),
(28, 62, 23, '2018-09-20 07:54:02', '2018-09-20 07:54:02'),
(29, 63, 23, '2018-09-20 07:54:17', '2018-09-20 07:54:17'),
(30, 64, 26, '2018-09-20 07:56:52', '2018-09-20 07:56:52'),
(31, 64, 23, '2018-09-20 07:58:57', '2018-09-20 07:58:57'),
(32, 64, 25, '2018-09-20 14:26:11', '2018-09-20 14:26:11'),
(33, 65, 25, '2018-09-21 10:18:35', '2018-09-21 10:18:35'),
(34, 66, 25, '2018-09-21 10:34:39', '2018-09-21 10:34:39'),
(35, 67, 22, '2018-09-21 10:49:38', '2018-09-21 10:49:38'),
(36, 68, 22, '2018-09-21 11:12:30', '2018-09-21 11:12:30'),
(37, 69, 22, '2018-09-21 11:16:07', '2018-09-21 11:16:07'),
(38, 64, 28, '2018-09-21 12:57:54', '2018-09-21 12:57:54'),
(39, 64, 22, '2018-09-22 05:54:11', '2018-09-22 05:54:11'),
(40, 66, 22, '2018-09-22 09:46:45', '2018-09-22 09:46:45'),
(41, 70, 22, '2018-09-22 10:24:33', '2018-09-22 10:24:33'),
(42, 70, 25, '2018-09-22 10:27:09', '2018-09-22 10:27:09'),
(43, 71, 22, '2018-09-22 10:31:03', '2018-09-22 10:31:03'),
(44, 72, 22, '2018-09-22 10:31:44', '2018-09-22 10:31:44'),
(45, 72, 25, '2018-09-22 10:35:39', '2018-09-22 10:35:39'),
(46, 71, 25, '2018-09-22 10:36:02', '2018-09-22 10:36:02'),
(47, 73, 22, '2018-09-22 11:24:39', '2018-09-22 11:24:39'),
(48, 74, 22, '2018-09-22 11:25:30', '2018-09-22 11:25:30'),
(49, 74, 28, '2018-09-22 11:30:32', '2018-09-22 11:30:32'),
(50, 73, 25, '2018-09-22 12:12:41', '2018-09-22 12:12:41'),
(51, 75, 22, '2018-09-22 14:00:22', '2018-09-22 14:00:22'),
(52, 76, 22, '2018-09-22 14:22:35', '2018-09-22 14:22:35'),
(53, 77, 25, '2018-09-22 14:46:34', '2018-09-22 14:46:34'),
(54, 77, 22, '2018-09-22 14:51:54', '2018-09-22 14:51:54'),
(55, 76, 28, '2018-09-24 05:25:23', '2018-09-24 05:25:23'),
(56, 76, 25, '2018-09-24 10:41:50', '2018-09-24 10:41:50'),
(57, 78, 22, '2018-09-24 10:50:39', '2018-09-24 10:50:39'),
(58, 79, 22, '2018-09-24 10:51:05', '2018-09-24 10:51:05'),
(59, 80, 22, '2018-09-24 10:51:45', '2018-09-24 10:51:45'),
(60, 81, 22, '2018-09-24 10:52:09', '2018-09-24 10:52:09'),
(61, 81, 25, '2018-09-24 10:53:02', '2018-09-24 10:53:02'),
(62, 80, 25, '2018-09-24 10:54:46', '2018-09-24 10:54:46'),
(63, 82, 22, '2018-09-25 05:31:54', '2018-09-25 05:31:54'),
(64, 83, 22, '2018-09-25 06:17:10', '2018-09-25 06:17:10'),
(65, 84, 22, '2018-09-25 07:03:42', '2018-09-25 07:03:42'),
(66, 85, 22, '2018-09-25 09:36:33', '2018-09-25 09:36:33'),
(67, 86, 22, '2018-09-25 10:11:23', '2018-09-25 10:11:23'),
(68, 87, 22, '2018-09-25 11:05:53', '2018-09-25 11:05:53'),
(69, 88, 22, '2018-09-25 11:18:39', '2018-09-25 11:18:39'),
(70, 89, 22, '2018-09-25 11:19:17', '2018-09-25 11:19:17'),
(71, 90, 22, '2018-09-25 11:20:41', '2018-09-25 11:20:41'),
(72, 91, 22, '2018-09-25 11:24:26', '2018-09-25 11:24:26'),
(73, 92, 22, '2018-09-25 12:13:25', '2018-09-25 12:13:25'),
(74, 93, 22, '2018-09-25 12:50:09', '2018-09-25 12:50:09'),
(75, 94, 22, '2018-09-25 13:00:41', '2018-09-25 13:00:41'),
(76, 95, 22, '2018-09-25 14:11:55', '2018-09-25 14:11:55'),
(77, 96, 22, '2018-09-25 14:18:28', '2018-09-25 14:18:28'),
(78, 97, 22, '2018-09-25 14:28:13', '2018-09-25 14:28:13'),
(79, 98, 22, '2018-09-26 05:15:38', '2018-09-26 05:15:38'),
(80, 99, 22, '2018-09-26 05:19:45', '2018-09-26 05:19:45'),
(81, 100, 22, '2018-09-26 05:27:58', '2018-09-26 05:27:58'),
(82, 101, 22, '2018-09-26 05:30:53', '2018-09-26 05:30:53'),
(83, 102, 22, '2018-09-26 05:37:42', '2018-09-26 05:37:42'),
(84, 103, 22, '2018-09-26 05:41:19', '2018-09-26 05:41:19'),
(85, 104, 22, '2018-09-26 07:10:05', '2018-09-26 07:10:05'),
(86, 105, 22, '2018-09-26 07:15:59', '2018-09-26 07:15:59'),
(87, 106, 22, '2018-09-26 07:17:00', '2018-09-26 07:17:00'),
(88, 107, 25, '2018-09-26 07:47:57', '2018-09-26 07:47:57'),
(89, 107, 22, '2018-09-26 07:48:10', '2018-09-26 07:48:10'),
(90, 108, 22, '2018-09-27 06:46:43', '2018-09-27 06:46:43'),
(91, 109, 22, '2018-09-27 07:03:18', '2018-09-27 07:03:18'),
(92, 110, 22, '2018-09-27 09:21:49', '2018-09-27 09:21:49'),
(93, 111, 22, '2018-09-27 09:35:30', '2018-09-27 09:35:30'),
(94, 109, 25, '2018-09-27 10:50:13', '2018-09-27 10:50:13'),
(95, 110, 25, '2018-09-27 11:30:01', '2018-09-27 11:30:01'),
(96, 112, 22, '2018-09-27 12:17:10', '2018-09-27 12:17:10'),
(97, 113, 22, '2018-09-27 12:36:21', '2018-09-27 12:36:21'),
(98, 114, 30, '2018-09-27 12:58:44', '2018-09-27 12:58:44'),
(99, 114, 22, '2018-09-27 13:01:09', '2018-09-27 13:01:09'),
(100, 115, 30, '2018-09-27 13:32:35', '2018-09-27 13:32:35'),
(101, 116, 30, '2018-09-27 14:26:45', '2018-09-27 14:26:45'),
(102, 117, 30, '2018-09-27 14:27:18', '2018-09-27 14:27:18'),
(103, 118, 30, '2018-09-27 14:28:14', '2018-09-27 14:28:14'),
(104, 118, 22, '2018-09-27 14:30:18', '2018-09-27 14:30:18'),
(105, 119, 22, '2018-09-28 13:20:57', '2018-09-28 13:20:57'),
(106, 120, 22, '2018-09-29 06:31:54', '2018-09-29 06:31:54'),
(107, 120, 25, '2018-09-29 06:47:54', '2018-09-29 06:47:54'),
(108, 121, 22, '2018-09-29 09:24:01', '2018-09-29 09:24:01'),
(109, 121, 25, '2018-09-29 09:34:11', '2018-09-29 09:34:11'),
(110, 122, 22, '2018-09-29 09:49:34', '2018-09-29 09:49:34'),
(111, 123, 22, '2018-09-29 11:26:21', '2018-09-29 11:26:21'),
(112, 124, 22, '2018-09-29 11:27:26', '2018-09-29 11:27:26'),
(113, 125, 22, '2018-09-29 12:19:11', '2018-09-29 12:19:11'),
(114, 126, 22, '2018-09-29 12:25:47', '2018-09-29 12:25:47'),
(115, 127, 22, '2018-10-09 09:11:56', '2018-10-09 09:11:56'),
(116, 127, 33, '2018-10-11 09:36:48', '2018-10-11 09:36:48'),
(117, 127, 46, '2018-10-15 06:44:02', '2018-10-15 06:44:02'),
(118, 128, 22, '2018-10-15 12:44:11', '2018-10-15 12:44:11'),
(119, 129, 49, '2018-10-15 12:45:27', '2018-10-15 12:45:27'),
(120, 129, 22, '2018-10-15 12:49:47', '2018-10-15 12:49:47'),
(121, 128, 49, '2018-10-15 13:10:49', '2018-10-15 13:10:49'),
(122, 130, 53, '2018-10-16 12:23:30', '2018-10-16 12:23:30'),
(123, 131, 41, '2018-10-16 13:46:35', '2018-10-16 13:46:35'),
(124, 131, 22, '2018-10-16 13:56:49', '2018-10-16 13:56:49'),
(125, 132, 41, '2018-10-16 14:12:06', '2018-10-16 14:12:06'),
(126, 132, 22, '2018-10-16 14:13:08', '2018-10-16 14:13:08'),
(127, 133, 24, '2018-10-17 05:31:16', '2018-10-17 05:31:16'),
(128, 134, 46, '2018-10-17 07:36:15', '2018-10-17 07:36:15');

-- --------------------------------------------------------

--
-- Table structure for table `group_tag_mapping`
--

CREATE TABLE `group_tag_mapping` (
  `groupMapTagId` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_tag_mapping`
--

INSERT INTO `group_tag_mapping` (`groupMapTagId`, `tag_id`, `group_id`, `crd`) VALUES
(538, 118, 132, '2018-10-17 06:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `likeId` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for like 0 for dislike',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `notification_by` varchar(100) NOT NULL DEFAULT '0' COMMENT '0:admin',
  `notification_for` int(11) NOT NULL,
  `notification_message` varchar(500) NOT NULL,
  `is_read` int(11) NOT NULL DEFAULT '0' COMMENT '0:unread 1:read',
  `status` varchar(100) NOT NULL DEFAULT '1' COMMENT '1:active 0:inactive',
  `notification_type` varchar(100) NOT NULL,
  `created_on` varchar(200) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `notification_by`, `notification_for`, `notification_message`, `is_read`, `status`, `notification_type`, `created_on`, `crd`) VALUES
(184, '25', 41, '{\"title\":\"Connect\",\"body\":\"ravi liked on your post.\",\"type\":\"mabwe_groupLike\",\"userId\":\"25\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/extra\\/company.png\",\"notify_id\":\"131\",\"click_action\":\"ShowGroupDetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_groupLike', '', '2018-10-16 13:56:18'),
(185, '22', 41, '{\"title\":\"Connect\",\"body\":\"raj commented on your post.\",\"type\":\"mabwe_groupComment\",\"userId\":\"22\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/extra\\/company.png\",\"notify_id\":\"131\",\"click_action\":\"ShowGroupDetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_groupComment', '', '2018-10-16 13:56:49'),
(186, '22', 41, '{\"title\":\"Connect\",\"body\":\"raj liked on your post.\",\"type\":\"mabwe_groupLike\",\"userId\":\"22\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/extra\\/company.png\",\"notify_id\":\"132\",\"click_action\":\"ShowGroupDetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_groupLike', '', '2018-10-16 14:12:30'),
(187, '22', 41, '{\"title\":\"Connect\",\"body\":\"raj commented on your post.\",\"type\":\"mabwe_groupComment\",\"userId\":\"22\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/extra\\/company.png\",\"notify_id\":\"132\",\"click_action\":\"ShowGroupDetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_groupComment', '', '2018-10-16 14:13:08'),
(188, '24', 22, '{\"title\":\"Connect\",\"body\":\"Prachi commented on your post.\",\"type\":\"mabwe_comment\",\"userId\":\"24\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/uploads\\/profile\\/5521ac3d0b6a6ba9c49d41870217c457.jpg\",\"notify_id\":\"532\",\"click_action\":\"DetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_comment', '', '2018-10-17 05:13:14'),
(189, '24', 22, '{\"title\":\"Connect\",\"body\":\"Prachi liked on your post.\",\"type\":\"mabwe_Like\",\"userId\":\"24\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/uploads\\/profile\\/5521ac3d0b6a6ba9c49d41870217c457.jpg\",\"notify_id\":\"532\",\"click_action\":\"DetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_Like', '', '2018-10-17 05:33:25'),
(190, '25', 22, '{\"title\":\"Connect\",\"body\":\"ravi commented on your post.\",\"type\":\"mabwe_comment\",\"userId\":\"25\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/uploads\\/profile\\/5521ac3d0b6a6ba9c49d41870217c457.jpg\",\"notify_id\":\"532\",\"click_action\":\"DetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_comment', '', '2018-10-17 06:09:12'),
(191, '25', 41, '{\"title\":\"Connect\",\"body\":\"ravi liked on your post.\",\"type\":\"mabwe_groupLike\",\"userId\":\"25\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/extra\\/company.png\",\"notify_id\":\"132\",\"click_action\":\"ShowGroupDetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_groupLike', '', '2018-10-17 06:11:10'),
(192, '25', 22, '{\"title\":\"Connect\",\"body\":\"Rakesh Patel liked on your post.\",\"type\":\"mabwe_Like\",\"userId\":\"25\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/uploads\\/profile\\/5521ac3d0b6a6ba9c49d41870217c457.jpg\",\"notify_id\":\"532\",\"click_action\":\"DetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_Like', '', '2018-10-17 07:08:57'),
(193, '22', 46, '{\"title\":\"Connect\",\"body\":\"raj commented on your post.\",\"type\":\"mabwe_comment\",\"userId\":\"22\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/uploads\\/profile\\/0cfc8a8ae64cd3b8ee1a58747479f5cb.jpg\",\"notify_id\":\"557\",\"click_action\":\"DetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_comment', '', '2018-10-17 11:33:03'),
(194, '22', 41, '{\"title\":\"Connect\",\"body\":\"raj commented on your post.\",\"type\":\"mabwe_groupComment\",\"userId\":\"22\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/extra\\/company.png\",\"notify_id\":\"132\",\"click_action\":\"ShowGroupDetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_groupComment', '', '2018-10-17 12:19:59'),
(195, '22', 46, '{\"title\":\"Connect\",\"body\":\"raj commented on your post.\",\"type\":\"mabwe_comment\",\"userId\":\"22\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/uploads\\/profile\\/0cfc8a8ae64cd3b8ee1a58747479f5cb.jpg\",\"notify_id\":\"557\",\"click_action\":\"DetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_comment', '', '2018-10-17 13:06:47'),
(196, '22', 46, '{\"title\":\"Connect\",\"body\":\"raj commented on your post.\",\"type\":\"mabwe_comment\",\"userId\":\"22\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/uploads\\/profile\\/0cfc8a8ae64cd3b8ee1a58747479f5cb.jpg\",\"notify_id\":\"557\",\"click_action\":\"DetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_comment', '', '2018-10-17 13:07:14'),
(197, '32', 46, '{\"title\":\"Career\",\"body\":\"Lloyd Marimo liked on your post.\",\"type\":\"mabwe_Like\",\"userId\":\"32\",\"profileImage\":\"http:\\/\\/dev.mindiii.com\\/mabwe\\/uploads\\/profile\\/0cfc8a8ae64cd3b8ee1a58747479f5cb.jpg\",\"notify_id\":\"567\",\"click_action\":\"DetailsActivity\",\"sound\":\"default\"}', 0, '1', 'mabwe_Like', '', '2018-10-18 03:48:49');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `optionId` int(11) NOT NULL,
  `option_name` varchar(50) NOT NULL,
  `option_value` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`optionId`, `option_name`, `option_value`, `created_on`) VALUES
(6, 'term_and_condition', 'HR_Policies10.pdf', '2018-09-18 12:52:55'),
(5, 'policy', 'sample15.pdf', '2018-09-18 12:51:45');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `postId` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `latitude` varchar(200) NOT NULL,
  `longitude` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `description` text NOT NULL,
  `video` varchar(255) NOT NULL,
  `video_thumb` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postId`, `user_id`, `category_id`, `title`, `latitude`, `longitude`, `country`, `state`, `city`, `address`, `description`, `video`, `video_thumb`, `status`, `crd`, `upd`) VALUES
(582, 25, 1, 'test video', '23.2599333', '77.412615', 'India', 'Madhya Pradesh', 'Bhopal', 'Bhopal, Madhya Pradesh, India', 'zhj', 'mPAeI0kUoxHqWTS1.mp4', '511a073ccceb682f60f54d474dc289bc.png', 1, '2018-10-18 09:22:47', '2018-10-18 09:22:47'),
(583, 25, 1, 'new video', '-0.789275', '113.92132699999999', 'Indonesia', '', 'Ambulu', 'Indonesia', 'vdjs', 'g3nQL26WwByYxJ1m.mp4', '1fdee7c617da97ec767ce9ac84f236ce.png', 1, '2018-10-18 09:23:49', '2018-10-18 09:23:49'),
(584, 25, 1, 'gzzy', '23.179301300000002', '75.7849097', 'India', 'Madhya Pradesh', 'Ujjain', 'Ujjain, Madhya Pradesh, India', 'bxhdj', 'kRj0dnlA1LOS6UCx.mp4', '4766df3b86fdff5a1d6f3d654523e8ca.png', 1, '2018-10-18 09:25:18', '2018-10-18 09:25:18'),
(585, 25, 1, 'img', '40.2671941', '-86.1349019', 'United States', 'Indiana', 'Tipton', 'Indiana, USA', 'dhj', '', '', 1, '2018-10-18 10:14:50', '2018-10-18 10:14:50');

-- --------------------------------------------------------

--
-- Table structure for table `post_attachments`
--

CREATE TABLE `post_attachments` (
  `postImagesId` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `attachmentName` varchar(250) NOT NULL,
  `attachmentType` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_attachments`
--

INSERT INTO `post_attachments` (`postImagesId`, `post_id`, `attachmentName`, `attachmentType`, `status`, `crd`, `upd`) VALUES
(330, 521, 'wZT1iEMOIo75NYs0.mp4', 'video', 1, '2018-10-02 10:54:09', '2018-10-02 10:54:09'),
(331, 524, 'tHXFzSTCjxkq0cIn.mp4', 'video', 1, '2018-10-08 05:48:49', '2018-10-08 05:48:49'),
(332, 525, 'KrI25aRv1uiVXAFt.mp4', 'video', 1, '2018-10-08 05:49:06', '2018-10-08 05:49:06'),
(333, 530, '9XHjQIrPZV06Fcvg.mp4', 'video', 1, '2018-10-08 06:18:01', '2018-10-08 06:18:01'),
(334, 197, 'gLEAN4rc19TZDteM.mp4', 'video', 1, '2018-10-08 07:08:54', '2018-10-08 07:08:54'),
(335, 198, 'YPpt5ZMIsxzUqRTb.mp4', 'video', 1, '2018-10-08 07:14:42', '2018-10-08 07:14:42'),
(336, 199, 'c3edc742989cfbe8430071963a9ca1f3.jpg', 'image', 1, '2018-10-08 07:29:28', '2018-10-08 07:29:28'),
(337, 199, '13cf840298d2104d794be541ca8022c0.jpg', 'image', 1, '2018-10-08 07:29:28', '2018-10-08 07:29:28'),
(338, 199, '10101cdebeb70f05c80d5bc692cc3b0f.jpg', 'image', 1, '2018-10-08 07:29:28', '2018-10-08 07:29:28'),
(339, 200, 'KXwNqEkVFD7sP3Ma.mp4', 'video', 1, '2018-10-08 07:32:37', '2018-10-08 07:32:37'),
(340, 201, '9ti53JdOunoC2QTU.mp4', 'video', 1, '2018-10-08 07:41:04', '2018-10-08 07:41:04'),
(341, 202, 'f9d707a626d0ef75a5315464c8c7d92d.png', 'image', 1, '2018-10-08 10:07:41', '2018-10-08 10:07:41'),
(342, 203, 'eufTM8Ny7V4lZ0Go.mp4', 'video', 1, '2018-10-08 10:12:30', '2018-10-08 10:12:30'),
(343, 204, 'fcac501194c3de41bbdc209477936af5.jpg', 'image', 1, '2018-10-08 10:12:48', '2018-10-08 10:12:48'),
(344, 204, '762bd4b24c2b9ca9f751935f101f5e30.jpg', 'image', 1, '2018-10-08 10:12:48', '2018-10-08 10:12:48'),
(345, 204, 'c3d5012ff6086cdee6047cf5042704d1.jpg', 'image', 1, '2018-10-08 10:12:48', '2018-10-08 10:12:48'),
(346, 205, 'SxaZ3ks6Um0F8JXC.mp4', 'video', 1, '2018-10-08 10:13:09', '2018-10-08 10:13:09'),
(347, 206, '29bf50fef68f8e799324e60d05a7d5f1.png', 'image', 1, '2018-10-08 10:22:07', '2018-10-08 10:22:07'),
(348, 207, 'ea7f53c7850b4edb1bce82deb98e9055.png', 'image', 1, '2018-10-08 12:46:53', '2018-10-08 12:46:53'),
(349, 208, '02ca5b17ce7003ec29d0787b25596041.png', 'image', 1, '2018-10-08 12:48:23', '2018-10-08 12:48:23'),
(350, 209, '5bb7a2b6eeb4e3242dcc827bfb79ec5c.png', 'image', 1, '2018-10-08 13:09:34', '2018-10-08 13:09:34'),
(351, 210, '6627039e1ccca32a007a760c8ef35100.png', 'image', 1, '2018-10-08 13:11:37', '2018-10-08 13:11:37'),
(352, 211, 'cc1d1904df9d3233cd7bd95b6d0f9614.png', 'image', 1, '2018-10-08 13:20:43', '2018-10-08 13:20:43'),
(353, 212, '9b8e7146b237be8f7ed37deaabc78880.png', 'image', 1, '2018-10-08 13:26:04', '2018-10-08 13:26:04'),
(354, 213, '4f3f845f93056c52058df7408df8cc6a.png', 'image', 1, '2018-10-08 13:41:07', '2018-10-08 13:41:07'),
(355, 214, '5bb4a691d6497e6b8353a58e73368b7e.png', 'image', 1, '2018-10-08 13:55:08', '2018-10-08 13:55:08'),
(356, 215, '13e485df7af667dac80087d39f1d8f60.png', 'image', 1, '2018-10-08 13:57:38', '2018-10-08 13:57:38'),
(357, 215, '40b60b67c246f83b539840d0970175cb.png', 'image', 1, '2018-10-08 13:57:38', '2018-10-08 13:57:38'),
(358, 216, 'd0c62c61c262f910f7cbfcb90f81292e.png', 'image', 1, '2018-10-08 14:13:01', '2018-10-08 14:13:01'),
(359, 219, 'H7eBdOqPazgLTSxR.mp4', 'video', 1, '2018-10-09 05:22:51', '2018-10-09 05:22:51'),
(360, 220, '8680627dc6d1fe615fc65b761522711d.jpg', 'image', 1, '2018-10-09 05:49:17', '2018-10-09 05:49:17'),
(361, 220, '613f22e0bb90fbc019b36ea6937028fd.jpg', 'image', 1, '2018-10-09 05:49:17', '2018-10-09 05:49:17'),
(362, 220, 'ff103e76cb603c3170460ae4195a8398.jpg', 'image', 1, '2018-10-09 05:49:17', '2018-10-09 05:49:17'),
(363, 222, 'hpeM4bGaL1cWoz8Q.mp4', 'video', 1, '2018-10-09 05:50:05', '2018-10-09 05:50:05'),
(364, 228, '61912faca93cf5b8021b51d063c72792.jpg', 'image', 1, '2018-10-09 05:51:26', '2018-10-09 05:51:26'),
(365, 228, '2626d28f54888eaaeff70f580fd3b112.jpg', 'image', 1, '2018-10-09 05:51:26', '2018-10-09 05:51:26'),
(366, 228, '58c3fa78f17cac36ad47fb8b9518063c.jpg', 'image', 1, '2018-10-09 05:51:26', '2018-10-09 05:51:26'),
(367, 229, '4y0kcVe8vXjbLaJS.mp4', 'video', 1, '2018-10-09 05:54:31', '2018-10-09 05:54:31'),
(368, 230, 'eddb2359ac752e7eb6f568a6291a0a76.jpg', 'image', 1, '2018-10-09 05:56:43', '2018-10-09 05:56:43'),
(369, 230, '6353cdb90ca4cc47147ab8f5c78cf0bb.jpg', 'image', 1, '2018-10-09 05:56:43', '2018-10-09 05:56:43'),
(370, 230, 'edabf7224ed4a44e4ac0f9601c6593a7.jpg', 'image', 1, '2018-10-09 05:56:43', '2018-10-09 05:56:43'),
(371, 232, 'd9e4d41147d37053f1fde56f4f0d9a1b.jpg', 'image', 1, '2018-10-09 05:58:22', '2018-10-09 05:58:22'),
(372, 232, '719b86be1953bbb3b979ac4da1de8352.jpg', 'image', 1, '2018-10-09 05:58:22', '2018-10-09 05:58:22'),
(373, 232, 'c1bfaa15a000e9143e84eb663ee0f1d3.jpg', 'image', 1, '2018-10-09 05:58:22', '2018-10-09 05:58:22'),
(374, 235, '1598af872dabc27f60d54b895f113b16.jpg', 'image', 1, '2018-10-09 06:05:31', '2018-10-09 06:05:31'),
(375, 235, 'f1e6a7cbf520aba5bb9a773db728c574.jpg', 'image', 1, '2018-10-09 06:05:31', '2018-10-09 06:05:31'),
(376, 235, '3cf46aaa6b31cbdc3b6786ffdd4261ab.jpg', 'image', 1, '2018-10-09 06:05:31', '2018-10-09 06:05:31'),
(377, 236, 'c869f8b827a7f596fe96a3e1ea692db7.jpg', 'image', 1, '2018-10-09 06:05:50', '2018-10-09 06:05:50'),
(378, 237, '8d12c27153289a396774445e349d397a.jpg', 'image', 1, '2018-10-09 06:06:42', '2018-10-09 06:06:42'),
(379, 238, 'JdmLHDth62Ru0TsC.mp4', 'video', 1, '2018-10-09 06:07:00', '2018-10-09 06:07:00'),
(380, 239, 'oyXZerT725ncpiwW.mp4', 'video', 1, '2018-10-09 06:07:54', '2018-10-09 06:07:54'),
(381, 242, 'a19ebaed552488e381c028b155e70338.jpg', 'image', 1, '2018-10-09 06:09:51', '2018-10-09 06:09:51'),
(382, 242, 'c8a5072fb705ff64aeb0d10cf326d204.jpg', 'image', 1, '2018-10-09 06:09:51', '2018-10-09 06:09:51'),
(383, 242, '42de2ac1bb665a9d00359a619559f866.jpg', 'image', 1, '2018-10-09 06:09:51', '2018-10-09 06:09:51'),
(384, 243, '874bae237536ae8b15dc8400dd03ddcd.jpg', 'image', 1, '2018-10-09 06:11:04', '2018-10-09 06:11:04'),
(385, 243, 'a4732d4b5b629520a8dac3d383b961ba.jpg', 'image', 1, '2018-10-09 06:11:04', '2018-10-09 06:11:04'),
(386, 243, 'a36dde57080cc697b19d5d09df3461ab.jpg', 'image', 1, '2018-10-09 06:11:04', '2018-10-09 06:11:04'),
(387, 244, 'kUWTqcGiPV9teExJ.mp4', 'video', 1, '2018-10-09 06:14:24', '2018-10-09 06:14:24'),
(388, 245, 'NQM7vx83FoIUq2LH.mp4', 'video', 1, '2018-10-09 06:14:38', '2018-10-09 06:14:38'),
(389, 246, 'd68d1383023c539da4f5a5f889d6ecf5.jpg', 'image', 1, '2018-10-09 06:22:11', '2018-10-09 06:22:11'),
(390, 246, '157246a2d5b58c0f4323eb55332b244e.jpg', 'image', 1, '2018-10-09 06:22:11', '2018-10-09 06:22:11'),
(391, 246, '3569b5db0905b5b67f5b1a7dccca90ec.jpg', 'image', 1, '2018-10-09 06:22:11', '2018-10-09 06:22:11'),
(392, 249, '6d5363dcb3f3ad76e3f10ab428b2fca2.jpg', 'image', 1, '2018-10-09 06:28:35', '2018-10-09 06:28:35'),
(393, 249, '3a9c9ff9a39757c82909b0f43d6091b6.jpg', 'image', 1, '2018-10-09 06:28:35', '2018-10-09 06:28:35'),
(394, 249, '13706ea06a269c0e6a1a8ad77f91e011.jpg', 'image', 1, '2018-10-09 06:28:35', '2018-10-09 06:28:35'),
(395, 250, '7405d259ae7c7f60a596d642d8b1b509.jpg', 'image', 1, '2018-10-09 06:30:03', '2018-10-09 06:30:03'),
(396, 250, '862b326fd5620104d74da155c488def9.jpg', 'image', 1, '2018-10-09 06:30:03', '2018-10-09 06:30:03'),
(397, 250, '63f0d7fbd1e900fd01be5cb6fd26b2d9.jpg', 'image', 1, '2018-10-09 06:30:03', '2018-10-09 06:30:03'),
(398, 251, '830acf711a0d708ffd07e9e4ba92876d.jpg', 'image', 1, '2018-10-09 06:32:36', '2018-10-09 06:32:36'),
(399, 251, '2dea84d1bbeb9e914da042a2c746ca8d.jpg', 'image', 1, '2018-10-09 06:32:36', '2018-10-09 06:32:36'),
(400, 251, '380c5322289fb4efd5a575e12f513d42.jpg', 'image', 1, '2018-10-09 06:32:36', '2018-10-09 06:32:36'),
(401, 252, '82e5acbe9a8e3550de2f2019cf10b8f3.jpg', 'image', 1, '2018-10-09 06:33:26', '2018-10-09 06:33:26'),
(402, 252, '1ed09c5c8146a9cdfe360111d2c298c2.jpg', 'image', 1, '2018-10-09 06:33:26', '2018-10-09 06:33:26'),
(403, 252, '5c78c2996dc4365c243114b233e6d247.jpg', 'image', 1, '2018-10-09 06:33:26', '2018-10-09 06:33:26'),
(404, 255, 'PYBLvVMdH02K6EsG.mp4', 'video', 1, '2018-10-09 06:58:44', '2018-10-09 06:58:44'),
(405, 260, '29f7ea5ec54ccdaab9afc110cb625be3.png', 'image', 1, '2018-10-09 07:18:02', '2018-10-09 07:18:02'),
(406, 264, 'd83f40778c2e7aa9149ad113cf8e1cfa.png', 'image', 1, '2018-10-09 07:24:18', '2018-10-09 07:24:18'),
(407, 265, '83767119a19ba09e6295fe15ca434a3d.png', 'image', 1, '2018-10-09 08:22:13', '2018-10-09 08:22:13'),
(408, 266, '8070548d1b8aa0ee5b4f34a79bdee65d.png', 'image', 1, '2018-10-09 08:42:58', '2018-10-09 08:42:58'),
(409, 267, '097fff6d3416845f7f77f3eaf4ed0a60.jpg', 'image', 1, '2018-10-09 09:45:07', '2018-10-09 09:45:07'),
(410, 267, '4d0012987255653c9f795f4f11867696.jpg', 'image', 1, '2018-10-09 09:45:07', '2018-10-09 09:45:07'),
(411, 267, '3925c039e3d1b2de12951b351fda64a4.jpg', 'image', 1, '2018-10-09 09:45:07', '2018-10-09 09:45:07'),
(412, 268, 'MUvGpZd5w9AQlKE0.mp4', 'video', 1, '2018-10-09 11:00:33', '2018-10-09 11:00:33'),
(413, 269, 'e8e2697be9ffaac0c9e314e59edbc26e.png', 'image', 1, '2018-10-09 11:16:57', '2018-10-09 11:16:57'),
(414, 270, '71935bed38134603ace1282bba8e5bec.png', 'image', 1, '2018-10-09 11:19:46', '2018-10-09 11:19:46'),
(415, 271, '1c610ad61397eb3d322250a27b69e409.png', 'image', 1, '2018-10-09 11:22:23', '2018-10-09 11:22:23'),
(416, 277, 'qz0nsb3QXkoP6O4a.mp4', 'video', 1, '2018-10-09 12:18:51', '2018-10-09 12:18:51'),
(417, 278, 'qUxaKYpCbBcf8jRH.mp4', 'video', 1, '2018-10-09 12:19:38', '2018-10-09 12:19:38'),
(418, 279, 'c1VJnTZhgawjeNvx.mp4', 'video', 1, '2018-10-09 12:21:13', '2018-10-09 12:21:13'),
(419, 281, 'xEM3kW9NVS2DFn5v.mp4', 'video', 1, '2018-10-09 12:24:48', '2018-10-09 12:24:48'),
(420, 282, 'ccce3ed489897429da13122ad0aead05.png', 'image', 1, '2018-10-09 13:11:09', '2018-10-09 13:11:09'),
(421, 283, '2a30d72a0f6e65b674073dd15d7995e9.png', 'image', 1, '2018-10-09 13:13:55', '2018-10-09 13:13:55'),
(422, 286, 'ce210c0498a17a748d78f64d95d5fa0a.png', 'image', 1, '2018-10-09 14:06:09', '2018-10-09 14:06:09'),
(423, 293, 'a2617c26f6e1513d71f74928fb188938.png', 'image', 1, '2018-10-10 08:38:28', '2018-10-10 08:38:28'),
(424, 294, '92e1a6a5d56ca5468647cf64c14649a7.png', 'image', 1, '2018-10-10 08:39:46', '2018-10-10 08:39:46'),
(425, 309, 'U7oLAIKG4eNTRbMY.mp4', 'video', 1, '2018-10-10 09:19:13', '2018-10-10 09:19:13'),
(426, 310, '5b64ff00754c9382eb10a75b9c8685cf.jpg', 'image', 1, '2018-10-10 09:26:05', '2018-10-10 09:26:05'),
(427, 310, 'a220ccdd11c11dc2582c2af43881e675.jpg', 'image', 1, '2018-10-10 09:26:05', '2018-10-10 09:26:05'),
(428, 310, 'c57bbebe6e7be76c5e63cf4cc8e2417a.jpg', 'image', 1, '2018-10-10 09:26:05', '2018-10-10 09:26:05'),
(429, 311, '8621c473d703b592db61e87a990ea4db.jpg', 'image', 1, '2018-10-10 09:28:26', '2018-10-10 09:28:26'),
(430, 311, '71d6af12539afbdf45003ed44fce8f3f.jpg', 'image', 1, '2018-10-10 09:28:26', '2018-10-10 09:28:26'),
(431, 311, '260f1588877bd044b70aca81292a45e1.jpg', 'image', 1, '2018-10-10 09:28:26', '2018-10-10 09:28:26'),
(432, 312, 'af7244021e7736efea87b2ad642772c0.jpg', 'image', 1, '2018-10-10 09:28:53', '2018-10-10 09:28:53'),
(433, 313, 'ab61b7f8c125b287f7fc236d6cc8ac24.jpg', '', 1, '2018-10-10 11:21:04', '2018-10-10 11:21:04'),
(434, 313, '97e4e6f478923697bbf465f3255103f8.jpg', '', 1, '2018-10-10 11:21:04', '2018-10-10 11:21:04'),
(435, 313, '6003747359cd74ef5f03d9e26438dbec.jpg', '', 1, '2018-10-10 11:21:04', '2018-10-10 11:21:04'),
(436, 314, '59057989ceacaf49f53d37f7b06d5d69.png', '', 1, '2018-10-10 11:40:56', '2018-10-10 11:40:56'),
(437, 317, '8ed31ec0a3e5baad931a529a9491a2e6.jpg', '', 1, '2018-10-11 05:32:41', '2018-10-11 05:32:41'),
(438, 317, '5cc6791027e823f996cc57171b7890f7.jpg', '', 1, '2018-10-11 05:32:41', '2018-10-11 05:32:41'),
(439, 317, '014b3139b2bb414069eecd42c189d953.jpg', '', 1, '2018-10-11 05:32:41', '2018-10-11 05:32:41'),
(440, 318, '2735ed5219c0f7cb8a42cdfc42f0be7b.png', '', 1, '2018-10-11 06:03:00', '2018-10-11 06:03:00'),
(441, 318, 'b3c039d72b257a516ca9d3ec4098442f.png', '', 1, '2018-10-11 06:03:00', '2018-10-11 06:03:00'),
(442, 321, '2d208041f1fffac726d6492d6b4006d0.jpg', '', 1, '2018-10-11 09:58:50', '2018-10-11 09:58:50'),
(443, 321, '70dfedd076127eb2f08f83470ea68756.jpg', '', 1, '2018-10-11 09:58:50', '2018-10-11 09:58:50'),
(444, 321, '5903656f7fcf4dbda81fbd01bcc6ff42.jpg', '', 1, '2018-10-11 09:58:50', '2018-10-11 09:58:50'),
(445, 327, 'f3989b8c5770101542d5c96e5f7d0710.png', '', 1, '2018-10-11 10:39:22', '2018-10-11 10:39:22'),
(446, 331, 'd9dcc98df9f978ab65a4220aba2211f1.png', '', 1, '2018-10-11 12:28:56', '2018-10-11 12:28:56'),
(447, 332, '254395e36468b55b042d494d4950d954.png', '', 1, '2018-10-11 12:29:41', '2018-10-11 12:29:41'),
(448, 333, '5dd410322ecf4591f43c976e4fa6349c.png', '', 1, '2018-10-11 12:29:46', '2018-10-11 12:29:46'),
(449, 334, '145f6b39cde2582219483a6f300a1f99.png', '', 1, '2018-10-11 12:35:38', '2018-10-11 12:35:38'),
(450, 336, 'bb46f080dbf1fcf2b4fca08f7a83a273.jpg', '', 1, '2018-10-11 12:42:25', '2018-10-11 12:42:25'),
(451, 336, 'b8dac3c3aada796e6cae01f976cbaff2.jpg', '', 1, '2018-10-11 12:42:25', '2018-10-11 12:42:25'),
(452, 336, '39932cde62dadc851888153440dd9e22.jpg', '', 1, '2018-10-11 12:42:25', '2018-10-11 12:42:25'),
(453, 337, '7405bca400a121a791124a4c418fca56.jpg', '', 1, '2018-10-11 12:45:06', '2018-10-11 12:45:06'),
(454, 337, '2efc84cad46c889058560d55513dfb02.jpg', '', 1, '2018-10-11 12:45:06', '2018-10-11 12:45:06'),
(455, 337, '3b352ade73c91443e348a60c33709388.jpg', '', 1, '2018-10-11 12:45:06', '2018-10-11 12:45:06'),
(456, 338, '3af0cd14050d0345a840fce550c7e138.jpg', '', 1, '2018-10-11 12:45:38', '2018-10-11 12:45:38'),
(457, 338, '1c1ee0f49a376f7e6d68b099dcfb52bb.jpg', '', 1, '2018-10-11 12:45:38', '2018-10-11 12:45:38'),
(458, 338, '8aabc06f6356aadf093e80d0aeca0eb2.jpg', '', 1, '2018-10-11 12:45:38', '2018-10-11 12:45:38'),
(459, 339, 'b1ada15abd4a55b30873147ac2c2a169.jpg', '', 1, '2018-10-11 12:47:47', '2018-10-11 12:47:47'),
(460, 339, 'ce27101b3af0b3a12cf0959bc2232dd7.jpg', '', 1, '2018-10-11 12:47:47', '2018-10-11 12:47:47'),
(461, 339, '2c6e5df8cfec57650f6f0c1c43c7ac07.jpg', '', 1, '2018-10-11 12:47:47', '2018-10-11 12:47:47'),
(462, 340, '1d8e7a115c7c8aae3cdcc93ebea63f59.jpg', '', 1, '2018-10-11 12:48:17', '2018-10-11 12:48:17'),
(463, 340, '8a6616ccb40d98cda2552a1c96fd9e10.jpg', '', 1, '2018-10-11 12:48:17', '2018-10-11 12:48:17'),
(464, 340, '137bf8a2bcf4fa6eda2ef534602125bd.jpg', '', 1, '2018-10-11 12:48:17', '2018-10-11 12:48:17'),
(465, 347, '0d31d67cb66f3084336e5f64de251e33.jpg', '', 1, '2018-10-11 14:33:18', '2018-10-11 14:33:18'),
(466, 347, '58d9d59deac2e65962225cb984e18463.jpg', '', 1, '2018-10-11 14:33:18', '2018-10-11 14:33:18'),
(467, 347, 'a80052d724dc67b87fb707e396435e39.jpg', '', 1, '2018-10-11 14:33:18', '2018-10-11 14:33:18'),
(468, 352, '308c7823fc335fd0b87932d173bc6bad.jpg', '', 1, '2018-10-11 14:36:41', '2018-10-11 14:36:41'),
(469, 352, '58a964a5da2da917eef933a9670383b7.jpg', '', 1, '2018-10-11 14:36:41', '2018-10-11 14:36:41'),
(470, 352, '970ad23503eaa22c788074a96770c139.jpg', '', 1, '2018-10-11 14:36:41', '2018-10-11 14:36:41'),
(471, 355, '2adb9e72252cc1c6266fcdd88eaec5c8.png', '', 1, '2018-10-12 05:35:32', '2018-10-12 05:35:32'),
(472, 360, '12a8a29b4c1063435bc3ea2f4d1bb85b.jpg', '', 1, '2018-10-12 05:41:54', '2018-10-12 05:41:54'),
(473, 362, '2af500a27e7e4ee9c2b9f4a9a144e09d.png', '', 1, '2018-10-12 05:48:04', '2018-10-12 05:48:04'),
(474, 362, '9d8beb9a7aaf82fbfc5074659c5bc9a6.png', '', 1, '2018-10-12 05:48:04', '2018-10-12 05:48:04'),
(475, 362, '3c3b550a5c6b08c1a3282f0d051ea6a4.png', '', 1, '2018-10-12 05:48:04', '2018-10-12 05:48:04'),
(476, 365, '8da7d4031fbeb3ae5a5f548d79f1a023.jpg', '', 1, '2018-10-12 06:52:04', '2018-10-12 06:52:04'),
(477, 366, '958b39e027a4a26b39914d314aeb7f3b.jpg', '', 1, '2018-10-12 06:52:26', '2018-10-12 06:52:26'),
(478, 366, '0272fdfa67aa8834e0f1f6d96776ae9c.jpg', '', 1, '2018-10-12 06:52:26', '2018-10-12 06:52:26'),
(479, 366, 'd211c52986a4575df1ff04e9eab596bc.jpg', '', 1, '2018-10-12 06:52:26', '2018-10-12 06:52:26'),
(480, 372, 'c2a38ca600ca3a498cd63e451c14dbcb.jpg', '', 1, '2018-10-12 07:04:01', '2018-10-12 07:04:01'),
(481, 372, '72719741b4380db4f69f412e6c29f009.jpg', '', 1, '2018-10-12 07:04:01', '2018-10-12 07:04:01'),
(482, 372, '20ef47256cf8a91d30b17bd5b13080ab.jpg', '', 1, '2018-10-12 07:04:01', '2018-10-12 07:04:01'),
(483, 375, '4c48f03c645148fe326204098915e689.png', '', 1, '2018-10-12 07:35:08', '2018-10-12 07:35:08'),
(484, 378, '21178b21e64eda768d4f29910b0f9d8a.png', '', 1, '2018-10-12 07:49:19', '2018-10-12 07:49:19'),
(485, 379, '6cce973f11874e84e989047ba6feb87b.png', '', 1, '2018-10-12 07:50:26', '2018-10-12 07:50:26'),
(486, 380, '392d42cf2a3a8cbced1c4b37a18e8950.png', '', 1, '2018-10-12 08:47:36', '2018-10-12 08:47:36'),
(487, 385, '410efa92e52d9e22cd991e8be0aadf7b.jpg', '', 1, '2018-10-12 10:11:05', '2018-10-12 10:11:05'),
(488, 389, '7bc768a9b0d1d81476ce5dcc2450e072.png', '', 1, '2018-10-12 10:37:56', '2018-10-12 10:37:56'),
(489, 397, '5ffb07fef7cd63275b4045ec46b3b680.png', '', 1, '2018-10-12 11:25:17', '2018-10-12 11:25:17'),
(490, 412, '58a02200e337e31162a62caf9c10cf94.png', '', 1, '2018-10-12 14:33:22', '2018-10-12 14:33:22'),
(491, 418, '68a3c913f23a8f7c1cfc38f05c13c650.png', '', 1, '2018-10-13 07:27:31', '2018-10-13 07:27:31'),
(492, 420, '3e021544590eb234492d91be60eb261e.jpg', '', 1, '2018-10-13 08:14:42', '2018-10-13 08:14:42'),
(493, 433, 'ea8857bff2bcdf4dd1123d28b7ed4494.png', '', 1, '2018-10-13 09:33:19', '2018-10-13 09:33:19'),
(494, 434, '13d8e315bcf3a91aacb6fa7e18c0c6e1.png', '', 1, '2018-10-13 09:34:09', '2018-10-13 09:34:09'),
(495, 435, '1c27e9540205eeec31c12ad82e971776.png', '', 1, '2018-10-13 09:34:50', '2018-10-13 09:34:50'),
(496, 436, '1eb4716bc86fc756000ab025b26ab3a2.png', '', 1, '2018-10-13 09:35:24', '2018-10-13 09:35:24'),
(497, 438, '323ce98856af0b3deec74eebb82cdb81.png', '', 1, '2018-10-13 09:36:28', '2018-10-13 09:36:28'),
(498, 438, 'f628070a56050d9fbefd6720bd65cc70.png', '', 1, '2018-10-13 09:36:28', '2018-10-13 09:36:28'),
(499, 438, '0f2519c27c2ca4004f3e348d257c0642.png', '', 1, '2018-10-13 09:36:28', '2018-10-13 09:36:28'),
(500, 442, '44f5511e736e85944b9c53d0081ebed1.png', '', 1, '2018-10-13 10:24:53', '2018-10-13 10:24:53'),
(501, 443, 'd20b0d1f8a022ff719f1f8ea7ab388c5.png', '', 1, '2018-10-13 10:52:52', '2018-10-13 10:52:52'),
(502, 443, 'd63a56a529ecb9a34c6d89566482c35e.png', '', 1, '2018-10-13 10:52:52', '2018-10-13 10:52:52'),
(503, 443, 'cfa3f6d1debb87fb0df9f188b88e2f26.png', '', 1, '2018-10-13 10:52:52', '2018-10-13 10:52:52'),
(504, 443, 'b2b14ad091ce7bf52440b43b5f29539a.png', '', 1, '2018-10-13 10:52:52', '2018-10-13 10:52:52'),
(505, 443, 'b5d715bad3909f3e50cc58513a4e7d7e.png', '', 1, '2018-10-13 10:52:52', '2018-10-13 10:52:52'),
(506, 447, 'be1810ef1379b2d9926faf9d43ecf8a5.png', '', 1, '2018-10-13 11:22:16', '2018-10-13 11:22:16'),
(507, 449, '47eaf46486349260722b269d619e2b95.png', '', 1, '2018-10-13 12:07:41', '2018-10-13 12:07:41'),
(508, 449, 'a3b7c9e586c7e163ed122d61e8be04b8.png', '', 1, '2018-10-13 12:07:41', '2018-10-13 12:07:41'),
(509, 449, 'a5a124f11f38cf4b0e50381406bd0fcc.png', '', 1, '2018-10-13 12:07:41', '2018-10-13 12:07:41'),
(510, 456, '250d08821851856a2316c8d3c667be29.png', '', 1, '2018-10-15 10:44:35', '2018-10-15 10:44:35'),
(511, 456, '60dd98df2a375f0a064490378a2696eb.png', '', 1, '2018-10-15 10:44:35', '2018-10-15 10:44:35'),
(512, 456, '0012593dcbda6c818995c64078affdd7.png', '', 1, '2018-10-15 10:44:35', '2018-10-15 10:44:35'),
(513, 456, 'e0adcfe4546f2c68b422bb8e4488f27b.png', '', 1, '2018-10-15 10:44:35', '2018-10-15 10:44:35'),
(514, 456, '4f53c7a0c72d3e65efa203b1731f2c4d.png', '', 1, '2018-10-15 10:44:35', '2018-10-15 10:44:35'),
(515, 461, 'e670a311a3ed5428bf0b05a8e252b0cf.png', '', 1, '2018-10-16 05:50:48', '2018-10-16 05:50:48'),
(516, 476, 'b6f679011e07ae1d4408bd8f15773250.jpg', '', 1, '2018-10-16 08:24:42', '2018-10-16 08:24:42'),
(517, 481, '18127dfbee930509f7934385257bde44.jpg', '', 1, '2018-10-16 08:39:35', '2018-10-16 08:39:35'),
(518, 510, 'c0623d0964a1280c9f3a2aa0e914e2ed.jpg', '', 1, '2018-10-16 11:21:34', '2018-10-16 11:21:34'),
(519, 515, '11dd5ccad0ebeb0fe5d9d6a724e5c47b.png', '', 1, '2018-10-16 12:17:52', '2018-10-16 12:17:52'),
(520, 515, '0418616d575d36779296cc1f09201b65.png', '', 1, '2018-10-16 12:17:52', '2018-10-16 12:17:52'),
(521, 515, 'c4865475f2b183333e45a24bce21a7ee.png', '', 1, '2018-10-16 12:17:52', '2018-10-16 12:17:52'),
(522, 521, '625a657551cb1b4cc2087b97f852b59b.png', '', 1, '2018-10-16 12:34:25', '2018-10-16 12:34:25'),
(523, 522, '11902f3e9af4336895451d7bfaaa1629.png', '', 1, '2018-10-16 12:39:39', '2018-10-16 12:39:39'),
(524, 524, '7d7cfba8706f83e4b1f3141f58dbd127.png', '', 1, '2018-10-16 12:43:18', '2018-10-16 12:43:18'),
(525, 526, '82db1580224f8a58033f693125cede7d.png', '', 1, '2018-10-16 12:46:29', '2018-10-16 12:46:29'),
(526, 527, '070ad52bda0b408200f607c30b022778.png', '', 1, '2018-10-16 12:48:50', '2018-10-16 12:48:50'),
(527, 527, '239b6156c2c27fd4204ffa44a038a437.png', '', 1, '2018-10-16 12:48:50', '2018-10-16 12:48:50'),
(528, 530, 'e6813f3503d7cf2db1b7cf9e8de380ab.png', '', 1, '2018-10-16 12:59:30', '2018-10-16 12:59:30'),
(529, 531, '04d88b8d4394e49002fdb09d3ed4576d.png', '', 1, '2018-10-16 13:01:29', '2018-10-16 13:01:29'),
(530, 533, '03b45febff72e65361c41b206ae27554.png', '', 1, '2018-10-16 13:07:13', '2018-10-16 13:07:13'),
(531, 535, 'c2df2cdf9a9c56ba255a0b71c4d66b0a.png', '', 1, '2018-10-16 13:15:26', '2018-10-16 13:15:26'),
(532, 536, 'b5d9ca74000b6a3204a1168e81dcf0b1.png', '', 1, '2018-10-16 13:16:53', '2018-10-16 13:16:53'),
(533, 537, '2b7d80cd0fe80ff40a1c860d4a522521.png', '', 1, '2018-10-16 13:18:13', '2018-10-16 13:18:13'),
(534, 537, 'f3d35b028ace87497929a0a696a6be97.png', '', 1, '2018-10-16 13:18:13', '2018-10-16 13:18:13'),
(535, 537, '06cc28e961a8a9d20f786387e6b36d19.png', '', 1, '2018-10-16 13:18:13', '2018-10-16 13:18:13'),
(536, 544, '855a5a33fc5ebfa64d24cad2aea5a2da.png', '', 1, '2018-10-17 05:03:47', '2018-10-17 05:03:47'),
(537, 544, '651a0c5c36c3c4f2214cb81b82f3246f.png', '', 1, '2018-10-17 05:03:47', '2018-10-17 05:03:47'),
(538, 545, 'd6eb02e4b7076997708eb894317ad1cd.png', '', 1, '2018-10-17 05:28:57', '2018-10-17 05:28:57'),
(539, 545, '93325d13600bec2d37a01334400657df.png', '', 1, '2018-10-17 05:28:57', '2018-10-17 05:28:57'),
(540, 546, '89c2697c0bc77594f394cea2ba3e65ab.png', '', 1, '2018-10-17 05:35:44', '2018-10-17 05:35:44'),
(541, 546, '709b99164842b5d7bfd8706b757e4085.png', '', 1, '2018-10-17 05:35:44', '2018-10-17 05:35:44'),
(542, 554, 'e2262d6e1dcdd4f384425254f34fbec1.png', '', 1, '2018-10-17 07:06:38', '2018-10-17 07:06:38'),
(543, 554, '803f86610ffdb68c095a64914471d88f.png', '', 1, '2018-10-17 07:06:38', '2018-10-17 07:06:38'),
(544, 554, '3d8b48e6c2bd964c9e0d5c44fb611d39.png', '', 1, '2018-10-17 07:06:38', '2018-10-17 07:06:38'),
(545, 554, '9ec32dadd0535748ea78d6f35321bf62.png', '', 1, '2018-10-17 07:06:38', '2018-10-17 07:06:38'),
(546, 571, '3cb7e4268dec17150d6ae6fe80f03cc1.png', '', 1, '2018-10-17 13:25:07', '2018-10-17 13:25:07'),
(547, 571, 'ac807d37c8ec7468999192c0d9fb1ddd.png', '', 1, '2018-10-17 13:25:07', '2018-10-17 13:25:07'),
(548, 585, 'f1fe45273738ae9d909d75c0746a5802.png', '', 1, '2018-10-18 10:14:53', '2018-10-18 10:14:53'),
(549, 585, 'cd4b07f96b0a806c9cdb7e55a2456936.png', '', 1, '2018-10-18 10:14:53', '2018-10-18 10:14:53'),
(550, 585, 'c33df9c618d70d5af40cd31bacf288ae.png', '', 1, '2018-10-18 10:14:53', '2018-10-18 10:14:53');

-- --------------------------------------------------------

--
-- Table structure for table `post_permissions`
--

CREATE TABLE `post_permissions` (
  `postPermissionId` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `authorisedToWork` tinyint(4) DEFAULT NULL COMMENT '0 for No, 1 For Yes',
  `willingToRelocate` tinyint(4) DEFAULT NULL COMMENT '0 for No, 1 For Yes',
  `whilingToship` tinyint(4) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL COMMENT 'if Whilling to ship is 1 the contact lwill here',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_permissions`
--

INSERT INTO `post_permissions` (`postPermissionId`, `user_id`, `post_id`, `authorisedToWork`, `willingToRelocate`, `whilingToship`, `email`, `contact`, `crd`, `upd`) VALUES
(582, 25, 582, 0, 0, 0, '', '', '2018-10-18 09:22:47', '2018-10-18 09:22:47'),
(583, 25, 583, 0, 0, 0, '', '', '2018-10-18 09:23:49', '2018-10-18 09:23:49'),
(584, 25, 584, 0, 0, 0, '', '', '2018-10-18 09:25:18', '2018-10-18 09:25:18'),
(585, 25, 585, 0, 0, 0, '', '', '2018-10-18 10:14:50', '2018-10-18 10:14:50');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tagId` bigint(20) NOT NULL,
  `tagName` varchar(100) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tagId`, `tagName`, `crd`, `upd`) VALUES
(86, 'ravi', '2018-09-29 06:31:55', '2018-09-29 06:31:55'),
(88, 'indore', '2018-09-29 09:37:35', '2018-09-29 09:37:35'),
(89, 'bhopal', '2018-09-29 09:38:39', '2018-09-29 09:38:39'),
(96, 'test', '2018-09-29 11:03:32', '2018-09-29 11:03:32'),
(97, 'rrrrr', '2018-09-29 11:08:55', '2018-09-29 11:08:55'),
(98, 'ththt', '2018-09-29 11:10:14', '2018-09-29 11:10:14'),
(107, 'mindiii', '2018-10-04 05:21:56', '2018-10-04 05:21:56'),
(108, 'raj', '2018-10-09 09:02:28', '2018-10-09 09:02:28'),
(109, 'dgffgh', '2018-10-09 09:02:28', '2018-10-09 09:02:28'),
(110, 'dffgfggghf', '2018-10-09 09:02:28', '2018-10-09 09:02:28'),
(111, 'hgg1hjgj', '2018-10-10 08:56:17', '2018-10-10 08:56:17'),
(112, '2545fdf', '2018-10-12 11:00:01', '2018-10-12 11:00:01'),
(113, 'ravin', '2018-10-12 11:25:16', '2018-10-12 11:25:16'),
(114, 'vaibhav', '2018-10-15 14:02:53', '2018-10-15 14:02:53'),
(115, 'anurag', '2018-10-15 14:02:53', '2018-10-15 14:02:53'),
(116, 'priya', '2018-10-15 14:02:53', '2018-10-15 14:02:53'),
(117, 'hfh', '2018-10-16 05:50:46', '2018-10-16 05:50:46'),
(118, '', '2018-10-17 06:23:04', '2018-10-17 06:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `tags_mapping`
--

CREATE TABLE `tags_mapping` (
  `tapMapId` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tags_mapping`
--

INSERT INTO `tags_mapping` (`tapMapId`, `post_id`, `tag_id`, `crd`, `upd`) VALUES
(479, 582, 89, '2018-10-18 09:22:47', '2018-10-18 09:22:47'),
(480, 583, 86, '2018-10-18 09:23:49', '2018-10-18 09:23:49'),
(481, 583, 89, '2018-10-18 09:23:49', '2018-10-18 09:23:49'),
(482, 584, 96, '2018-10-18 09:25:18', '2018-10-18 09:25:18'),
(483, 584, 89, '2018-10-18 09:25:18', '2018-10-18 09:25:18'),
(484, 585, 96, '2018-10-18 10:14:50', '2018-10-18 10:14:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` bigint(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 for active, 2 for deactive',
  `deviceType` tinyint(10) NOT NULL DEFAULT '0' COMMENT '1 for IOS, 2 for Android, 0 for WEB',
  `deviceToken` varchar(255) NOT NULL,
  `socialId` varchar(255) NOT NULL,
  `socialType` varchar(20) NOT NULL,
  `authToken` varchar(50) NOT NULL,
  `profileImage` varchar(250) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `profession` varchar(100) DEFAULT NULL,
  `forgetPass` varchar(255) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `fullname`, `email`, `password`, `status`, `deviceType`, `deviceToken`, `socialId`, `socialType`, `authToken`, `profileImage`, `country`, `profession`, `forgetPass`, `crd`, `upd`) VALUES
(22, 'raj', 'raj@gmail.com', '$2y$10$4ndx5F1adndFglLdFHCeYuOa2AJIY1VkKAcxRbemztWwvKFbp4aCK', 1, 0, 'fqGiW90Efig:APA91bGNjtHnpgbJEeOZgnTVdFpAA8K5DzIMIdv93bQBFNwvcptV7huL1lzsUaiDuicaZUa5-YMxG2j3qdNhqtNQFHj81Kl4Z6bd7HuNkj0T33GkMbKpeszJYmA1Ab59LCUt_D4qatN4', '', '', 'dcf270b47fa70171f8b2', '5521ac3d0b6a6ba9c49d41870217c457.jpg', 'Japan', 'engineer computer', '', '2018-10-17 09:35:25', '2018-10-17 09:35:25'),
(23, 'neha', 'neha@gmail.com', '$2y$10$gI19ID2qd5p3O6kIQkvPf.3JRlSbEo3zZf.Z3dKhJu/ZumOc1tpPe', 1, 0, '', '', '', '', '4086d6cae5fabf13663362a88a393401.jpg', 'Azerbaijan', 'engineering', '', '2018-09-20 07:59:01', '2018-09-20 07:59:01'),
(24, 'Prachi', 'prachi@gmail.com', '$2y$10$WQMISmLFmJBScBj9ooXXNOq8fLJmGEKwda6IGBxlUZ1hFrMGO4tNi', 1, 0, '', '', '', '', '', NULL, NULL, '', '2018-10-17 06:04:01', '2018-10-17 06:04:01'),
(25, 'Rakesh Patel', 'ravi@gmail.com', '$2y$10$cseMxS7Hue8S6CR7UXMANuAQhi3.cLww9U6R6Z.O7EJX8D2nGgrRe', 1, 0, 'cQ-awnJglFI:APA91bExruYg5TWV0GGMiCJlcINlYWD87YHFT95vH6zJEUXrNlYLzcvVWAGOP51WZ60_k7xhxV-EilcvLxUH3W8sdkHSk0wFNA3MLJ03j91WOT-nJM4y5s5SyGL-m8QjFfLL5w_q4-xy', '', '', 'd40a14bcbeed68e5411f', '63e6d3a7821e2da0896463368a9fddd7.jpg', 'India', 'Job', '', '2018-10-18 10:13:11', '2018-10-18 10:13:11'),
(26, 'anil', 'anil@gmail.com', '$2y$10$Ia7Ff591HE37q98HgMV1XORknlK2rP6oJlIz4hJt.uiDcGhBHCTsi', 1, 0, 'ebjFGPo4bNU:APA91bGeiT7NSMhONb1Vnyujz1jKtO0BXH3WaGUYSFS1QNWEepevbn4nUdqxwC5HljbD6OuCVfax404NBEjRvSUDkcWRgHzHQB1cNPytAR8BLYOZG_RJvKINiU-KS-EvXbPk-CCI8evD', '', '', 'd20d1c8e406a0e854ad6', 'a7c2eebf880b2d7bf2016d914e14b085.jpg', 'India', 'business', '', '2018-10-11 07:08:54', '2018-10-11 07:08:54'),
(27, 'Tatenda Kunaka', 'tkunaka@middlebury.edu', '$2y$10$f.mYxylRoBFTMI59rt8okumRRqVw0vLHOASkIJhfjFOZUY1J33amy', 1, 0, '', '', '', '', '834af61b3da2ddbde1cff61a3d72d335.jpg', 'Zimbabwe', 'IT', '', '2018-09-24 05:00:16', '2018-09-24 05:00:16'),
(28, 'pravi', 'pravi@gmail.com', '$2y$10$CPfpUENrlNzMNTVsQPCVsOSfwUlTAUJ6i6q8DmCKOwGh7KcHGXRqe', 1, 0, 'eh2LCetMues:APA91bH2Ht6O6MkgSgr2j5O-BhASpatnnghtV19KX23Nc1dKUo8CoPGcibfv28jW8Dp5LCim3Jit1lxO0fm16EOp1W_V5Oss4CVQ6n1brvzZZuw4v9hh37QQDb4_twveZrKKzTkekj5B', '', '', 'fc8a1ea9d05b4a0f8c22', '441fdb470c51598980fb67717cf33179.jpg', 'Aruba', 'engineer', '', '2018-10-22 06:04:46', '2018-10-22 06:04:46'),
(29, 'Tatenda Kunaka', 'kunakat@yahoo.com', '$2y$10$R5pq5ugEAAN8AHvZyoKpNePcOAqtUFGOKoA6lFIczHpYRfJQmejES', 1, 0, 'c8sWL9ebZ3w:APA91bFe-PjQ0aIAinVVkvPD5lmME-Q2bA3NyckmW1KMNoY-P3KVOGpsz94f9c_w36L69VJa8j9UEiNrAEkeNYN7P1sUGzgtjwkCtD_XLNfGIfoeySgR_O4TvZzg6xwCJGgf1mBYG5HH', '', '', '2d955ec369b8eb752fb8', '', NULL, NULL, '', '2018-09-24 05:00:58', '2018-09-24 05:00:58'),
(30, 'Arvind', 'arvind.mindiii@gmail.com', '$2y$10$GjxhoPDfMHggoC3O3nBqrO1TMlWMKqF5daUh/HzOP/zVip.IsC8s6', 1, 0, 'cjt54CcA0gk:APA91bHtrAd_k04KpYSQOM6URdIOpicEN_qJMjzApCg8kACo5D5jEukA8V57701exdGrmGJFaq6ZC_hwFfVdS7vQzVDPItTiaaX85Wti0brf_mYfXIUD1K9XBuyQ5hSpYB5rMaMqwMw-', '', '', '72edc8e0ed5d44acc04c', '6a94e0d6bbf5baec98e8a8cf2b0d446e.jpg', 'Belgium', 'engineer', '', '2018-09-28 09:21:06', '2018-09-28 09:21:06'),
(31, 'Tatenda Kunaka', 'lloydmarimo@miis.edu', '$2y$10$oslD81VF3nlXgLYdwa3xj.hOuXg0Rra.hZAXdjJMp5D4qE03hwzrW', 1, 0, '', '', '', '', '', NULL, NULL, '', '2018-10-05 05:01:32', '2018-10-05 05:01:32'),
(32, 'Lloyd Marimo', 'marimo@yahoo.com', '$2y$10$21QQAr04hZgvRmPAySOIRelC47hl5J8lk8loQo0rZlVebE7ZcLeS.', 1, 0, 'ejdWVJ6UZTg:APA91bGdiFv_DF7vB6KaF9bAE9DbkfV0zVO6_DTbTmaNtlYClnwHR3-wUyvbo_teZDxrydS2ChLnxWAiyWjUiIsO9kk2iWm1lXTlsJCYvGtndBY_NfQI8dZZCanE5Kc1cpu_wtVNhzFn', '', '', '2620aa66b12e76b57865', '', NULL, NULL, '', '2018-10-05 05:02:32', '2018-10-05 05:02:32'),
(33, 'palak', 'palak@gmail.com', '$2y$10$O2C4E.06hy1hvp1vAAVigutbJN87ch1w9E3jKZA6TAtuu5U6RgdyO', 1, 0, '', '', '', '', 'bf068c449216c3aec9bb34afc54b49ae.jpg', 'Bahrain', 'engineer', '', '2018-10-15 07:16:55', '2018-10-15 07:16:55'),
(34, 'Bhaskar Dutt Sharma', 'bhaskar.mindiii@gmail.com', '$2y$10$LCxLTk.P.pvtpeu3xbAmbOYe8Nbs7ZjqnjxQnOqbD/sCe87rxBzp6', 1, 0, '0', '', '', '7cc9a61ef368fa730b05', '', NULL, NULL, '', '2018-10-17 05:33:52', '2018-10-17 05:33:52'),
(35, 'Bhaskar Dutt Sharma', 'bhaskar.mindi@gmail.com', '$2y$10$A6v56hvHG2YcEb0wTAFDyejfHJ4YK8GX.ofFEwSsib2XfGKyKhCBS', 1, 0, '0', '', '', 'dd543f82d6ebc2a85aaf', '', NULL, NULL, '', '2018-10-11 10:54:33', '2018-10-11 10:54:33'),
(36, 'Bhaskar Dutt Sharma', 'bhaskarmindi@gmail.com', '$2y$10$WBfdeOjClqZPaIgvbACnuOKK3Ol57ZKJ7reyiqybzB8aqpuzXGwlG', 1, 0, '0', '', '', '5a714d8fa1f280219499', '', NULL, NULL, '', '2018-10-11 11:26:00', '2018-10-11 11:26:00'),
(37, 'Raj', 'aj@gmail.com', '$2y$10$CvZJvdJY18mkqSzu6OKcbunF8cw785p9tLtXVUKEc3eKlW6HH4Wtm', 1, 0, '0', '', '', '9cc3a7dedb812a4a2386', '', NULL, NULL, '', '2018-10-12 05:46:33', '2018-10-12 05:46:33'),
(38, 'Raj', 'suraj@gmail.com', '$2y$10$ibtfPdtHVsRaq7sdc3HPIOvlyG9PgefQN3DyG5lyOUAuAkRk2y7nG', 1, 0, '0', '', '', 'df0674ee04462af12b1f', '8c28a623a7afb4be772f86bb34ec4b31.png', NULL, NULL, '', '2018-10-12 10:10:43', '2018-10-12 10:10:43'),
(39, 'Palak', 'pg@gmail.com', '$2y$10$zELxkNhQeGnmeTiGTo6.x./iMF1Knu17hqsb8nmHzqTV4dPhwr4Hq', 1, 0, '', '', '', '70874eeaffa00da5fdeb', '', NULL, NULL, '', '2018-10-17 07:55:18', '2018-10-17 07:55:18'),
(40, 'Testing', 'testing@gmail.com', '$2y$10$rV5i.hkpfZb0ntRoqN197uEDZZMbAA8mtB7o67kzSIqdqIfjTfgGS', 1, 0, '0', '', '', '4a0dffa4442aa96a9cbf', '', NULL, NULL, '', '2018-10-15 08:44:12', '2018-10-15 08:44:12'),
(41, 'test', 'test@gmail.com', '$2y$10$BpJLIhBK84XziA8qlLTdOODxsDy4lrFWVhT.L3iA.shXR.Z9ctdHW', 1, 0, 'dP8iJmTP2V0:APA91bFeWYJrUK-r_wEtNy4yfR6WVxvcN9cX1yr9Eb-cC8yO5HZCV0BZlDJ6mxWrD0iADdjG8E27wz9bbKRfa6UGmAuunP_iTEAdF3WRUzk0KcG7ggQsrNFoNjvhIklKkCL9VIwRK6AP', '', '', 'cfd6fe965e136fc00605', '', NULL, NULL, '', '2018-10-16 13:15:01', '2018-10-16 13:15:01'),
(42, 'Testing', 'testi@gmail.com', '$2y$10$jU3JeFAS2fTFygcAWYLlAuGRiitWCwcbT2NEFVrYcm2Yn5AKq8S/C', 1, 0, '0', '', '', '4e40b14bc28d5ead402e', '', NULL, NULL, '', '2018-10-13 09:28:54', '2018-10-13 09:28:54'),
(43, 'Avi', 'avi@gmail.com', '$2y$10$1YD/7bke7Dd1No23fJgANeE0PQT.9T/xJENQjEM1AWBg3ik4ISW..', 1, 0, '', '', '', 'cf6d448c359f075b16b5', '', NULL, NULL, '', '2018-10-13 12:37:30', '2018-10-13 12:37:30'),
(44, 'Papal', 'gupta@gmail.com', '$2y$10$e0/kOkt.XHvmEg1S3nRV2u//idRhu1PSOgkw18V3LaT6yWZgCt6Em', 1, 0, '', '', '', '9b09fb4d3e3071ff358a', '', NULL, NULL, '', '2018-10-13 12:54:40', '2018-10-13 12:54:40'),
(45, 'Santosh', 'santosh@gmail.com', '$2y$10$JsWjnCb7bmbXoAFlWo5fVO17FTA6hG/Gv8QFUag5B4HcDdwC6hM3.', 1, 0, 'cVanTAjZU0U:APA91bGierEi0nDNWcx17inSomwQNr_LBIaTio0IEIJz_lWOT4m3ARoD3Sa8Is9-yhr4XoiRHfDsGK-WvpIogo5bKoBGCWev8c6F_XbvCd27w0tyJwXqJ6_VtoVXdh6Rv55XiPH6-e_b', '', '', '6d3366d7a91cc553a10f', '35bee7e80b88c2a22fbcb5e0858d1b01.jpg', NULL, NULL, '', '2018-10-13 15:33:41', '2018-10-13 15:33:41'),
(46, 'test', 'test1@gmail.com', '$2y$10$57p7AvL1mdplybBIRY0UWOBP.VaVBxUcJmVERK.unTURc/OAoWEWm', 1, 0, 'e9X1wZTdmdI:APA91bHsOCcfDpU2omEqqdXMXr6lNdHsGIoYdAaUeGV1WTBejVGM6uf89VbQ0E54M6IIh0G30bnIcjvk31nGBnUFH--6zpbZXniDlG7YZVCJ8-yHRpRgRlbiXUbhHL06mupX-2z4qe_R', '', '', 'eae0a7fa2a17eb1b9ebc', '0cfc8a8ae64cd3b8ee1a58747479f5cb.jpg', NULL, NULL, '', '2018-10-17 11:30:52', '2018-10-17 11:30:52'),
(50, 'Vijay', 'vijay@gmail.com', '$2y$10$BLCv0Bfuw.s7CVe52C3umu/n9KaS4qQWYthrXlir206mK2xexYt/O', 1, 0, '0', '', '', '65b8344b4ff62b9812b9', '', NULL, NULL, '', '2018-10-16 04:58:29', '2018-10-16 04:58:29'),
(51, 'webwe', 'webwe@g.com', '$2y$10$Gk0qtuxSfHf4l5GVKWALjOS4PeYzBuZsXJkUNwq905OLHHAYHeFCa', 1, 0, '', '', '', '', '', NULL, NULL, '', '2018-10-16 05:21:49', '2018-10-16 05:21:49'),
(52, 'Prachi', 'Prachiii@gmail.com', '$2y$10$dLQbi3T1KTV.yU/Gi/rdx.6o2x56QJ5OnDNAX40.8yl7biNSw7hNW', 1, 0, '', '', '', '', '', NULL, NULL, '', '2018-10-16 11:19:28', '2018-10-16 11:19:28'),
(53, 'mab', 'mab@gmail.com', '$2y$10$RF.ts5f40c/f03PJZNL7zO35GwuvQ7SrE.Utdreu8N/ufl7o77pje', 1, 0, '', '', '', 'b91e21b93d3754bdad03', 'b961c5e34b58ad4e16ae386456a1a884.jpg', NULL, NULL, '', '2018-10-16 12:26:13', '2018-10-16 12:26:13'),
(54, 'hdjsjz', 'gdj@gmail.com', '$2y$10$Te/U.AHByooVWfbuJDKEjeMzGCpEW2n2Q5WJi3XRkOIeAsgGEGqaS', 1, 0, '', '', '', '', '', NULL, NULL, '', '2018-10-16 14:16:22', '2018-10-16 14:16:22'),
(55, 'the', 'rahul@gmail.com', '$2y$10$cdZJLzj/SsJUxGK5gjKw/u42BwHjSPwGuwmOmce/QPSkoz2pPWIf.', 1, 0, 'c2Po956l4N8:APA91bFTGUb6hUMcdb-jXeAdZ-5bHpOPMm-TdXdQbrI82QgJyAovg7JT0RIwAP0EZ72yWMcLeHC4zgBWChMOyqtf3aZiFgMyt0xt_XCGNJFwgoJiDubD4RQVwem81sUO5Ie-c_2wWXX1', '', '', 'e9a0a45cc482c494045a', '', NULL, NULL, '', '2018-10-16 14:24:56', '2018-10-16 14:24:56'),
(56, 'Bhaskar Dutt Sharma', 'bhaskars.mindiii@gmail.com', '$2y$10$UJkyVOnpxoAoriZmm76WnuKKCIeoBw3PCsmxJnqYrxUcxhtB.DU1C', 1, 0, '0', '', '', '4a4ae8082b3913a270a0', '', NULL, NULL, '', '2018-10-17 05:33:47', '2018-10-17 05:33:47'),
(57, 'shyam', 'shyam@gmail.com', '$2y$10$AydBSPaMqTfsh/UOoFO9xuBBkVPcUfMREVGVOX7VQazBnlumFpQvO', 1, 0, 'dInKtcNaXRg:APA91bGGfOvInaGojM1eTQOzeGYegT5n_u4bl8M6D2GCjR6QPDDKptR4DcQD2d5vEDOjmu5GwJKDq_scfVlhm4nA5klcaH46eoaTsd8mXHQSyBV08DeS31ARHQrYJG1zRijjg-SlW2P9', '', '', '4326cdfa002a408ef365', '45589ee204d31e72092521fba938ab86.jpg', NULL, NULL, '', '2018-10-17 13:14:07', '2018-10-17 13:14:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`groupId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `group_comments`
--
ALTER TABLE `group_comments`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `group_likes`
--
ALTER TABLE `group_likes`
  ADD PRIMARY KEY (`likeId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`groupMemberId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `group_tag_mapping`
--
ALTER TABLE `group_tag_mapping`
  ADD PRIMARY KEY (`groupMapTagId`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_for` (`notification_for`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`optionId`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`postId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `post_attachments`
--
ALTER TABLE `post_attachments`
  ADD PRIMARY KEY (`postImagesId`),
  ADD KEY `jobs_id` (`post_id`);

--
-- Indexes for table `post_permissions`
--
ALTER TABLE `post_permissions`
  ADD PRIMARY KEY (`postPermissionId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_id` (`post_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tagId`);

--
-- Indexes for table `tags_mapping`
--
ALTER TABLE `tags_mapping`
  ADD PRIMARY KEY (`tapMapId`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=458;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `groupId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `group_comments`
--
ALTER TABLE `group_comments`
  MODIFY `commentId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `group_likes`
--
ALTER TABLE `group_likes`
  MODIFY `likeId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `groupMemberId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `group_tag_mapping`
--
ALTER TABLE `group_tag_mapping`
  MODIFY `groupMapTagId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=541;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `optionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=586;

--
-- AUTO_INCREMENT for table `post_attachments`
--
ALTER TABLE `post_attachments`
  MODIFY `postImagesId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=551;

--
-- AUTO_INCREMENT for table `post_permissions`
--
ALTER TABLE `post_permissions`
  MODIFY `postPermissionId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=586;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tagId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `tags_mapping`
--
ALTER TABLE `tags_mapping`
  MODIFY `tapMapId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`postId`) ON DELETE CASCADE;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `groups_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`categoryId`) ON DELETE CASCADE;

--
-- Constraints for table `group_comments`
--
ALTER TABLE `group_comments`
  ADD CONSTRAINT `group_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_comments_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`groupId`) ON DELETE CASCADE;

--
-- Constraints for table `group_likes`
--
ALTER TABLE `group_likes`
  ADD CONSTRAINT `group_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_likes_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`groupId`) ON DELETE CASCADE;

--
-- Constraints for table `group_tag_mapping`
--
ALTER TABLE `group_tag_mapping`
  ADD CONSTRAINT `group_tag_mapping_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`groupId`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_tag_mapping_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tagId`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`postId`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`categoryId`) ON DELETE CASCADE;

--
-- Constraints for table `post_permissions`
--
ALTER TABLE `post_permissions`
  ADD CONSTRAINT `post_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_permissions_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`postId`) ON DELETE CASCADE;

--
-- Constraints for table `tags_mapping`
--
ALTER TABLE `tags_mapping`
  ADD CONSTRAINT `tags_mapping_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`postId`) ON DELETE CASCADE,
  ADD CONSTRAINT `tags_mapping_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tagId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
