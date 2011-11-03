-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 03, 2011 at 07:30 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `freenet3`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_recorder`
--

CREATE TABLE IF NOT EXISTS `action_recorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `identifier` varchar(255) NOT NULL,
  `success` char(1) DEFAULT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_action_recorder_module` (`module`),
  KEY `idx_action_recorder_user_id` (`user_id`),
  KEY `idx_action_recorder_identifier` (`identifier`),
  KEY `idx_action_recorder_date_added` (`date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `action_recorder`
--


-- --------------------------------------------------------

--
-- Table structure for table `address_book`
--

CREATE TABLE IF NOT EXISTS `address_book` (
  `address_book_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL,
  `entry_gender` char(1) DEFAULT NULL,
  `entry_company` varchar(255) DEFAULT NULL,
  `entry_firstname` varchar(255) NOT NULL,
  `entry_lastname` varchar(255) NOT NULL,
  `entry_street_address` varchar(255) NOT NULL,
  `entry_suburb` varchar(255) DEFAULT NULL,
  `entry_postcode` varchar(255) NOT NULL,
  `entry_city` varchar(255) NOT NULL,
  `entry_state` varchar(255) DEFAULT NULL,
  `entry_country_id` int(11) NOT NULL DEFAULT '0',
  `entry_zone_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`address_book_id`),
  KEY `idx_address_book_customers_id` (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `address_book`
--


-- --------------------------------------------------------

--
-- Table structure for table `address_format`
--

CREATE TABLE IF NOT EXISTS `address_format` (
  `address_format_id` int(11) NOT NULL AUTO_INCREMENT,
  `address_format` varchar(128) NOT NULL,
  `address_summary` varchar(48) NOT NULL,
  PRIMARY KEY (`address_format_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `address_format`
--

INSERT INTO `address_format` (`address_format_id`, `address_format`, `address_summary`) VALUES
(1, '$firstname $lastname$cr$streets$cr$city, $postcode$cr$statecomma$country', '$city / $country'),
(2, '$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country', '$city, $state / $country'),
(3, '$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country', '$state / $country'),
(4, '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country', '$postcode / $country'),
(5, '$firstname $lastname$cr$streets$cr$postcode $city$cr$country', '$city / $country');

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE IF NOT EXISTS `administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `user_password` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`id`, `user_name`, `user_password`) VALUES
(1, 'sacarlson', '$P$DowASvCRIRe4FwIyvjXAyYawmka/aj/');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `banners_id` int(11) NOT NULL AUTO_INCREMENT,
  `banners_title` varchar(64) NOT NULL,
  `banners_url` varchar(255) NOT NULL,
  `banners_image` varchar(64) NOT NULL,
  `banners_group` varchar(10) NOT NULL,
  `banners_html_text` text,
  `expires_impressions` int(7) DEFAULT '0',
  `expires_date` datetime DEFAULT NULL,
  `date_scheduled` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`banners_id`),
  KEY `idx_banners_group` (`banners_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`banners_id`, `banners_title`, `banners_url`, `banners_image`, `banners_group`, `banners_html_text`, `expires_impressions`, `expires_date`, `date_scheduled`, `date_added`, `date_status_change`, `status`) VALUES
(1, 'osCommerce', 'http://www.oscommerce.com', 'banners/oscommerce.gif', '468x50', '', 0, NULL, NULL, '2010-09-22 09:41:17', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `banners_history`
--

CREATE TABLE IF NOT EXISTS `banners_history` (
  `banners_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `banners_id` int(11) NOT NULL,
  `banners_shown` int(5) NOT NULL DEFAULT '0',
  `banners_clicked` int(5) NOT NULL DEFAULT '0',
  `banners_history_date` datetime NOT NULL,
  PRIMARY KEY (`banners_history_id`),
  KEY `idx_banners_history_banners_id` (`banners_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=404 ;

--
-- Dumping data for table `banners_history`
--

INSERT INTO `banners_history` (`banners_history_id`, `banners_id`, `banners_shown`, `banners_clicked`, `banners_history_date`) VALUES
(1, 1, 88, 1, '2010-09-22 10:06:29'),
(2, 1, 1, 0, '2010-09-23 23:20:58'),
(3, 1, 40, 0, '2010-09-24 01:59:54'),
(4, 1, 6, 0, '2010-09-27 12:59:20'),
(5, 1, 6, 0, '2010-09-28 08:53:24'),
(6, 1, 9, 0, '2010-09-29 07:37:18'),
(7, 1, 4, 0, '2010-09-30 03:47:08'),
(8, 1, 14, 0, '2010-10-01 12:41:15'),
(9, 1, 3, 0, '2010-10-02 03:55:50'),
(10, 1, 8, 0, '2010-10-03 01:27:30'),
(11, 1, 8, 1, '2010-10-04 01:35:56'),
(12, 1, 5, 0, '2010-10-05 07:54:19'),
(13, 1, 6, 0, '2010-10-06 16:19:45'),
(14, 1, 3, 0, '2010-10-07 03:22:59'),
(15, 1, 5, 0, '2010-10-08 02:38:40'),
(16, 1, 17, 0, '2010-10-09 00:31:42'),
(17, 1, 21, 0, '2010-10-10 05:20:08'),
(18, 1, 39, 0, '2010-10-11 00:10:20'),
(19, 1, 13, 0, '2010-10-12 01:13:24'),
(20, 1, 16, 0, '2010-10-13 01:57:29'),
(21, 1, 12, 0, '2010-10-14 00:09:24'),
(22, 1, 7, 0, '2010-10-15 00:49:10'),
(23, 1, 22, 1, '2010-10-16 01:47:07'),
(24, 1, 33, 0, '2010-10-17 00:03:09'),
(25, 1, 8, 0, '2010-10-18 01:35:56'),
(26, 1, 10, 0, '2010-10-19 01:41:04'),
(27, 1, 13, 0, '2010-10-20 01:19:10'),
(28, 1, 4, 0, '2010-10-21 14:10:09'),
(29, 1, 13, 0, '2010-10-22 00:35:50'),
(30, 1, 17, 0, '2010-10-23 01:05:47'),
(31, 1, 19, 0, '2010-10-24 00:01:46'),
(32, 1, 20, 0, '2010-10-25 01:32:32'),
(33, 1, 23, 1, '2010-10-26 01:03:12'),
(34, 1, 54, 0, '2010-10-27 00:03:47'),
(35, 1, 33, 0, '2010-10-28 02:49:51'),
(36, 1, 17, 0, '2010-10-29 01:34:23'),
(37, 1, 10, 0, '2010-10-30 00:30:32'),
(38, 1, 3, 0, '2010-10-31 00:02:31'),
(39, 1, 4, 0, '2010-11-01 00:45:47'),
(40, 1, 42, 0, '2010-11-02 00:16:22'),
(41, 1, 32, 0, '2010-11-03 05:36:16'),
(42, 1, 23, 0, '2010-11-04 01:15:07'),
(43, 1, 16, 0, '2010-11-05 02:47:51'),
(44, 1, 20, 0, '2010-11-06 00:11:27'),
(45, 1, 11, 0, '2010-11-07 01:18:51'),
(46, 1, 26, 0, '2010-11-08 02:22:48'),
(47, 1, 32, 0, '2010-11-09 00:07:17'),
(48, 1, 28, 0, '2010-11-10 00:31:08'),
(49, 1, 12, 0, '2010-11-11 00:02:52'),
(50, 1, 28, 0, '2010-11-12 00:16:33'),
(51, 1, 23, 0, '2010-11-13 03:03:17'),
(52, 1, 46, 0, '2010-11-14 00:05:24'),
(53, 1, 25, 0, '2010-11-15 02:47:28'),
(54, 1, 42, 1, '2010-11-16 00:04:01'),
(55, 1, 21, 0, '2010-11-17 00:12:05'),
(56, 1, 3, 0, '2010-11-18 07:44:03'),
(57, 1, 15, 0, '2010-11-19 02:46:02'),
(58, 1, 11, 0, '2010-11-20 01:07:14'),
(59, 1, 21, 0, '2010-11-21 00:12:33'),
(60, 1, 4, 0, '2010-11-22 06:02:43'),
(61, 1, 4, 0, '2010-11-23 06:26:36'),
(62, 1, 23, 0, '2010-11-24 02:54:15'),
(63, 1, 10, 0, '2010-11-25 00:49:56'),
(64, 1, 13, 0, '2010-11-26 08:23:39'),
(65, 1, 11, 0, '2010-11-27 02:28:02'),
(66, 1, 5, 0, '2010-11-28 03:38:53'),
(67, 1, 19, 0, '2010-11-29 05:49:01'),
(68, 1, 6, 0, '2010-11-30 05:51:35'),
(69, 1, 6, 0, '2010-12-01 04:03:42'),
(70, 1, 25, 0, '2010-12-02 05:46:35'),
(71, 1, 44, 0, '2010-12-03 02:03:58'),
(72, 1, 17, 0, '2010-12-04 00:19:08'),
(73, 1, 21, 0, '2010-12-05 03:20:56'),
(74, 1, 16, 0, '2010-12-06 01:50:08'),
(75, 1, 11, 0, '2010-12-07 00:25:18'),
(76, 1, 14, 0, '2010-12-08 00:02:22'),
(77, 1, 8, 0, '2010-12-09 04:27:23'),
(78, 1, 13, 0, '2010-12-10 00:32:03'),
(79, 1, 2, 0, '2010-12-11 09:44:41'),
(80, 1, 36, 1, '2010-12-12 00:08:41'),
(81, 1, 53, 0, '2010-12-13 01:19:11'),
(82, 1, 33, 0, '2010-12-14 00:29:35'),
(83, 1, 50, 0, '2010-12-15 01:59:30'),
(84, 1, 30, 0, '2010-12-16 00:03:32'),
(85, 1, 23, 1, '2010-12-17 00:29:43'),
(86, 1, 5, 0, '2010-12-18 00:23:22'),
(87, 1, 18, 0, '2010-12-19 01:15:58'),
(88, 1, 10, 0, '2010-12-20 14:50:52'),
(89, 1, 6, 0, '2010-12-21 00:27:12'),
(90, 1, 14, 0, '2010-12-22 18:44:51'),
(91, 1, 2, 0, '2010-12-23 04:42:05'),
(92, 1, 7, 0, '2010-12-24 14:57:02'),
(93, 1, 20, 0, '2010-12-25 00:01:23'),
(94, 1, 27, 0, '2010-12-26 00:19:54'),
(95, 1, 5, 0, '2010-12-27 00:34:58'),
(96, 1, 8, 0, '2010-12-28 00:04:18'),
(97, 1, 19, 0, '2010-12-29 02:22:58'),
(98, 1, 7, 0, '2010-12-30 00:06:48'),
(99, 1, 17, 0, '2010-12-31 02:30:59'),
(100, 1, 63, 0, '2011-01-01 12:09:17'),
(101, 1, 7, 0, '2011-01-02 02:34:17'),
(102, 1, 5, 0, '2011-01-03 06:28:58'),
(103, 1, 17, 0, '2011-01-04 01:53:12'),
(104, 1, 11, 0, '2011-01-05 04:46:00'),
(105, 1, 2, 0, '2011-01-06 00:29:51'),
(106, 1, 6, 0, '2011-01-07 00:17:30'),
(107, 1, 10, 0, '2011-01-08 04:28:16'),
(108, 1, 7, 0, '2011-01-09 02:55:15'),
(109, 1, 7, 0, '2011-01-10 00:49:12'),
(110, 1, 6, 0, '2011-01-11 00:15:40'),
(111, 1, 7, 0, '2011-01-12 01:01:21'),
(112, 1, 5, 0, '2011-01-13 02:25:00'),
(113, 1, 22, 0, '2011-01-14 00:55:10'),
(114, 1, 48, 0, '2011-01-15 00:32:50'),
(115, 1, 14, 1, '2011-01-16 02:11:34'),
(116, 1, 8, 0, '2011-01-17 02:52:03'),
(117, 1, 8, 0, '2011-01-18 00:27:38'),
(118, 1, 10, 0, '2011-01-19 00:38:52'),
(119, 1, 9, 0, '2011-01-20 00:33:52'),
(120, 1, 15, 0, '2011-01-21 03:34:00'),
(121, 1, 5, 0, '2011-01-22 02:13:13'),
(122, 1, 12, 0, '2011-01-27 08:31:54'),
(123, 1, 40, 0, '2011-01-28 01:15:29'),
(124, 1, 24, 0, '2011-01-29 00:02:49'),
(125, 1, 10, 0, '2011-01-30 01:11:10'),
(126, 1, 10, 0, '2011-01-31 02:47:09'),
(127, 1, 7, 0, '2011-02-01 05:17:34'),
(128, 1, 7, 0, '2011-02-02 01:07:17'),
(129, 1, 18, 0, '2011-02-03 02:30:03'),
(130, 1, 4, 0, '2011-02-04 03:53:24'),
(131, 1, 6, 0, '2011-02-05 01:43:59'),
(132, 1, 6, 0, '2011-02-06 01:00:17'),
(133, 1, 21, 0, '2011-02-07 01:54:57'),
(134, 1, 2, 0, '2011-02-08 10:28:46'),
(135, 1, 3, 0, '2011-02-09 03:57:17'),
(136, 1, 3, 0, '2011-02-10 02:04:28'),
(137, 1, 4, 0, '2011-02-11 03:17:03'),
(138, 1, 4, 0, '2011-02-12 02:40:59'),
(139, 1, 4, 0, '2011-02-13 13:11:12'),
(140, 1, 10, 0, '2011-02-14 02:50:44'),
(141, 1, 14, 0, '2011-02-15 10:11:42'),
(142, 1, 6, 0, '2011-02-16 09:20:26'),
(143, 1, 8, 0, '2011-02-17 02:45:28'),
(144, 1, 6, 0, '2011-02-18 02:35:47'),
(145, 1, 9, 0, '2011-02-19 03:39:18'),
(146, 1, 7, 0, '2011-02-20 01:19:26'),
(147, 1, 3, 0, '2011-02-21 07:58:49'),
(148, 1, 5, 0, '2011-02-22 01:06:35'),
(149, 1, 6, 0, '2011-02-23 00:34:40'),
(150, 1, 38, 0, '2011-02-24 09:40:13'),
(151, 1, 16, 0, '2011-02-25 06:32:43'),
(152, 1, 16, 0, '2011-02-26 00:12:59'),
(153, 1, 25, 0, '2011-02-27 00:23:39'),
(154, 1, 40, 0, '2011-02-28 00:15:23'),
(155, 1, 14, 0, '2011-03-01 00:19:14'),
(156, 1, 38, 1, '2011-03-02 00:55:20'),
(157, 1, 28, 0, '2011-03-03 00:14:21'),
(158, 1, 54, 0, '2011-03-04 00:44:58'),
(159, 1, 84, 0, '2011-03-05 00:59:01'),
(160, 1, 32, 0, '2011-03-06 00:14:40'),
(161, 1, 21, 0, '2011-03-07 00:27:10'),
(162, 1, 15, 0, '2011-03-08 02:35:19'),
(163, 1, 20, 0, '2011-03-09 00:41:48'),
(164, 1, 28, 0, '2011-03-10 01:11:36'),
(165, 1, 103, 0, '2011-03-11 00:32:26'),
(166, 1, 103, 0, '2011-03-11 00:32:26'),
(167, 1, 16, 0, '2011-03-12 00:00:36'),
(168, 1, 56, 0, '2011-03-13 02:21:13'),
(169, 1, 42, 0, '2011-03-14 00:05:52'),
(170, 1, 20, 0, '2011-03-15 00:47:18'),
(171, 1, 62, 0, '2011-03-16 03:22:52'),
(172, 1, 68, 0, '2011-03-17 01:17:19'),
(173, 1, 36, 0, '2011-03-18 04:01:44'),
(174, 1, 91, 0, '2011-03-19 00:32:41'),
(175, 1, 22, 0, '2011-03-20 02:21:10'),
(176, 1, 34, 0, '2011-03-21 03:59:15'),
(177, 1, 68, 0, '2011-03-22 00:25:07'),
(178, 1, 36, 0, '2011-03-23 01:10:22'),
(179, 1, 48, 0, '2011-03-24 00:29:55'),
(180, 1, 82, 0, '2011-03-25 01:18:31'),
(181, 1, 32, 0, '2011-03-26 01:10:42'),
(182, 1, 28, 0, '2011-03-27 01:21:09'),
(183, 1, 20, 0, '2011-03-28 01:17:08'),
(184, 1, 8, 0, '2011-03-29 00:57:20'),
(185, 1, 10, 0, '2011-03-30 00:59:04'),
(186, 1, 28, 0, '2011-03-31 04:43:42'),
(187, 1, 30, 0, '2011-04-01 00:00:27'),
(188, 1, 10, 0, '2011-04-02 01:11:25'),
(189, 1, 16, 0, '2011-04-03 00:31:18'),
(190, 1, 11, 0, '2011-04-04 00:30:34'),
(191, 1, 10, 0, '2011-04-05 03:12:40'),
(192, 1, 8, 0, '2011-04-06 03:19:21'),
(193, 1, 15, 0, '2011-04-07 01:23:39'),
(194, 1, 3, 0, '2011-04-08 08:11:55'),
(195, 1, 8, 0, '2011-04-09 06:33:33'),
(196, 1, 26, 1, '2011-04-10 01:25:07'),
(197, 1, 15, 0, '2011-04-11 00:07:23'),
(198, 1, 15, 0, '2011-04-12 00:07:04'),
(199, 1, 9, 0, '2011-04-13 04:18:17'),
(200, 1, 25, 0, '2011-04-14 00:17:21'),
(201, 1, 17, 0, '2011-04-15 03:11:47'),
(202, 1, 6, 0, '2011-04-16 03:53:14'),
(203, 1, 8, 0, '2011-04-17 01:54:30'),
(204, 1, 11, 0, '2011-04-18 00:34:39'),
(205, 1, 26, 0, '2011-04-19 01:21:36'),
(206, 1, 13, 0, '2011-04-20 01:53:43'),
(207, 1, 22, 0, '2011-04-21 00:07:02'),
(208, 1, 7, 0, '2011-04-22 00:13:45'),
(209, 1, 13, 0, '2011-04-23 03:14:06'),
(210, 1, 11, 0, '2011-04-24 00:38:37'),
(211, 1, 9, 0, '2011-04-25 02:31:01'),
(212, 1, 11, 0, '2011-04-26 02:34:12'),
(213, 1, 13, 0, '2011-04-27 00:29:53'),
(214, 1, 14, 0, '2011-04-28 00:33:20'),
(215, 1, 10, 0, '2011-04-29 10:43:34'),
(216, 1, 13, 0, '2011-04-30 01:28:36'),
(217, 1, 11, 0, '2011-05-01 02:04:53'),
(218, 1, 20, 0, '2011-05-02 01:48:39'),
(219, 1, 6, 0, '2011-05-03 03:00:03'),
(220, 1, 15, 0, '2011-05-04 00:14:04'),
(221, 1, 20, 0, '2011-05-05 04:14:58'),
(222, 1, 17, 0, '2011-05-06 00:51:53'),
(223, 1, 8, 0, '2011-05-07 02:40:39'),
(224, 1, 14, 0, '2011-05-08 00:34:29'),
(225, 1, 37, 0, '2011-05-09 00:26:41'),
(226, 1, 24, 0, '2011-05-10 00:55:23'),
(227, 1, 17, 0, '2011-05-11 01:03:03'),
(228, 1, 27, 0, '2011-05-12 00:03:59'),
(229, 1, 24, 0, '2011-05-13 00:53:32'),
(230, 1, 11, 0, '2011-05-14 01:00:27'),
(231, 1, 25, 0, '2011-05-15 00:39:52'),
(232, 1, 12, 0, '2011-05-16 00:33:49'),
(233, 1, 15, 0, '2011-05-17 00:48:34'),
(234, 1, 13, 0, '2011-05-18 00:48:22'),
(235, 1, 32, 0, '2011-05-19 01:58:12'),
(236, 1, 28, 0, '2011-05-20 00:45:52'),
(237, 1, 10, 0, '2011-05-21 02:04:45'),
(238, 1, 11, 0, '2011-05-22 00:43:52'),
(239, 1, 14, 0, '2011-05-23 07:09:55'),
(240, 1, 14, 0, '2011-05-24 01:41:01'),
(241, 1, 21, 0, '2011-05-25 00:22:35'),
(242, 1, 15, 0, '2011-05-26 00:14:39'),
(243, 1, 15, 0, '2011-05-27 00:09:24'),
(244, 1, 15, 0, '2011-05-28 00:14:09'),
(245, 1, 19, 0, '2011-05-29 00:12:01'),
(246, 1, 12, 0, '2011-05-30 01:42:10'),
(247, 1, 18, 0, '2011-05-31 02:34:21'),
(248, 1, 5, 0, '2011-06-01 02:15:19'),
(249, 1, 12, 0, '2011-06-02 02:26:24'),
(250, 1, 12, 0, '2011-06-03 01:47:35'),
(251, 1, 15, 0, '2011-06-04 02:10:38'),
(252, 1, 15, 0, '2011-06-05 00:20:53'),
(253, 1, 8, 0, '2011-06-06 03:00:30'),
(254, 1, 14, 0, '2011-06-07 05:21:50'),
(255, 1, 12, 0, '2011-06-08 00:10:39'),
(256, 1, 12, 0, '2011-06-09 01:52:12'),
(257, 1, 32, 0, '2011-06-10 00:26:05'),
(258, 1, 11, 0, '2011-06-11 01:24:31'),
(259, 1, 29, 1, '2011-06-12 01:07:05'),
(260, 1, 15, 0, '2011-06-13 02:09:14'),
(261, 1, 9, 0, '2011-06-14 05:05:38'),
(262, 1, 12, 0, '2011-06-15 01:51:18'),
(263, 1, 15, 0, '2011-06-16 05:59:38'),
(264, 1, 10, 0, '2011-06-17 00:10:55'),
(265, 1, 20, 0, '2011-06-18 01:56:33'),
(266, 1, 4, 0, '2011-06-19 08:02:13'),
(267, 1, 52, 0, '2011-06-20 00:54:36'),
(268, 1, 28, 0, '2011-06-21 00:35:24'),
(269, 1, 25, 0, '2011-06-22 05:32:29'),
(270, 1, 9, 0, '2011-06-23 00:13:37'),
(271, 1, 29, 0, '2011-06-24 00:22:03'),
(272, 1, 27, 0, '2011-06-25 02:39:00'),
(273, 1, 4, 0, '2011-06-26 00:10:43'),
(274, 1, 8, 0, '2011-06-27 00:29:00'),
(275, 1, 12, 0, '2011-06-28 00:28:00'),
(276, 1, 34, 0, '2011-06-29 00:23:06'),
(277, 1, 11, 0, '2011-06-30 01:34:10'),
(278, 1, 7, 0, '2011-07-01 00:33:23'),
(279, 1, 12, 0, '2011-07-02 00:51:55'),
(280, 1, 7, 0, '2011-07-03 05:51:34'),
(281, 1, 9, 0, '2011-07-04 06:20:21'),
(282, 1, 10, 0, '2011-07-05 00:26:43'),
(283, 1, 13, 0, '2011-07-06 02:03:51'),
(284, 1, 12, 0, '2011-07-07 00:43:11'),
(285, 1, 10, 0, '2011-07-08 01:15:26'),
(286, 1, 21, 0, '2011-07-09 05:12:36'),
(287, 1, 22, 0, '2011-07-10 00:23:10'),
(288, 1, 35, 0, '2011-07-11 00:45:25'),
(289, 1, 29, 0, '2011-07-12 02:58:57'),
(290, 1, 54, 0, '2011-07-13 01:24:25'),
(291, 1, 129, 0, '2011-07-14 00:37:15'),
(292, 1, 36, 0, '2011-07-15 04:01:26'),
(293, 1, 12, 0, '2011-07-16 06:02:19'),
(294, 1, 28, 0, '2011-07-17 01:55:51'),
(295, 1, 24, 0, '2011-07-18 01:10:03'),
(296, 1, 26, 0, '2011-07-19 00:15:46'),
(297, 1, 19, 0, '2011-07-20 00:53:49'),
(298, 1, 27, 0, '2011-07-21 01:55:12'),
(299, 1, 16, 0, '2011-07-22 01:43:32'),
(300, 1, 38, 0, '2011-07-23 00:47:20'),
(301, 1, 5, 0, '2011-07-24 04:11:22'),
(302, 1, 14, 0, '2011-07-25 00:25:30'),
(303, 1, 23, 0, '2011-07-26 00:01:50'),
(304, 1, 15, 0, '2011-07-27 00:38:34'),
(305, 1, 25, 0, '2011-07-28 00:44:45'),
(306, 1, 15, 0, '2011-07-29 02:14:51'),
(307, 1, 10, 0, '2011-07-30 00:01:10'),
(308, 1, 26, 0, '2011-07-31 05:07:14'),
(309, 1, 14, 0, '2011-08-01 06:21:44'),
(310, 1, 18, 0, '2011-08-02 15:41:34'),
(311, 1, 14, 0, '2011-08-03 00:32:39'),
(312, 1, 10, 0, '2011-08-04 00:25:16'),
(313, 1, 14, 0, '2011-08-05 00:33:56'),
(314, 1, 9, 0, '2011-08-06 00:57:18'),
(315, 1, 25, 0, '2011-08-07 01:20:10'),
(316, 1, 15, 0, '2011-08-08 12:06:44'),
(317, 1, 10, 0, '2011-08-09 00:51:33'),
(318, 1, 19, 0, '2011-08-10 00:26:46'),
(319, 1, 18, 0, '2011-08-11 14:18:12'),
(320, 1, 9, 0, '2011-08-12 03:44:01'),
(321, 1, 30, 0, '2011-08-13 00:00:22'),
(322, 1, 8, 0, '2011-08-14 02:46:05'),
(323, 1, 31, 0, '2011-08-15 00:59:40'),
(324, 1, 11, 0, '2011-08-16 00:50:41'),
(325, 1, 11, 0, '2011-08-17 13:48:06'),
(326, 1, 19, 0, '2011-08-18 00:22:09'),
(327, 1, 17, 0, '2011-08-19 00:44:43'),
(328, 1, 10, 0, '2011-08-20 03:50:54'),
(329, 1, 6, 0, '2011-08-21 00:08:46'),
(330, 1, 12, 0, '2011-08-22 00:18:08'),
(331, 1, 27, 0, '2011-08-23 00:10:49'),
(332, 1, 17, 0, '2011-08-24 01:09:35'),
(333, 1, 14, 0, '2011-08-25 00:45:14'),
(334, 1, 125, 0, '2011-08-26 01:05:13'),
(335, 1, 52, 0, '2011-08-27 01:03:46'),
(336, 1, 25, 0, '2011-08-28 00:01:37'),
(337, 1, 16, 0, '2011-08-29 01:27:05'),
(338, 1, 46, 0, '2011-08-30 07:00:42'),
(339, 1, 18, 0, '2011-08-31 02:37:20'),
(340, 1, 12, 0, '2011-09-01 02:38:25'),
(341, 1, 8, 0, '2011-09-02 04:28:56'),
(342, 1, 58, 0, '2011-09-03 00:34:40'),
(343, 1, 9, 0, '2011-09-04 04:05:13'),
(344, 1, 20, 0, '2011-09-05 05:16:09'),
(345, 1, 12, 0, '2011-09-06 01:07:46'),
(346, 1, 52, 0, '2011-09-07 05:20:48'),
(347, 1, 28, 0, '2011-09-08 00:48:21'),
(348, 1, 16, 0, '2011-09-09 01:07:24'),
(349, 1, 141, 0, '2011-09-10 00:04:48'),
(350, 1, 15, 0, '2011-09-11 04:01:16'),
(351, 1, 10, 0, '2011-09-12 00:28:35'),
(352, 1, 18, 0, '2011-09-13 01:12:59'),
(353, 1, 26, 0, '2011-09-14 01:16:35'),
(354, 1, 12, 0, '2011-09-15 01:14:29'),
(355, 1, 17, 0, '2011-09-16 06:33:21'),
(356, 1, 9, 0, '2011-09-17 00:36:26'),
(357, 1, 14, 0, '2011-09-18 00:49:39'),
(358, 1, 12, 0, '2011-09-19 00:19:04'),
(359, 1, 20, 0, '2011-09-20 00:52:21'),
(360, 1, 6, 0, '2011-09-21 06:59:41'),
(361, 1, 4, 0, '2011-09-22 22:12:36'),
(362, 1, 24, 0, '2011-09-23 00:21:11'),
(363, 1, 12, 0, '2011-09-24 00:41:01'),
(364, 1, 14, 0, '2011-09-25 01:59:27'),
(365, 1, 18, 0, '2011-09-26 00:53:07'),
(366, 1, 55, 0, '2011-09-27 00:49:26'),
(367, 1, 7, 0, '2011-09-28 06:27:42'),
(368, 1, 18, 0, '2011-09-29 04:17:03'),
(369, 1, 12, 0, '2011-09-30 01:02:25'),
(370, 1, 77, 0, '2011-10-01 00:12:32'),
(371, 1, 45, 0, '2011-10-02 00:20:43'),
(372, 1, 9, 0, '2011-10-03 02:25:47'),
(373, 1, 14, 0, '2011-10-04 00:20:41'),
(374, 1, 9, 0, '2011-10-05 03:33:54'),
(375, 1, 15, 0, '2011-10-06 00:42:57'),
(376, 1, 6, 0, '2011-10-07 00:47:49'),
(377, 1, 11, 0, '2011-10-08 00:32:30'),
(378, 1, 26, 0, '2011-10-09 00:05:00'),
(379, 1, 21, 0, '2011-10-10 01:04:58'),
(380, 1, 12, 0, '2011-10-11 10:16:30'),
(381, 1, 20, 0, '2011-10-12 00:27:20'),
(382, 1, 9, 0, '2011-10-13 01:38:57'),
(383, 1, 21, 0, '2011-10-14 00:31:06'),
(384, 1, 14, 0, '2011-10-15 02:51:21'),
(385, 1, 11, 0, '2011-10-16 00:22:01'),
(386, 1, 64, 0, '2011-10-17 01:27:17'),
(387, 1, 14, 0, '2011-10-18 02:01:25'),
(388, 1, 12, 0, '2011-10-19 01:28:56'),
(389, 1, 8, 0, '2011-10-20 01:21:18'),
(390, 1, 13, 0, '2011-10-21 00:09:50'),
(391, 1, 7, 0, '2011-10-22 02:28:39'),
(392, 1, 12, 0, '2011-10-23 01:36:46'),
(393, 1, 13, 0, '2011-10-24 00:26:44'),
(394, 1, 29, 0, '2011-10-25 00:47:21'),
(395, 1, 20, 0, '2011-10-26 01:11:21'),
(396, 1, 97, 0, '2011-10-27 05:24:18'),
(397, 1, 14, 0, '2011-10-28 00:34:04'),
(398, 1, 10, 0, '2011-10-29 07:42:09'),
(399, 1, 8, 0, '2011-10-30 00:15:44'),
(400, 1, 23, 0, '2011-10-31 00:05:27'),
(401, 1, 15, 0, '2011-11-01 02:05:52'),
(402, 1, 41, 0, '2011-11-02 00:58:11'),
(403, 1, 14, 0, '2011-11-03 00:22:20');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_image` varchar(64) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(3) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`categories_id`),
  KEY `idx_categories_parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `categories_image`, `parent_id`, `sort_order`, `date_added`, `last_modified`) VALUES
(1, 'DWL-G122_main.jpg', 0, 1, '2010-09-22 09:41:17', '2010-09-22 10:07:55'),
(2, 'cdimage-105x61.png', 0, 2, '2010-09-22 09:41:17', '2010-09-22 10:07:19'),
(21, 'wifi_symbol2.jpg', 0, 0, '2010-09-22 10:01:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories_description`
--

CREATE TABLE IF NOT EXISTS `categories_description` (
  `categories_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `categories_name` varchar(32) NOT NULL,
  PRIMARY KEY (`categories_id`,`language_id`),
  KEY `idx_categories_name` (`categories_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories_description`
--

INSERT INTO `categories_description` (`categories_id`, `language_id`, `categories_name`) VALUES
(1, 1, 'Hardware'),
(2, 1, 'Software'),
(21, 1, 'Services');

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `configuration_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_title` varchar(255) NOT NULL,
  `configuration_key` varchar(255) NOT NULL,
  `configuration_value` text NOT NULL,
  `configuration_description` varchar(255) NOT NULL,
  `configuration_group_id` int(11) NOT NULL,
  `sort_order` int(5) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `use_function` varchar(255) DEFAULT NULL,
  `set_function` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`configuration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=198 ;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1, 'Store Name', 'STORE_NAME', 'FreeNet', 'The name of my store', 1, 1, NULL, '2010-09-22 09:41:17', NULL, NULL),
(2, 'Store Owner', 'STORE_OWNER', 'sacarlson', 'The name of my store owner', 1, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(3, 'E-Mail Address', 'STORE_OWNER_EMAIL_ADDRESS', 'sacarlson@ipipi.com', 'The e-mail address of my store owner', 1, 3, NULL, '2010-09-22 09:41:17', NULL, NULL),
(4, 'E-Mail From', 'EMAIL_FROM', '"sacarlson" <sacarlson@ipipi.com>', 'The e-mail address used in (sent) e-mails', 1, 4, NULL, '2010-09-22 09:41:17', NULL, NULL),
(5, 'Country', 'STORE_COUNTRY', '223', 'The country my store is located in <br><br><b>Note: Please remember to update the store zone.</b>', 1, 6, NULL, '2010-09-22 09:41:17', 'tep_get_country_name', 'tep_cfg_pull_down_country_list('),
(6, 'Zone', 'STORE_ZONE', '18', 'The zone my store is located in', 1, 7, NULL, '2010-09-22 09:41:17', 'tep_cfg_get_zone_name', 'tep_cfg_pull_down_zone_list('),
(7, 'Expected Sort Order', 'EXPECTED_PRODUCTS_SORT', 'desc', 'This is the sort order used in the expected products box.', 1, 8, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''asc'', ''desc''), '),
(8, 'Expected Sort Field', 'EXPECTED_PRODUCTS_FIELD', 'date_expected', 'The column to sort by in the expected products box.', 1, 9, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''products_name'', ''date_expected''), '),
(9, 'Switch To Default Language Currency', 'USE_DEFAULT_LANGUAGE_CURRENCY', 'false', 'Automatically switch to the language''s currency when it is changed', 1, 10, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(10, 'Send Extra Order Emails To', 'SEND_EXTRA_ORDER_EMAILS_TO', '', 'Send extra order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', 1, 11, NULL, '2010-09-22 09:41:17', NULL, NULL),
(11, 'Use Search-Engine Safe URLs', 'SEARCH_ENGINE_FRIENDLY_URLS', 'false', 'Use search-engine safe urls for all site links', 1, 12, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(12, 'Display Cart After Adding Product', 'DISPLAY_CART', 'true', 'Display the shopping cart after adding a product (or return back to their origin)', 1, 14, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(13, 'Allow Guest To Tell A Friend', 'ALLOW_GUEST_TO_TELL_A_FRIEND', 'false', 'Allow guests to tell a friend about a product', 1, 15, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(14, 'Default Search Operator', 'ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and', 'Default search operators', 1, 17, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''and'', ''or''), '),
(15, 'Store Address and Phone', 'STORE_NAME_ADDRESS', 'FreeNet\r\n482/119 Moo 10 Soi 13\r\nThailand\r\nPhone: +66-86-827-0277\r\nlocal phone: 086-827-0277', 'This is the Store Name, Address and Phone used on printable documents and displayed online', 1, 18, '2010-09-22 13:06:12', '2010-09-22 09:41:17', NULL, 'tep_cfg_textarea('),
(16, 'Show Category Counts', 'SHOW_COUNTS', 'true', 'Count recursively how many products are in each category', 1, 19, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(17, 'Tax Decimal Places', 'TAX_DECIMAL_PLACES', '0', 'Pad the tax value this amount of decimal places', 1, 20, NULL, '2010-09-22 09:41:17', NULL, NULL),
(18, 'Display Prices with Tax', 'DISPLAY_PRICE_WITH_TAX', 'false', 'Display prices with tax included (true) or add the tax at the end (false)', 1, 21, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(19, 'First Name', 'ENTRY_FIRST_NAME_MIN_LENGTH', '2', 'Minimum length of first name', 2, 1, NULL, '2010-09-22 09:41:17', NULL, NULL),
(20, 'Last Name', 'ENTRY_LAST_NAME_MIN_LENGTH', '2', 'Minimum length of last name', 2, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(21, 'Date of Birth', 'ENTRY_DOB_MIN_LENGTH', '10', 'Minimum length of date of birth', 2, 3, NULL, '2010-09-22 09:41:17', NULL, NULL),
(22, 'E-Mail Address', 'ENTRY_EMAIL_ADDRESS_MIN_LENGTH', '6', 'Minimum length of e-mail address', 2, 4, NULL, '2010-09-22 09:41:17', NULL, NULL),
(23, 'Street Address', 'ENTRY_STREET_ADDRESS_MIN_LENGTH', '5', 'Minimum length of street address', 2, 5, NULL, '2010-09-22 09:41:17', NULL, NULL),
(24, 'Company', 'ENTRY_COMPANY_MIN_LENGTH', '2', 'Minimum length of company name', 2, 6, NULL, '2010-09-22 09:41:17', NULL, NULL),
(25, 'Post Code', 'ENTRY_POSTCODE_MIN_LENGTH', '2', 'Minimum length of post code', 2, 7, '2011-10-27 16:31:25', '2010-09-22 09:41:17', NULL, NULL),
(26, 'City', 'ENTRY_CITY_MIN_LENGTH', '1', 'Minimum length of city', 2, 8, '2011-10-27 16:31:38', '2010-09-22 09:41:17', NULL, NULL),
(27, 'State', 'ENTRY_STATE_MIN_LENGTH', '2', 'Minimum length of state', 2, 9, NULL, '2010-09-22 09:41:17', NULL, NULL),
(28, 'Telephone Number', 'ENTRY_TELEPHONE_MIN_LENGTH', '3', 'Minimum length of telephone number', 2, 10, NULL, '2010-09-22 09:41:17', NULL, NULL),
(29, 'Password', 'ENTRY_PASSWORD_MIN_LENGTH', '5', 'Minimum length of password', 2, 11, NULL, '2010-09-22 09:41:17', NULL, NULL),
(30, 'Credit Card Owner Name', 'CC_OWNER_MIN_LENGTH', '3', 'Minimum length of credit card owner name', 2, 12, NULL, '2010-09-22 09:41:17', NULL, NULL),
(31, 'Credit Card Number', 'CC_NUMBER_MIN_LENGTH', '10', 'Minimum length of credit card number', 2, 13, NULL, '2010-09-22 09:41:17', NULL, NULL),
(32, 'Review Text', 'REVIEW_TEXT_MIN_LENGTH', '50', 'Minimum length of review text', 2, 14, NULL, '2010-09-22 09:41:17', NULL, NULL),
(33, 'Best Sellers', 'MIN_DISPLAY_BESTSELLERS', '1', 'Minimum number of best sellers to display', 2, 15, NULL, '2010-09-22 09:41:17', NULL, NULL),
(34, 'Also Purchased', 'MIN_DISPLAY_ALSO_PURCHASED', '1', 'Minimum number of products to display in the ''This Customer Also Purchased'' box', 2, 16, NULL, '2010-09-22 09:41:17', NULL, NULL),
(35, 'Address Book Entries', 'MAX_ADDRESS_BOOK_ENTRIES', '5', 'Maximum address book entries a customer is allowed to have', 3, 1, NULL, '2010-09-22 09:41:17', NULL, NULL),
(36, 'Search Results', 'MAX_DISPLAY_SEARCH_RESULTS', '20', 'Amount of products to list', 3, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(37, 'Page Links', 'MAX_DISPLAY_PAGE_LINKS', '5', 'Number of ''number'' links use for page-sets', 3, 3, NULL, '2010-09-22 09:41:17', NULL, NULL),
(38, 'Special Products', 'MAX_DISPLAY_SPECIAL_PRODUCTS', '9', 'Maximum number of products on special to display', 3, 4, NULL, '2010-09-22 09:41:17', NULL, NULL),
(39, 'New Products Module', 'MAX_DISPLAY_NEW_PRODUCTS', '9', 'Maximum number of new products to display in a category', 3, 5, NULL, '2010-09-22 09:41:17', NULL, NULL),
(40, 'Products Expected', 'MAX_DISPLAY_UPCOMING_PRODUCTS', '10', 'Maximum number of products expected to display', 3, 6, NULL, '2010-09-22 09:41:17', NULL, NULL),
(41, 'Manufacturers List', 'MAX_DISPLAY_MANUFACTURERS_IN_A_LIST', '0', 'Used in manufacturers box; when the number of manufacturers exceeds this number, a drop-down list will be displayed instead of the default list', 3, 7, NULL, '2010-09-22 09:41:17', NULL, NULL),
(42, 'Manufacturers Select Size', 'MAX_MANUFACTURERS_LIST', '1', 'Used in manufacturers box; when this value is ''1'' the classic drop-down list will be used for the manufacturers box. Otherwise, a list-box with the specified number of rows will be displayed.', 3, 7, NULL, '2010-09-22 09:41:17', NULL, NULL),
(43, 'Length of Manufacturers Name', 'MAX_DISPLAY_MANUFACTURER_NAME_LEN', '15', 'Used in manufacturers box; maximum length of manufacturers name to display', 3, 8, NULL, '2010-09-22 09:41:17', NULL, NULL),
(44, 'New Reviews', 'MAX_DISPLAY_NEW_REVIEWS', '6', 'Maximum number of new reviews to display', 3, 9, NULL, '2010-09-22 09:41:17', NULL, NULL),
(45, 'Selection of Random Reviews', 'MAX_RANDOM_SELECT_REVIEWS', '10', 'How many records to select from to choose one random product review', 3, 10, NULL, '2010-09-22 09:41:17', NULL, NULL),
(46, 'Selection of Random New Products', 'MAX_RANDOM_SELECT_NEW', '10', 'How many records to select from to choose one random new product to display', 3, 11, NULL, '2010-09-22 09:41:17', NULL, NULL),
(47, 'Selection of Products on Special', 'MAX_RANDOM_SELECT_SPECIALS', '10', 'How many records to select from to choose one random product special to display', 3, 12, NULL, '2010-09-22 09:41:17', NULL, NULL),
(48, 'Categories To List Per Row', 'MAX_DISPLAY_CATEGORIES_PER_ROW', '3', 'How many categories to list per row', 3, 13, NULL, '2010-09-22 09:41:17', NULL, NULL),
(49, 'New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '10', 'Maximum number of new products to display in new products page', 3, 14, NULL, '2010-09-22 09:41:17', NULL, NULL),
(50, 'Best Sellers', 'MAX_DISPLAY_BESTSELLERS', '10', 'Maximum number of best sellers to display', 3, 15, NULL, '2010-09-22 09:41:17', NULL, NULL),
(51, 'Also Purchased', 'MAX_DISPLAY_ALSO_PURCHASED', '6', 'Maximum number of products to display in the ''This Customer Also Purchased'' box', 3, 16, NULL, '2010-09-22 09:41:17', NULL, NULL),
(52, 'Customer Order History Box', 'MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX', '6', 'Maximum number of products to display in the customer order history box', 3, 17, NULL, '2010-09-22 09:41:17', NULL, NULL),
(53, 'Order History', 'MAX_DISPLAY_ORDER_HISTORY', '10', 'Maximum number of orders to display in the order history page', 3, 18, NULL, '2010-09-22 09:41:17', NULL, NULL),
(54, 'Product Quantities In Shopping Cart', 'MAX_QTY_IN_CART', '99', 'Maximum number of product quantities that can be added to the shopping cart (0 for no limit)', 3, 19, NULL, '2010-09-22 09:41:17', NULL, NULL),
(55, 'Small Image Width', 'SMALL_IMAGE_WIDTH', '100', 'The pixel width of small images', 4, 1, NULL, '2010-09-22 09:41:17', NULL, NULL),
(56, 'Small Image Height', 'SMALL_IMAGE_HEIGHT', '80', 'The pixel height of small images', 4, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(57, 'Heading Image Width', 'HEADING_IMAGE_WIDTH', '57', 'The pixel width of heading images', 4, 3, NULL, '2010-09-22 09:41:17', NULL, NULL),
(58, 'Heading Image Height', 'HEADING_IMAGE_HEIGHT', '40', 'The pixel height of heading images', 4, 4, NULL, '2010-09-22 09:41:17', NULL, NULL),
(59, 'Subcategory Image Width', 'SUBCATEGORY_IMAGE_WIDTH', '100', 'The pixel width of subcategory images', 4, 5, NULL, '2010-09-22 09:41:17', NULL, NULL),
(60, 'Subcategory Image Height', 'SUBCATEGORY_IMAGE_HEIGHT', '57', 'The pixel height of subcategory images', 4, 6, NULL, '2010-09-22 09:41:17', NULL, NULL),
(61, 'Calculate Image Size', 'CONFIG_CALCULATE_IMAGE_SIZE', 'true', 'Calculate the size of images?', 4, 7, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(62, 'Image Required', 'IMAGE_REQUIRED', 'true', 'Enable to display broken images. Good for development.', 4, 8, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(63, 'Gender', 'ACCOUNT_GENDER', 'false', 'Display gender in the customers account', 5, 1, '2010-09-22 13:07:28', '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(64, 'Date of Birth', 'ACCOUNT_DOB', 'false', 'Display date of birth in the customers account', 5, 2, '2010-09-22 13:07:34', '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(65, 'Company', 'ACCOUNT_COMPANY', 'false', 'Display company in the customers account', 5, 3, '2010-09-22 13:07:40', '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(66, 'Suburb', 'ACCOUNT_SUBURB', 'false', 'Display suburb in the customers account', 5, 4, '2010-09-22 13:07:46', '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(67, 'State', 'ACCOUNT_STATE', 'false', 'Display state in the customers account', 5, 5, '2010-09-22 13:07:52', '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(68, 'Installed Modules', 'MODULE_PAYMENT_INSTALLED', 'cod.php;paypal_express.php', 'List of payment module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: cod.php;paypal_express.php)', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(69, 'Installed Modules', 'MODULE_ORDER_TOTAL_INSTALLED', 'ot_subtotal.php;ot_shipping.php;ot_tax.php;ot_total.php', 'List of order_total module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php)', 6, 0, '2010-12-15 09:16:49', '2010-09-22 09:41:17', NULL, NULL),
(70, 'Installed Modules', 'MODULE_SHIPPING_INSTALLED', 'flat.php', 'List of shipping module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ups.php;flat.php;item.php)', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(71, 'Installed Modules', 'MODULE_ACTION_RECORDER_INSTALLED', 'ar_admin_login.php;ar_contact_us.php;ar_tell_a_friend.php', 'List of action recorder module filenames separated by a semi-colon. This is automatically updated. No need to edit.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(72, 'Installed Modules', 'MODULE_SOCIAL_BOOKMARKS_INSTALLED', 'sb_email.php;sb_facebook.php;sb_twitter.php;sb_google_buzz.php;sb_digg.php', 'List of social bookmark module filenames separated by a semi-colon. This is automatically updated. No need to edit.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(73, 'Enable Cash On Delivery Module', 'MODULE_PAYMENT_COD_STATUS', 'True', 'Do you want to accept Cash On Delevery payments?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(74, 'Payment Zone', 'MODULE_PAYMENT_COD_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, NULL, '2010-09-22 09:41:17', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
(75, 'Sort order of display.', 'MODULE_PAYMENT_COD_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(76, 'Set Order Status', 'MODULE_PAYMENT_COD_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 0, NULL, '2010-09-22 09:41:17', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(77, 'Enable Flat Shipping', 'MODULE_SHIPPING_FLAT_STATUS', 'True', 'Do you want to offer flat rate shipping?', 6, 0, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(78, 'Shipping Cost', 'MODULE_SHIPPING_FLAT_COST', '0.00', 'The shipping cost for all orders using this shipping method.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(79, 'Tax Class', 'MODULE_SHIPPING_FLAT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, NULL, '2010-09-22 09:41:17', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes('),
(80, 'Shipping Zone', 'MODULE_SHIPPING_FLAT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, NULL, '2010-09-22 09:41:17', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
(81, 'Sort Order', 'MODULE_SHIPPING_FLAT_SORT_ORDER', '0', 'Sort order of display.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(82, 'Default Currency', 'DEFAULT_CURRENCY', 'THB', 'Default Currency', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(83, 'Default Language', 'DEFAULT_LANGUAGE', 'en', 'Default Language', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(84, 'Default Order Status For New Orders', 'DEFAULT_ORDERS_STATUS_ID', '1', 'When a new order is created, this order status will be assigned to it.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(85, 'Display Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'true', 'Do you want to display the order shipping cost?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(86, 'Sort Order', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '2', 'Sort order of display.', 6, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(87, 'Allow Free Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING', 'false', 'Do you want to allow free shipping?', 6, 3, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(88, 'Free Shipping For Orders Over', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', '50', 'Provide free shipping for orders over the set amount.', 6, 4, NULL, '2010-09-22 09:41:17', 'currencies->format', NULL),
(89, 'Provide Free Shipping For Orders Made', 'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION', 'national', 'Provide free shipping for orders sent to the set destination.', 6, 5, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''national'', ''international'', ''both''), '),
(90, 'Display Sub-Total', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', 'Do you want to display the order sub-total cost?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(91, 'Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1', 'Sort order of display.', 6, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(92, 'Display Tax', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'Do you want to display the order tax value?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(93, 'Sort Order', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '3', 'Sort order of display.', 6, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(94, 'Display Total', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', 'Do you want to display the total order value?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(95, 'Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '4', 'Sort order of display.', 6, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(96, 'Minimum Minutes Per E-Mail', 'MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES', '15', 'Minimum number of minutes to allow 1 e-mail to be sent (eg, 15 for 1 e-mail every 15 minutes)', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(97, 'Minimum Minutes Per E-Mail', 'MODULE_ACTION_RECORDER_TELL_A_FRIEND_EMAIL_MINUTES', '15', 'Minimum number of minutes to allow 1 e-mail to be sent (eg, 15 for 1 e-mail every 15 minutes)', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(98, 'Allowed Minutes', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES', '5', 'Number of minutes to allow login attempts to occur.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(99, 'Allowed Attempts', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS', '3', 'Number of login attempts to allow within the specified period.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(100, 'Enable E-Mail Module', 'MODULE_SOCIAL_BOOKMARKS_EMAIL_STATUS', 'True', 'Do you want to allow products to be shared through e-mail?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(101, 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_EMAIL_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(102, 'Enable Facebook Module', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_STATUS', 'True', 'Do you want to allow products to be shared through Facebook?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(103, 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(104, 'Enable Twitter Module', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_STATUS', 'True', 'Do you want to allow products to be shared through Twitter?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(105, 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_SORT_ORDER', '30', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(106, 'Enable Google Buzz Module', 'MODULE_SOCIAL_BOOKMARKS_GOOGLE_BUZZ_STATUS', 'True', 'Do you want to allow products to be shared through Google Buzz?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(107, 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_GOOGLE_BUZZ_SORT_ORDER', '40', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(108, 'Enable Digg Module', 'MODULE_SOCIAL_BOOKMARKS_DIGG_STATUS', 'True', 'Do you want to allow products to be shared through Digg?', 6, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(109, 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_DIGG_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(110, 'Country of Origin', 'SHIPPING_ORIGIN_COUNTRY', '223', 'Select the country of origin to be used in shipping quotes.', 7, 1, NULL, '2010-09-22 09:41:17', 'tep_get_country_name', 'tep_cfg_pull_down_country_list('),
(111, 'Postal Code', 'SHIPPING_ORIGIN_ZIP', 'NONE', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.', 7, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(112, 'Enter the Maximum Package Weight you will ship', 'SHIPPING_MAX_WEIGHT', '50', 'Carriers have a max weight limit for a single package. This is a common one for all.', 7, 3, NULL, '2010-09-22 09:41:17', NULL, NULL),
(113, 'Package Tare weight.', 'SHIPPING_BOX_WEIGHT', '3', 'What is the weight of typical packaging of small to medium packages?', 7, 4, NULL, '2010-09-22 09:41:17', NULL, NULL),
(114, 'Larger packages - percentage increase.', 'SHIPPING_BOX_PADDING', '10', 'For 10% enter 10', 7, 5, NULL, '2010-09-22 09:41:17', NULL, NULL),
(115, 'Display Product Image', 'PRODUCT_LIST_IMAGE', '1', 'Do you want to display the Product Image?', 8, 1, NULL, '2010-09-22 09:41:17', NULL, NULL),
(116, 'Display Product Manufaturer Name', 'PRODUCT_LIST_MANUFACTURER', '0', 'Do you want to display the Product Manufacturer Name?', 8, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(117, 'Display Product Model', 'PRODUCT_LIST_MODEL', '0', 'Do you want to display the Product Model?', 8, 3, NULL, '2010-09-22 09:41:17', NULL, NULL),
(118, 'Display Product Name', 'PRODUCT_LIST_NAME', '2', 'Do you want to display the Product Name?', 8, 4, NULL, '2010-09-22 09:41:17', NULL, NULL),
(119, 'Display Product Price', 'PRODUCT_LIST_PRICE', '3', 'Do you want to display the Product Price', 8, 5, NULL, '2010-09-22 09:41:17', NULL, NULL),
(120, 'Display Product Quantity', 'PRODUCT_LIST_QUANTITY', '0', 'Do you want to display the Product Quantity?', 8, 6, NULL, '2010-09-22 09:41:17', NULL, NULL),
(121, 'Display Product Weight', 'PRODUCT_LIST_WEIGHT', '0', 'Do you want to display the Product Weight?', 8, 7, NULL, '2010-09-22 09:41:17', NULL, NULL),
(122, 'Display Buy Now column', 'PRODUCT_LIST_BUY_NOW', '4', 'Do you want to display the Buy Now column?', 8, 8, NULL, '2010-09-22 09:41:17', NULL, NULL),
(123, 'Display Category/Manufacturer Filter (0=disable; 1=enable)', 'PRODUCT_LIST_FILTER', '1', 'Do you want to display the Category/Manufacturer Filter?', 8, 9, NULL, '2010-09-22 09:41:17', NULL, NULL),
(124, 'Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 'PREV_NEXT_BAR_LOCATION', '2', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 8, 10, NULL, '2010-09-22 09:41:17', NULL, NULL),
(125, 'Check stock level', 'STOCK_CHECK', 'true', 'Check to see if sufficent stock is available', 9, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(126, 'Subtract stock', 'STOCK_LIMITED', 'true', 'Subtract product in stock by product orders', 9, 2, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(127, 'Allow Checkout', 'STOCK_ALLOW_CHECKOUT', 'true', 'Allow customer to checkout even if there is insufficient stock', 9, 3, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(128, 'Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '***', 'Display something on screen so customer can see which product has insufficient stock', 9, 4, NULL, '2010-09-22 09:41:17', NULL, NULL),
(129, 'Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', 9, 5, NULL, '2010-09-22 09:41:17', NULL, NULL),
(130, 'Store Page Parse Time', 'STORE_PAGE_PARSE_TIME', 'false', 'Store the time it takes to parse a page', 10, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(131, 'Log Destination', 'STORE_PAGE_PARSE_TIME_LOG', '/var/log/www/tep/page_parse_time.log', 'Directory and filename of the page parse time log', 10, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(132, 'Log Date Format', 'STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S', 'The date format', 10, 3, NULL, '2010-09-22 09:41:17', NULL, NULL),
(133, 'Display The Page Parse Time', 'DISPLAY_PAGE_PARSE_TIME', 'true', 'Display the page parse time (store page parse time must be enabled)', 10, 4, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(134, 'Store Database Queries', 'STORE_DB_TRANSACTIONS', 'false', 'Store the database queries in the page parse time log', 10, 5, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(135, 'Use Cache', 'USE_CACHE', 'false', 'Use caching features', 11, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(136, 'Cache Directory', 'DIR_FS_CACHE', '/home/sacarlson/osc2/oscommerce2/catalog/includes/work/', 'The directory where the cached files are saved', 11, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(137, 'E-Mail Transport Method', 'EMAIL_TRANSPORT', 'sendmail', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.', 12, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''sendmail'', ''smtp''),'),
(138, 'E-Mail Linefeeds', 'EMAIL_LINEFEED', 'LF', 'Defines the character sequence used to separate mail headers.', 12, 2, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''LF'', ''CRLF''),'),
(139, 'Use MIME HTML When Sending Emails', 'EMAIL_USE_HTML', 'false', 'Send e-mails in HTML format', 12, 3, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''),'),
(140, 'Verify E-Mail Addresses Through DNS', 'ENTRY_EMAIL_ADDRESS_CHECK', 'false', 'Verify e-mail address through a DNS server', 12, 4, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(141, 'Send E-Mails', 'SEND_EMAILS', 'true', 'Send out e-mails', 12, 5, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(142, 'Enable download', 'DOWNLOAD_ENABLED', 'false', 'Enable the products download functions.', 13, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(143, 'Download by redirect', 'DOWNLOAD_BY_REDIRECT', 'false', 'Use browser redirection for download. Disable on non-Unix systems.', 13, 2, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(144, 'Expiry delay (days)', 'DOWNLOAD_MAX_DAYS', '7', 'Set number of days before the download link expires. 0 means no limit.', 13, 3, NULL, '2010-09-22 09:41:17', NULL, ''),
(145, 'Maximum number of downloads', 'DOWNLOAD_MAX_COUNT', '5', 'Set the maximum number of downloads. 0 means no download authorized.', 13, 4, NULL, '2010-09-22 09:41:17', NULL, ''),
(146, 'Enable GZip Compression', 'GZIP_COMPRESSION', 'false', 'Enable HTTP GZip compression.', 14, 1, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
(147, 'Compression Level', 'GZIP_LEVEL', '5', 'Use this compression level 0-9 (0 = minimum, 9 = maximum).', 14, 2, NULL, '2010-09-22 09:41:17', NULL, NULL),
(148, 'Session Directory', 'SESSION_WRITE_DIRECTORY', '/home/sacarlson/osc2/oscommerce2/catalog/includes/work/', 'If sessions are file based, store them in this directory.', 15, 1, NULL, '2010-09-22 09:41:17', NULL, NULL),
(149, 'Force Cookie Use', 'SESSION_FORCE_COOKIE_USE', 'False', 'Force the use of sessions when cookies are only enabled.', 15, 2, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(150, 'Check SSL Session ID', 'SESSION_CHECK_SSL_SESSION_ID', 'False', 'Validate the SSL_SESSION_ID on every secure HTTPS page request.', 15, 3, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(151, 'Check User Agent', 'SESSION_CHECK_USER_AGENT', 'False', 'Validate the clients browser user agent on every page request.', 15, 4, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(152, 'Check IP Address', 'SESSION_CHECK_IP_ADDRESS', 'False', 'Validate the clients IP address on every page request.', 15, 5, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(153, 'Prevent Spider Sessions', 'SESSION_BLOCK_SPIDERS', 'True', 'Prevent known spiders from starting a session.', 15, 6, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(154, 'Recreate Session', 'SESSION_RECREATE', 'True', 'Recreate the session to generate a new session ID when the customer logs on or creates an account (PHP >=4.1 needed).', 15, 7, NULL, '2010-09-22 09:41:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(155, 'Last Update Check Time', 'LAST_UPDATE_CHECK_TIME', '', 'Last time a check for new versions of osCommerce was run', 6, 0, NULL, '2010-09-22 09:41:17', NULL, NULL),
(156, 'Enable PayPal Express Checkout', 'MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS', 'True', 'Do you want to accept PayPal Express Checkout payments?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(157, 'Seller Account', 'MODULE_PAYMENT_PAYPAL_EXPRESS_SELLER_ACCOUNT', 'sacarlson_2000@yahoo.com', 'The email address of the seller account if no API credentials has been setup.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(158, 'API Username', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_USERNAME', '', 'The username to use for the PayPal API service', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(159, 'API Password', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_PASSWORD', '', 'The password to use for the PayPal API service', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(160, 'API Signature', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_SIGNATURE', '', 'The signature to use for the PayPal API service', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(161, 'Transaction Server', 'MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_SERVER', 'Live', 'Use the live or testing (sandbox) gateway server to process transactions?', 6, 0, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''Live'', ''Sandbox''), '),
(162, 'Transaction Method', 'MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_METHOD', 'Sale', 'The processing method to use for each transaction.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''Authorization'', ''Sale''), '),
(163, 'PayPal Account Optional', 'MODULE_PAYMENT_PAYPAL_EXPRESS_ACCOUNT_OPTIONAL', 'False', 'This must also be enabled in your PayPal account, in Profile > Website Payment Preferences.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(164, 'PayPal Instant Update', 'MODULE_PAYMENT_PAYPAL_EXPRESS_INSTANT_UPDATE', 'True', 'Support PayPal shipping and tax calculations on the PayPal.com site during Express Checkout.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(165, 'PayPal Checkout Image', 'MODULE_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_IMAGE', 'Static', 'Use static or dynamic Express Checkout image buttons. Dynamic images are used with PayPal campaigns.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''Static'', ''Dynamic''), '),
(166, 'Debug E-Mail Address', 'MODULE_PAYMENT_PAYPAL_EXPRESS_DEBUG_EMAIL', '', 'All parameters of an invalid transaction will be sent to this email address.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(167, 'Payment Zone', 'MODULE_PAYMENT_PAYPAL_EXPRESS_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, NULL, '2010-09-22 09:41:18', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
(168, 'Sort order of display.', 'MODULE_PAYMENT_PAYPAL_EXPRESS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(169, 'Set Order Status', 'MODULE_PAYMENT_PAYPAL_EXPRESS_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 0, NULL, '2010-09-22 09:41:18', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(170, 'PayPal Transactions Order Status Level', 'MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTIONS_ORDER_STATUS_ID', '4', 'Include PayPal transaction information in this order status level', 6, 0, NULL, '2010-09-22 09:41:18', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(171, 'cURL Program Location', 'MODULE_PAYMENT_PAYPAL_EXPRESS_CURL', '/usr/bin/curl', 'The location to the cURL program application.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(172, 'Installed Modules', 'MODULE_HEADER_TAGS_INSTALLED', 'ht_manufacturer_title.php;ht_category_title.php;ht_product_title.php', 'List of header tag module filenames separated by a semi-colon. This is automatically updated. No need to edit.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(173, 'Enable Category Title Module', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_STATUS', 'True', 'Do you want to allow category titles to be added to the page title?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(174, 'Sort Order', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(175, 'Enable Manufacturer Title Module', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_STATUS', 'True', 'Do you want to allow manufacturer titles to be added to the page title?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(176, 'Sort Order', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(177, 'Enable Product Title Module', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_STATUS', 'True', 'Do you want to allow product titles to be added to the page title?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(178, 'Sort Order', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(179, 'Installed Modules', 'MODULE_ADMIN_DASHBOARD_INSTALLED', 'd_total_revenue.php;d_total_customers.php;d_orders.php;d_customers.php;d_admin_logins.php;d_security_checks.php;d_latest_news.php;d_latest_addons.php;d_version_check.php', 'List of Administration Tool Dashboard module filenames separated by a semi-colon. This is automatically updated. No need to edit.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(180, 'Enable Administrator Logins Module', 'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_STATUS', 'True', 'Do you want to show the latest administrator logins on the dashboard?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(181, 'Sort Order', 'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(182, 'Enable Customers Module', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_STATUS', 'True', 'Do you want to show the newest customers on the dashboard?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(183, 'Sort Order', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_SORT_ORDER', '400', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(184, 'Enable Latest Add-Ons Module', 'MODULE_ADMIN_DASHBOARD_LATEST_ADDONS_STATUS', 'True', 'Do you want to show the latest osCommerce Add-Ons on the dashboard?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(185, 'Sort Order', 'MODULE_ADMIN_DASHBOARD_LATEST_ADDONS_SORT_ORDER', '800', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(186, 'Enable Latest News Module', 'MODULE_ADMIN_DASHBOARD_LATEST_NEWS_STATUS', 'True', 'Do you want to show the latest osCommerce News on the dashboard?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(187, 'Sort Order', 'MODULE_ADMIN_DASHBOARD_LATEST_NEWS_SORT_ORDER', '700', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(188, 'Enable Orders Module', 'MODULE_ADMIN_DASHBOARD_ORDERS_STATUS', 'True', 'Do you want to show the latest orders on the dashboard?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(189, 'Sort Order', 'MODULE_ADMIN_DASHBOARD_ORDERS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(190, 'Enable Security Checks Module', 'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_STATUS', 'True', 'Do you want to run the security checks for this installation?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(191, 'Sort Order', 'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_SORT_ORDER', '600', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(192, 'Enable Total Customers Module', 'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_STATUS', 'True', 'Do you want to show the total customers chart on the dashboard?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(193, 'Sort Order', 'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(194, 'Enable Total Revenue Module', 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_STATUS', 'True', 'Do you want to show the total revenue chart on the dashboard?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(195, 'Sort Order', 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL),
(196, 'Enable Version Check Module', 'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_STATUS', 'True', 'Do you want to show the version check results on the dashboard?', 6, 1, NULL, '2010-09-22 09:41:18', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
(197, 'Sort Order', 'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_SORT_ORDER', '900', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2010-09-22 09:41:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `configuration_group`
--

CREATE TABLE IF NOT EXISTS `configuration_group` (
  `configuration_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_group_title` varchar(64) NOT NULL,
  `configuration_group_description` varchar(255) NOT NULL,
  `sort_order` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT '1',
  PRIMARY KEY (`configuration_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `configuration_group`
--

INSERT INTO `configuration_group` (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`) VALUES
(1, 'My Store', 'General information about my store', 1, 1),
(2, 'Minimum Values', 'The minimum values for functions / data', 2, 1),
(3, 'Maximum Values', 'The maximum values for functions / data', 3, 1),
(4, 'Images', 'Image parameters', 4, 1),
(5, 'Customer Details', 'Customer account configuration', 5, 1),
(6, 'Module Options', 'Hidden from configuration', 6, 0),
(7, 'Shipping/Packaging', 'Shipping options available at my store', 7, 1),
(8, 'Product Listing', 'Product Listing    configuration options', 8, 1),
(9, 'Stock', 'Stock configuration options', 9, 1),
(10, 'Logging', 'Logging configuration options', 10, 1),
(11, 'Cache', 'Caching configuration options', 11, 1),
(12, 'E-Mail Options', 'General setting for E-Mail transport and HTML E-Mails', 12, 1),
(13, 'Download', 'Downloadable products options', 13, 1),
(14, 'GZip Compression', 'GZip compression options', 14, 1),
(15, 'Sessions', 'Session options', 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `counter`
--

CREATE TABLE IF NOT EXISTS `counter` (
  `startdate` char(8) DEFAULT NULL,
  `counter` int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `counter`
--

INSERT INTO `counter` (`startdate`, `counter`) VALUES
('20100922', 7963);

-- --------------------------------------------------------

--
-- Table structure for table `counter_history`
--

CREATE TABLE IF NOT EXISTS `counter_history` (
  `month` char(8) DEFAULT NULL,
  `counter` int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `counter_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `countries_id` int(11) NOT NULL AUTO_INCREMENT,
  `countries_name` varchar(255) NOT NULL,
  `countries_iso_code_2` char(2) NOT NULL,
  `countries_iso_code_3` char(3) NOT NULL,
  `address_format_id` int(11) NOT NULL,
  PRIMARY KEY (`countries_id`),
  KEY `IDX_COUNTRIES_NAME` (`countries_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=240 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`countries_id`, `countries_name`, `countries_iso_code_2`, `countries_iso_code_3`, `address_format_id`) VALUES
(1, 'Afghanistan', 'AF', 'AFG', 1),
(2, 'Albania', 'AL', 'ALB', 1),
(3, 'Algeria', 'DZ', 'DZA', 1),
(4, 'American Samoa', 'AS', 'ASM', 1),
(5, 'Andorra', 'AD', 'AND', 1),
(6, 'Angola', 'AO', 'AGO', 1),
(7, 'Anguilla', 'AI', 'AIA', 1),
(8, 'Antarctica', 'AQ', 'ATA', 1),
(9, 'Antigua and Barbuda', 'AG', 'ATG', 1),
(10, 'Argentina', 'AR', 'ARG', 1),
(11, 'Armenia', 'AM', 'ARM', 1),
(12, 'Aruba', 'AW', 'ABW', 1),
(13, 'Australia', 'AU', 'AUS', 1),
(14, 'Austria', 'AT', 'AUT', 5),
(15, 'Azerbaijan', 'AZ', 'AZE', 1),
(16, 'Bahamas', 'BS', 'BHS', 1),
(17, 'Bahrain', 'BH', 'BHR', 1),
(18, 'Bangladesh', 'BD', 'BGD', 1),
(19, 'Barbados', 'BB', 'BRB', 1),
(20, 'Belarus', 'BY', 'BLR', 1),
(21, 'Belgium', 'BE', 'BEL', 1),
(22, 'Belize', 'BZ', 'BLZ', 1),
(23, 'Benin', 'BJ', 'BEN', 1),
(24, 'Bermuda', 'BM', 'BMU', 1),
(25, 'Bhutan', 'BT', 'BTN', 1),
(26, 'Bolivia', 'BO', 'BOL', 1),
(27, 'Bosnia and Herzegowina', 'BA', 'BIH', 1),
(28, 'Botswana', 'BW', 'BWA', 1),
(29, 'Bouvet Island', 'BV', 'BVT', 1),
(30, 'Brazil', 'BR', 'BRA', 1),
(31, 'British Indian Ocean Territory', 'IO', 'IOT', 1),
(32, 'Brunei Darussalam', 'BN', 'BRN', 1),
(33, 'Bulgaria', 'BG', 'BGR', 1),
(34, 'Burkina Faso', 'BF', 'BFA', 1),
(35, 'Burundi', 'BI', 'BDI', 1),
(36, 'Cambodia', 'KH', 'KHM', 1),
(37, 'Cameroon', 'CM', 'CMR', 1),
(38, 'Canada', 'CA', 'CAN', 1),
(39, 'Cape Verde', 'CV', 'CPV', 1),
(40, 'Cayman Islands', 'KY', 'CYM', 1),
(41, 'Central African Republic', 'CF', 'CAF', 1),
(42, 'Chad', 'TD', 'TCD', 1),
(43, 'Chile', 'CL', 'CHL', 1),
(44, 'China', 'CN', 'CHN', 1),
(45, 'Christmas Island', 'CX', 'CXR', 1),
(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', 1),
(47, 'Colombia', 'CO', 'COL', 1),
(48, 'Comoros', 'KM', 'COM', 1),
(49, 'Congo', 'CG', 'COG', 1),
(50, 'Cook Islands', 'CK', 'COK', 1),
(51, 'Costa Rica', 'CR', 'CRI', 1),
(52, 'Cote D''Ivoire', 'CI', 'CIV', 1),
(53, 'Croatia', 'HR', 'HRV', 1),
(54, 'Cuba', 'CU', 'CUB', 1),
(55, 'Cyprus', 'CY', 'CYP', 1),
(56, 'Czech Republic', 'CZ', 'CZE', 1),
(57, 'Denmark', 'DK', 'DNK', 1),
(58, 'Djibouti', 'DJ', 'DJI', 1),
(59, 'Dominica', 'DM', 'DMA', 1),
(60, 'Dominican Republic', 'DO', 'DOM', 1),
(61, 'East Timor', 'TP', 'TMP', 1),
(62, 'Ecuador', 'EC', 'ECU', 1),
(63, 'Egypt', 'EG', 'EGY', 1),
(64, 'El Salvador', 'SV', 'SLV', 1),
(65, 'Equatorial Guinea', 'GQ', 'GNQ', 1),
(66, 'Eritrea', 'ER', 'ERI', 1),
(67, 'Estonia', 'EE', 'EST', 1),
(68, 'Ethiopia', 'ET', 'ETH', 1),
(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', 1),
(70, 'Faroe Islands', 'FO', 'FRO', 1),
(71, 'Fiji', 'FJ', 'FJI', 1),
(72, 'Finland', 'FI', 'FIN', 1),
(73, 'France', 'FR', 'FRA', 1),
(74, 'France, Metropolitan', 'FX', 'FXX', 1),
(75, 'French Guiana', 'GF', 'GUF', 1),
(76, 'French Polynesia', 'PF', 'PYF', 1),
(77, 'French Southern Territories', 'TF', 'ATF', 1),
(78, 'Gabon', 'GA', 'GAB', 1),
(79, 'Gambia', 'GM', 'GMB', 1),
(80, 'Georgia', 'GE', 'GEO', 1),
(81, 'Germany', 'DE', 'DEU', 5),
(82, 'Ghana', 'GH', 'GHA', 1),
(83, 'Gibraltar', 'GI', 'GIB', 1),
(84, 'Greece', 'GR', 'GRC', 1),
(85, 'Greenland', 'GL', 'GRL', 1),
(86, 'Grenada', 'GD', 'GRD', 1),
(87, 'Guadeloupe', 'GP', 'GLP', 1),
(88, 'Guam', 'GU', 'GUM', 1),
(89, 'Guatemala', 'GT', 'GTM', 1),
(90, 'Guinea', 'GN', 'GIN', 1),
(91, 'Guinea-bissau', 'GW', 'GNB', 1),
(92, 'Guyana', 'GY', 'GUY', 1),
(93, 'Haiti', 'HT', 'HTI', 1),
(94, 'Heard and Mc Donald Islands', 'HM', 'HMD', 1),
(95, 'Honduras', 'HN', 'HND', 1),
(96, 'Hong Kong', 'HK', 'HKG', 1),
(97, 'Hungary', 'HU', 'HUN', 1),
(98, 'Iceland', 'IS', 'ISL', 1),
(99, 'India', 'IN', 'IND', 1),
(100, 'Indonesia', 'ID', 'IDN', 1),
(101, 'Iran (Islamic Republic of)', 'IR', 'IRN', 1),
(102, 'Iraq', 'IQ', 'IRQ', 1),
(103, 'Ireland', 'IE', 'IRL', 1),
(104, 'Israel', 'IL', 'ISR', 1),
(105, 'Italy', 'IT', 'ITA', 1),
(106, 'Jamaica', 'JM', 'JAM', 1),
(107, 'Japan', 'JP', 'JPN', 1),
(108, 'Jordan', 'JO', 'JOR', 1),
(109, 'Kazakhstan', 'KZ', 'KAZ', 1),
(110, 'Kenya', 'KE', 'KEN', 1),
(111, 'Kiribati', 'KI', 'KIR', 1),
(112, 'Korea, Democratic People''s Republic of', 'KP', 'PRK', 1),
(113, 'Korea, Republic of', 'KR', 'KOR', 1),
(114, 'Kuwait', 'KW', 'KWT', 1),
(115, 'Kyrgyzstan', 'KG', 'KGZ', 1),
(116, 'Lao People''s Democratic Republic', 'LA', 'LAO', 1),
(117, 'Latvia', 'LV', 'LVA', 1),
(118, 'Lebanon', 'LB', 'LBN', 1),
(119, 'Lesotho', 'LS', 'LSO', 1),
(120, 'Liberia', 'LR', 'LBR', 1),
(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', 1),
(122, 'Liechtenstein', 'LI', 'LIE', 1),
(123, 'Lithuania', 'LT', 'LTU', 1),
(124, 'Luxembourg', 'LU', 'LUX', 1),
(125, 'Macau', 'MO', 'MAC', 1),
(126, 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MKD', 1),
(127, 'Madagascar', 'MG', 'MDG', 1),
(128, 'Malawi', 'MW', 'MWI', 1),
(129, 'Malaysia', 'MY', 'MYS', 1),
(130, 'Maldives', 'MV', 'MDV', 1),
(131, 'Mali', 'ML', 'MLI', 1),
(132, 'Malta', 'MT', 'MLT', 1),
(133, 'Marshall Islands', 'MH', 'MHL', 1),
(134, 'Martinique', 'MQ', 'MTQ', 1),
(135, 'Mauritania', 'MR', 'MRT', 1),
(136, 'Mauritius', 'MU', 'MUS', 1),
(137, 'Mayotte', 'YT', 'MYT', 1),
(138, 'Mexico', 'MX', 'MEX', 1),
(139, 'Micronesia, Federated States of', 'FM', 'FSM', 1),
(140, 'Moldova, Republic of', 'MD', 'MDA', 1),
(141, 'Monaco', 'MC', 'MCO', 1),
(142, 'Mongolia', 'MN', 'MNG', 1),
(143, 'Montserrat', 'MS', 'MSR', 1),
(144, 'Morocco', 'MA', 'MAR', 1),
(145, 'Mozambique', 'MZ', 'MOZ', 1),
(146, 'Myanmar', 'MM', 'MMR', 1),
(147, 'Namibia', 'NA', 'NAM', 1),
(148, 'Nauru', 'NR', 'NRU', 1),
(149, 'Nepal', 'NP', 'NPL', 1),
(150, 'Netherlands', 'NL', 'NLD', 1),
(151, 'Netherlands Antilles', 'AN', 'ANT', 1),
(152, 'New Caledonia', 'NC', 'NCL', 1),
(153, 'New Zealand', 'NZ', 'NZL', 1),
(154, 'Nicaragua', 'NI', 'NIC', 1),
(155, 'Niger', 'NE', 'NER', 1),
(156, 'Nigeria', 'NG', 'NGA', 1),
(157, 'Niue', 'NU', 'NIU', 1),
(158, 'Norfolk Island', 'NF', 'NFK', 1),
(159, 'Northern Mariana Islands', 'MP', 'MNP', 1),
(160, 'Norway', 'NO', 'NOR', 1),
(161, 'Oman', 'OM', 'OMN', 1),
(162, 'Pakistan', 'PK', 'PAK', 1),
(163, 'Palau', 'PW', 'PLW', 1),
(164, 'Panama', 'PA', 'PAN', 1),
(165, 'Papua New Guinea', 'PG', 'PNG', 1),
(166, 'Paraguay', 'PY', 'PRY', 1),
(167, 'Peru', 'PE', 'PER', 1),
(168, 'Philippines', 'PH', 'PHL', 1),
(169, 'Pitcairn', 'PN', 'PCN', 1),
(170, 'Poland', 'PL', 'POL', 1),
(171, 'Portugal', 'PT', 'PRT', 1),
(172, 'Puerto Rico', 'PR', 'PRI', 1),
(173, 'Qatar', 'QA', 'QAT', 1),
(174, 'Reunion', 'RE', 'REU', 1),
(175, 'Romania', 'RO', 'ROM', 1),
(176, 'Russian Federation', 'RU', 'RUS', 1),
(177, 'Rwanda', 'RW', 'RWA', 1),
(178, 'Saint Kitts and Nevis', 'KN', 'KNA', 1),
(179, 'Saint Lucia', 'LC', 'LCA', 1),
(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 1),
(181, 'Samoa', 'WS', 'WSM', 1),
(182, 'San Marino', 'SM', 'SMR', 1),
(183, 'Sao Tome and Principe', 'ST', 'STP', 1),
(184, 'Saudi Arabia', 'SA', 'SAU', 1),
(185, 'Senegal', 'SN', 'SEN', 1),
(186, 'Seychelles', 'SC', 'SYC', 1),
(187, 'Sierra Leone', 'SL', 'SLE', 1),
(188, 'Singapore', 'SG', 'SGP', 4),
(189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 1),
(190, 'Slovenia', 'SI', 'SVN', 1),
(191, 'Solomon Islands', 'SB', 'SLB', 1),
(192, 'Somalia', 'SO', 'SOM', 1),
(193, 'South Africa', 'ZA', 'ZAF', 1),
(194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', 1),
(195, 'Spain', 'ES', 'ESP', 3),
(196, 'Sri Lanka', 'LK', 'LKA', 1),
(197, 'St. Helena', 'SH', 'SHN', 1),
(198, 'St. Pierre and Miquelon', 'PM', 'SPM', 1),
(199, 'Sudan', 'SD', 'SDN', 1),
(200, 'Suriname', 'SR', 'SUR', 1),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', 1),
(202, 'Swaziland', 'SZ', 'SWZ', 1),
(203, 'Sweden', 'SE', 'SWE', 1),
(204, 'Switzerland', 'CH', 'CHE', 1),
(205, 'Syrian Arab Republic', 'SY', 'SYR', 1),
(206, 'Taiwan', 'TW', 'TWN', 1),
(207, 'Tajikistan', 'TJ', 'TJK', 1),
(208, 'Tanzania, United Republic of', 'TZ', 'TZA', 1),
(209, 'Thailand', 'TH', 'THA', 1),
(210, 'Togo', 'TG', 'TGO', 1),
(211, 'Tokelau', 'TK', 'TKL', 1),
(212, 'Tonga', 'TO', 'TON', 1),
(213, 'Trinidad and Tobago', 'TT', 'TTO', 1),
(214, 'Tunisia', 'TN', 'TUN', 1),
(215, 'Turkey', 'TR', 'TUR', 1),
(216, 'Turkmenistan', 'TM', 'TKM', 1),
(217, 'Turks and Caicos Islands', 'TC', 'TCA', 1),
(218, 'Tuvalu', 'TV', 'TUV', 1),
(219, 'Uganda', 'UG', 'UGA', 1),
(220, 'Ukraine', 'UA', 'UKR', 1),
(221, 'United Arab Emirates', 'AE', 'ARE', 1),
(222, 'United Kingdom', 'GB', 'GBR', 1),
(223, 'United States', 'US', 'USA', 2),
(224, 'United States Minor Outlying Islands', 'UM', 'UMI', 1),
(225, 'Uruguay', 'UY', 'URY', 1),
(226, 'Uzbekistan', 'UZ', 'UZB', 1),
(227, 'Vanuatu', 'VU', 'VUT', 1),
(228, 'Vatican City State (Holy See)', 'VA', 'VAT', 1),
(229, 'Venezuela', 'VE', 'VEN', 1),
(230, 'Viet Nam', 'VN', 'VNM', 1),
(231, 'Virgin Islands (British)', 'VG', 'VGB', 1),
(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', 1),
(233, 'Wallis and Futuna Islands', 'WF', 'WLF', 1),
(234, 'Western Sahara', 'EH', 'ESH', 1),
(235, 'Yemen', 'YE', 'YEM', 1),
(236, 'Yugoslavia', 'YU', 'YUG', 1),
(237, 'Zaire', 'ZR', 'ZAR', 1),
(238, 'Zambia', 'ZM', 'ZMB', 1),
(239, 'Zimbabwe', 'ZW', 'ZWE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `currencies_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `code` char(3) NOT NULL,
  `symbol_left` varchar(12) DEFAULT NULL,
  `symbol_right` varchar(12) DEFAULT NULL,
  `decimal_point` char(1) DEFAULT NULL,
  `thousands_point` char(1) DEFAULT NULL,
  `decimal_places` char(1) DEFAULT NULL,
  `value` float(13,8) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`currencies_id`),
  KEY `idx_currencies_code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currencies_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_point`, `thousands_point`, `decimal_places`, `value`, `last_updated`) VALUES
(1, 'U.S. Dollar', 'USD', '$', '', '.', ',', '2', 0.03267000, '2010-09-22 12:34:26'),
(2, 'Euro', 'EUR', '', 'â‚¬', '.', ',', '2', 0.02453860, '2010-09-22 12:34:34'),
(3, 'Thai Baht', 'THB', '', 'à¸¿', '.', ',', '1', 1.00000000, '2010-09-22 12:34:38');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `customers_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_gender` char(1) DEFAULT NULL,
  `customers_firstname` varchar(255) NOT NULL,
  `customers_lastname` varchar(255) NOT NULL,
  `customers_dob` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `customers_email_address` varchar(255) NOT NULL,
  `customers_default_address_id` int(11) DEFAULT NULL,
  `customers_telephone` varchar(255) NOT NULL,
  `customers_fax` varchar(255) DEFAULT NULL,
  `customers_password` varchar(60) NOT NULL,
  `customers_newsletter` char(1) DEFAULT NULL,
  `customers_date_account_expires` datetime DEFAULT NULL,
  `customers_promotion_received` datetime DEFAULT NULL,
  `customers_bytes_ordered` bigint(20) NOT NULL DEFAULT '0',
  `customers_bytes_start` bigint(20) NOT NULL DEFAULT '0',
  `customers_ip_address` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`customers_id`),
  KEY `idx_customers_email_address` (`customers_email_address`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customers_id`, `customers_gender`, `customers_firstname`, `customers_lastname`, `customers_dob`, `customers_email_address`, `customers_default_address_id`, `customers_telephone`, `customers_fax`, `customers_password`, `customers_newsletter`, `customers_date_account_expires`, `customers_promotion_received`, `customers_bytes_ordered`, `customers_bytes_start`, `customers_ip_address`) VALUES
(3, NULL, 'test2', 'test', '0000-00-00 00:00:00', 'sacarlson5@ipipi.com', 3, '1324134', '', 'somepassword', '', '2011-10-31 14:29:08', '2010-09-22 21:29:19', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers_basket`
--

CREATE TABLE IF NOT EXISTS `customers_basket` (
  `customers_basket_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL,
  `products_id` tinytext NOT NULL,
  `customers_basket_quantity` int(2) NOT NULL,
  `final_price` decimal(15,4) DEFAULT NULL,
  `customers_basket_date_added` char(8) DEFAULT NULL,
  PRIMARY KEY (`customers_basket_id`),
  KEY `idx_customers_basket_customers_id` (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=90 ;

--
-- Dumping data for table `customers_basket`
--

INSERT INTO `customers_basket` (`customers_basket_id`, `customers_id`, `products_id`, `customers_basket_quantity`, `final_price`, `customers_basket_date_added`) VALUES
(17, 20, '31', 1, NULL, '20110101'),
(33, 23, '31', 1, NULL, '20110419'),
(47, 33, '32', 2, NULL, '20110723');

-- --------------------------------------------------------

--
-- Table structure for table `customers_basket_attributes`
--

CREATE TABLE IF NOT EXISTS `customers_basket_attributes` (
  `customers_basket_attributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL,
  `products_id` tinytext NOT NULL,
  `products_options_id` int(11) NOT NULL,
  `products_options_value_id` int(11) NOT NULL,
  PRIMARY KEY (`customers_basket_attributes_id`),
  KEY `idx_customers_basket_att_customers_id` (`customers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `customers_basket_attributes`
--


-- --------------------------------------------------------

--
-- Table structure for table `customers_info`
--

CREATE TABLE IF NOT EXISTS `customers_info` (
  `customers_info_id` int(11) NOT NULL,
  `customers_info_date_of_last_logon` datetime DEFAULT NULL,
  `customers_info_number_of_logons` int(5) DEFAULT NULL,
  `customers_info_date_account_created` datetime DEFAULT NULL,
  `customers_info_date_account_last_modified` datetime DEFAULT NULL,
  `global_product_notifications` int(1) DEFAULT '0',
  PRIMARY KEY (`customers_info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers_info`
--

INSERT INTO `customers_info` (`customers_info_id`, `customers_info_date_of_last_logon`, `customers_info_number_of_logons`, `customers_info_date_account_created`, `customers_info_date_account_last_modified`, `global_product_notifications`) VALUES
(1, NULL, 0, '2010-09-22 19:44:07', NULL, 0),
(2, '2011-10-17 21:12:18', 19, '2010-09-22 20:39:15', NULL, 0),
(3, NULL, 0, '2010-09-22 21:25:52', NULL, 0),
(4, '2010-10-27 01:11:38', 3, '2010-10-09 00:37:37', NULL, 0),
(5, NULL, 0, '2010-10-10 18:29:06', NULL, 0),
(6, NULL, 0, '2010-10-11 10:37:53', NULL, 0),
(7, NULL, 0, '2010-10-11 17:56:44', NULL, 0),
(8, NULL, 0, '2010-10-12 21:22:06', NULL, 0),
(9, '2011-05-20 14:33:20', 5, '2010-10-17 11:24:32', NULL, 0),
(10, '2011-05-09 12:04:44', 2, '2010-11-03 11:20:07', NULL, 0),
(11, '2010-11-10 16:00:48', 1, '2010-11-09 10:41:07', NULL, 0),
(12, '2011-03-18 18:51:48', 7, '2010-11-12 00:43:00', NULL, 0),
(13, NULL, 0, '2010-11-13 21:13:29', NULL, 0),
(14, NULL, 0, '2010-11-14 15:00:26', NULL, 0),
(15, NULL, 0, '2010-11-14 18:06:51', NULL, 0),
(16, NULL, 0, '2010-11-14 20:09:41', NULL, 0),
(17, NULL, 0, '2010-12-16 20:23:39', NULL, 0),
(18, NULL, 0, '2010-12-29 17:29:14', NULL, 0),
(19, '2011-11-02 15:35:02', 7, '2010-12-31 13:06:06', NULL, 0),
(20, NULL, 0, '2011-01-01 20:22:17', '2011-01-01 20:33:09', 0),
(21, '2011-03-02 17:28:03', 1, '2011-02-07 17:05:21', NULL, 0),
(22, '2011-06-29 08:40:03', 4, '2011-03-16 14:18:18', NULL, 0),
(23, '2011-04-19 13:06:27', 1, '2011-04-19 12:32:45', NULL, 0),
(24, NULL, 0, '2011-04-23 21:37:02', NULL, 0),
(25, NULL, 0, '2011-04-30 16:54:17', NULL, 0),
(26, NULL, 0, '2011-05-02 15:32:21', NULL, 0),
(27, '2011-09-03 15:42:18', 2, '2011-05-05 16:05:53', NULL, 0),
(28, '2011-10-27 18:56:49', 3, '2011-06-20 13:29:23', NULL, 0),
(29, NULL, 0, '2011-06-22 05:59:06', NULL, 0),
(30, NULL, 0, '2011-06-22 06:19:00', NULL, 0),
(31, NULL, 0, '2011-06-25 16:51:35', NULL, 0),
(32, NULL, 0, '2011-07-04 13:53:04', NULL, 0),
(33, '2011-08-11 16:20:32', 2, '2011-07-23 13:50:36', NULL, 0),
(34, '2011-10-31 17:59:29', 2, '2011-08-23 14:46:57', NULL, 0),
(35, '2011-10-27 13:35:28', 8, '2011-08-24 11:13:02', NULL, 0),
(36, '2011-10-27 13:56:11', 2, '2011-08-27 15:56:12', NULL, 0),
(37, NULL, 0, '2011-08-30 14:48:04', NULL, 0),
(38, '2011-09-07 13:26:29', 1, '2011-09-07 08:43:43', NULL, 0),
(39, NULL, 0, '2011-10-02 18:09:50', NULL, 0),
(40, NULL, 0, '2011-10-17 09:21:37', NULL, 0),
(41, NULL, 0, '2011-10-17 14:56:05', NULL, 0),
(42, NULL, 0, '2011-10-17 15:52:25', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `geo_zones`
--

CREATE TABLE IF NOT EXISTS `geo_zones` (
  `geo_zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `geo_zone_name` varchar(32) NOT NULL,
  `geo_zone_description` varchar(255) NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`geo_zone_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `geo_zones`
--

INSERT INTO `geo_zones` (`geo_zone_id`, `geo_zone_name`, `geo_zone_description`, `last_modified`, `date_added`) VALUES
(1, 'Florida', 'Florida local sales tax zone', NULL, '2010-09-22 09:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `ip_dhcp`
--

CREATE TABLE IF NOT EXISTS `ip_dhcp` (
  `starting_ip_address` varchar(20) NOT NULL,
  `ending_ip_address` varchar(20) NOT NULL,
  `start_ip_added` int(11) NOT NULL,
  `last_ip_added` int(11) NOT NULL,
  `max_add` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ip_dhcp`
--

INSERT INTO `ip_dhcp` (`starting_ip_address`, `ending_ip_address`, `start_ip_added`, `last_ip_added`, `max_add`) VALUES
('192.168.2.111', '192.168.2.150', 160, 161, 240);

-- --------------------------------------------------------

--
-- Table structure for table `ip_links`
--

CREATE TABLE IF NOT EXISTS `ip_links` (
  `user_name` varchar(20) NOT NULL,
  `user_ID` int(11) NOT NULL,
  `ip_address` varchar(40) NOT NULL,
  `bytes_rx` bigint(20) NOT NULL DEFAULT '0',
  `bytes_tx` bigint(20) NOT NULL DEFAULT '0',
  `bytes_rx_last` bigint(20) NOT NULL DEFAULT '0',
  `bytes_tx_last` bigint(20) NOT NULL DEFAULT '0',
  `bytes_rx_max` bigint(20) NOT NULL DEFAULT '0',
  `bytes_tx_max` bigint(20) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_last_seen` timestamp NULL DEFAULT NULL,
  `mac_address` varchar(20) NOT NULL,
  `fairnat_kbit_start` int(11) NOT NULL DEFAULT '2000',
  `fairnat_kbit` int(11) NOT NULL DEFAULT '2000',
  `fairnat_speed` int(11) NOT NULL DEFAULT '3',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `wired` tinyint(4) NOT NULL DEFAULT '0',
  `room_num` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ip_links`
--


-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `languages_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `code` char(2) NOT NULL,
  `image` varchar(64) DEFAULT NULL,
  `directory` varchar(32) DEFAULT NULL,
  `sort_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`languages_id`),
  KEY `IDX_LANGUAGES_NAME` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`languages_id`, `name`, `code`, `image`, `directory`, `sort_order`) VALUES
(1, 'English', 'en', 'icon.gif', 'english', 1);

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE IF NOT EXISTS `manufacturers` (
  `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturers_name` varchar(32) NOT NULL,
  `manufacturers_image` varchar(64) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`manufacturers_id`),
  KEY `IDX_MANUFACTURERS_NAME` (`manufacturers_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `manufacturers`
--

INSERT INTO `manufacturers` (`manufacturers_id`, `manufacturers_name`, `manufacturers_image`, `date_added`, `last_modified`) VALUES
(1, 'Matrox', 'manufacturer_matrox.gif', '2010-09-22 09:41:18', NULL),
(2, 'Microsoft', 'manufacturer_microsoft.gif', '2010-09-22 09:41:18', NULL),
(3, 'Warner', 'manufacturer_warner.gif', '2010-09-22 09:41:18', NULL),
(4, 'Fox', 'manufacturer_fox.gif', '2010-09-22 09:41:18', NULL),
(5, 'Logitech', 'manufacturer_logitech.gif', '2010-09-22 09:41:18', NULL),
(6, 'Canon', 'manufacturer_canon.gif', '2010-09-22 09:41:18', NULL),
(7, 'Sierra', 'manufacturer_sierra.gif', '2010-09-22 09:41:18', NULL),
(8, 'GT Interactive', 'manufacturer_gt_interactive.gif', '2010-09-22 09:41:18', NULL),
(9, 'Hewlett Packard', 'manufacturer_hewlett_packard.gif', '2010-09-22 09:41:18', NULL),
(10, 'FreeNet', 'freenet.png', '2010-09-22 12:29:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers_info`
--

CREATE TABLE IF NOT EXISTS `manufacturers_info` (
  `manufacturers_id` int(11) NOT NULL,
  `languages_id` int(11) NOT NULL,
  `manufacturers_url` varchar(255) NOT NULL,
  `url_clicked` int(5) NOT NULL DEFAULT '0',
  `date_last_click` datetime DEFAULT NULL,
  PRIMARY KEY (`manufacturers_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `manufacturers_info`
--

INSERT INTO `manufacturers_info` (`manufacturers_id`, `languages_id`, `manufacturers_url`, `url_clicked`, `date_last_click`) VALUES
(1, 1, 'http://www.matrox.com', 0, NULL),
(2, 1, 'http://www.microsoft.com', 0, NULL),
(3, 1, 'http://www.warner.com', 0, NULL),
(4, 1, 'http://www.fox.com', 0, NULL),
(5, 1, 'http://www.logitech.com', 0, NULL),
(6, 1, 'http://www.canon.com', 0, NULL),
(7, 1, 'http://www.sierra.com', 0, NULL),
(8, 1, 'http://www.infogrames.com', 0, NULL),
(9, 1, 'http://www.hewlettpackard.com', 0, NULL),
(10, 1, 'http://freenet.surething.biz', 4, '2011-07-12 14:04:55');

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE IF NOT EXISTS `newsletters` (
  `newsletters_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `module` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_sent` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `locked` int(1) DEFAULT '0',
  PRIMARY KEY (`newsletters_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `newsletters`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL,
  `customers_name` varchar(255) NOT NULL,
  `customers_company` varchar(255) DEFAULT NULL,
  `customers_street_address` varchar(255) NOT NULL,
  `customers_suburb` varchar(255) DEFAULT NULL,
  `customers_city` varchar(255) NOT NULL,
  `customers_postcode` varchar(255) NOT NULL,
  `customers_state` varchar(255) DEFAULT NULL,
  `customers_country` varchar(255) NOT NULL,
  `customers_telephone` varchar(255) NOT NULL,
  `customers_email_address` varchar(255) NOT NULL,
  `customers_address_format_id` int(5) NOT NULL,
  `delivery_name` varchar(255) NOT NULL,
  `delivery_company` varchar(255) DEFAULT NULL,
  `delivery_street_address` varchar(255) NOT NULL,
  `delivery_suburb` varchar(255) DEFAULT NULL,
  `delivery_city` varchar(255) NOT NULL,
  `delivery_postcode` varchar(255) NOT NULL,
  `delivery_state` varchar(255) DEFAULT NULL,
  `delivery_country` varchar(255) NOT NULL,
  `delivery_address_format_id` int(5) NOT NULL,
  `billing_name` varchar(255) NOT NULL,
  `billing_company` varchar(255) DEFAULT NULL,
  `billing_street_address` varchar(255) NOT NULL,
  `billing_suburb` varchar(255) DEFAULT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_postcode` varchar(255) NOT NULL,
  `billing_state` varchar(255) DEFAULT NULL,
  `billing_country` varchar(255) NOT NULL,
  `billing_address_format_id` int(5) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `cc_type` varchar(20) DEFAULT NULL,
  `cc_owner` varchar(255) DEFAULT NULL,
  `cc_number` varchar(32) DEFAULT NULL,
  `cc_expires` varchar(4) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_purchased` datetime DEFAULT NULL,
  `orders_status` int(5) NOT NULL,
  `orders_date_finished` datetime DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `currency_value` decimal(14,6) DEFAULT NULL,
  `ipaddy` varchar(15) NOT NULL,
  `ipisp` varchar(15) NOT NULL,
  PRIMARY KEY (`orders_id`),
  KEY `idx_orders_customers_id` (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=81 ;

--
-- Dumping data for table `orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders_products`
--

CREATE TABLE IF NOT EXISTS `orders_products` (
  `orders_products_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `products_model` varchar(12) DEFAULT NULL,
  `products_name` varchar(64) NOT NULL,
  `products_price` decimal(15,4) NOT NULL,
  `final_price` decimal(15,4) NOT NULL,
  `products_tax` decimal(7,4) NOT NULL,
  `products_quantity` int(2) NOT NULL,
  PRIMARY KEY (`orders_products_id`),
  KEY `idx_orders_products_orders_id` (`orders_id`),
  KEY `idx_orders_products_products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=81 ;

--
-- Dumping data for table `orders_products`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders_products_attributes`
--

CREATE TABLE IF NOT EXISTS `orders_products_attributes` (
  `orders_products_attributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `orders_products_id` int(11) NOT NULL,
  `products_options` varchar(32) NOT NULL,
  `products_options_values` varchar(32) NOT NULL,
  `options_values_price` decimal(15,4) NOT NULL,
  `price_prefix` char(1) NOT NULL,
  PRIMARY KEY (`orders_products_attributes_id`),
  KEY `idx_orders_products_att_orders_id` (`orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `orders_products_attributes`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders_products_download`
--

CREATE TABLE IF NOT EXISTS `orders_products_download` (
  `orders_products_download_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_filename` varchar(255) NOT NULL DEFAULT '',
  `download_maxdays` int(2) NOT NULL DEFAULT '0',
  `download_count` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`orders_products_download_id`),
  KEY `idx_orders_products_download_orders_id` (`orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `orders_products_download`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders_status`
--

CREATE TABLE IF NOT EXISTS `orders_status` (
  `orders_status_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `orders_status_name` varchar(32) NOT NULL,
  `public_flag` int(11) DEFAULT '1',
  `downloads_flag` int(11) DEFAULT '0',
  PRIMARY KEY (`orders_status_id`,`language_id`),
  KEY `idx_orders_status_name` (`orders_status_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_status`
--

INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES
(1, 1, 'Pending', 1, 0),
(2, 1, 'Processed', 1, 1),
(3, 1, 'Paid', 1, 1),
(4, 1, 'PayPal [Transactions]', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders_status_history`
--

CREATE TABLE IF NOT EXISTS `orders_status_history` (
  `orders_status_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `orders_status_id` int(5) NOT NULL,
  `date_added` datetime NOT NULL,
  `customer_notified` int(1) DEFAULT '0',
  `comments` text,
  PRIMARY KEY (`orders_status_history_id`),
  KEY `idx_orders_status_history_orders_id` (`orders_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `orders_status_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders_total`
--

CREATE TABLE IF NOT EXISTS `orders_total` (
  `orders_total_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL,
  `class` varchar(32) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`orders_total_id`),
  KEY `idx_orders_total_orders_id` (`orders_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=241 ;

--
-- Dumping data for table `orders_total`
--


-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `products_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_quantity` int(4) NOT NULL,
  `products_model` varchar(12) DEFAULT NULL,
  `products_image` varchar(64) DEFAULT NULL,
  `products_price` decimal(15,4) NOT NULL,
  `products_date_added` datetime NOT NULL,
  `products_last_modified` datetime DEFAULT NULL,
  `products_date_available` datetime DEFAULT NULL,
  `products_weight` decimal(5,2) NOT NULL,
  `products_status` tinyint(1) NOT NULL,
  `products_tax_class_id` int(11) NOT NULL,
  `manufacturers_id` int(11) DEFAULT NULL,
  `products_ordered` int(11) NOT NULL DEFAULT '0',
  `products_hours_to_expire` float NOT NULL,
  PRIMARY KEY (`products_id`),
  KEY `idx_products_model` (`products_model`),
  KEY `idx_products_date_added` (`products_date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`products_id`, `products_quantity`, `products_model`, `products_image`, `products_price`, `products_date_added`, `products_last_modified`, `products_date_available`, `products_weight`, `products_status`, `products_tax_class_id`, `manufacturers_id`, `products_ordered`, `products_hours_to_expire`) VALUES
(28, 0, NULL, NULL, '0.0000', '0000-00-00 00:00:00', NULL, NULL, '0.00', 0, 0, NULL, 0, 0),
(29, 69, '101', 'wifi_symbol2.jpg', '700.0000', '2010-09-22 12:33:25', '2011-06-20 18:14:21', NULL, '0.00', 1, 0, 10, 30, 720),
(30, 86, '102', '650px-Ethernet_RJ45_connector_p1160054.jpg', '1600.0000', '2010-09-22 12:42:29', '2011-06-20 18:16:41', NULL, '0.00', 1, 0, 10, 13, 720),
(31, 81, '103', 'wifi_symbol2.jpg', '50.0000', '2010-09-22 12:44:42', NULL, NULL, '0.00', 1, 0, 10, 18, 24),
(32, 95, '104', 'wifi_symbol2.jpg', '250.0000', '2010-09-22 12:46:23', '2011-06-20 18:20:25', NULL, '0.00', 1, 0, 10, 4, 168),
(33, 10, '201', 'freenet.png', '1500.0000', '2010-09-22 12:49:05', NULL, NULL, '0.00', 1, 0, 10, 0, 0),
(34, 88, '120', '650px-Ethernet_RJ45_connector_p1160054.jpg', '800.0000', '2011-06-20 17:45:20', '2011-06-20 18:04:26', NULL, '0.00', 1, 0, 0, 12, 720),
(35, 98, '121', '650px-Ethernet_RJ45_connector_p1160054.jpg', '300.0000', '2011-06-20 18:19:21', '2011-06-20 18:20:05', NULL, '0.00', 1, 0, 0, 2, 168),
(36, 997, '222', '650px-Ethernet_RJ45_connector_p1160054.jpg', '1000.0000', '2011-09-03 15:21:45', NULL, NULL, '0.00', 1, 0, 0, 2, 912),
(37, 997, '333', '650px-Ethernet_RJ45_connector_p1160054.jpg', '2400.0000', '2011-09-03 15:28:07', NULL, NULL, '0.00', 1, 0, 0, 2, 2232);

-- --------------------------------------------------------

--
-- Table structure for table `products_attributes`
--

CREATE TABLE IF NOT EXISTS `products_attributes` (
  `products_attributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL,
  `options_id` int(11) NOT NULL,
  `options_values_id` int(11) NOT NULL,
  `options_values_price` decimal(15,4) NOT NULL,
  `price_prefix` char(1) NOT NULL,
  PRIMARY KEY (`products_attributes_id`),
  KEY `idx_products_attributes_products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `products_attributes`
--


-- --------------------------------------------------------

--
-- Table structure for table `products_attributes_download`
--

CREATE TABLE IF NOT EXISTS `products_attributes_download` (
  `products_attributes_id` int(11) NOT NULL,
  `products_attributes_filename` varchar(255) NOT NULL DEFAULT '',
  `products_attributes_maxdays` int(2) DEFAULT '0',
  `products_attributes_maxcount` int(2) DEFAULT '0',
  PRIMARY KEY (`products_attributes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_attributes_download`
--

INSERT INTO `products_attributes_download` (`products_attributes_id`, `products_attributes_filename`, `products_attributes_maxdays`, `products_attributes_maxcount`) VALUES
(26, 'unreal.zip', 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `products_description`
--

CREATE TABLE IF NOT EXISTS `products_description` (
  `products_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_name` varchar(64) NOT NULL DEFAULT '',
  `products_description` text,
  `products_url` varchar(255) DEFAULT NULL,
  `products_viewed` int(5) DEFAULT '0',
  PRIMARY KEY (`products_id`,`language_id`),
  KEY `products_name` (`products_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `products_description`
--

INSERT INTO `products_description` (`products_id`, `language_id`, `products_name`, `products_description`, `products_url`, `products_viewed`) VALUES
(37, 1, 'FreeNet 93 day Wired Service', 'This is for longer term customers that to provide you with 93 days of wired service to lock you into the price we were charging at the time of the contract and also privides a small 3 day free bonus compared to our 30 day contract.  This contract only applies to rooms in PBC that are already pre-wired for FreeNet wired services. If your room is NOT prewired please take a look at the FreeNet wired service Install package. This contract provides an active Cat5 Ethernet cable from a single point in your room direct to the FreeNet 100 Mb/sec (10MB/sec) Ethernet switch hub. With wired LAN you can access The Internet at up to 1Mb/sec (1000kB/sec) and local services at 10 megabyte per second including video movies play on demand, system PXE boot off LAN with Ubuntu 9.1 and other operating systems, shared music files, VNC services and more. Service personnel are on premises so no delays when you have any problems we can fix it normally within minutes not days. This is a limited offer only the first 20 users in the PBC building will be provided service at this time until we can get access to more bandwidth from CAT or other Internet providers in the future.', '', 19),
(35, 1, 'FreeNet 7 days Wired Services', 'This is a contract for one week (7 days) of FreeNet wired Internet services.  This contract only applies to rooms in PBC that are already pre-wired for FreeNet wired services.  If your room is NOT prewired please take a look at the FreeNet wired service Install package.  This contract provides  an active Cat5 Ethernet cable from a single point in your room direct to the FreeNet 100 Mb/sec (10MB/sec) Ethernet switch hub. With wired LAN you can access The Internet  at up to 1Mb/sec (1000kB/sec) and local services at 10 megabyte per second including video movies play on demand, system PXE boot off LAN with Ubuntu 9.1 and other operating systems, shared music files, VNC services and more. Service personnel are on premises so no delays when you have any problems we can fix it normally within minutes not days. This is a limited offer only the first 20 users in the PBC building will be provided service at this time until we can get access to more bandwidth from CAT or other Internet providers in the future.', '', 46),
(36, 1, 'FreeNet 38 day Wired Service', 'I know 38 days sounds like a strange contract window time but this was just to provide for times when I didn''t have change for 800Baht and people wanted to just give me 1000Baht bill so I created this account that only provides you service at 26.32Baht per day instead of 26.67Baht (small discount) when compared to purchase with the 800 Baht for 30 day contract comes out to a huge 11.4Baht savings (if any at all).  \r\nThis contract only applies to rooms in PBC that are already pre-wired for FreeNet wired services. If your room is NOT prewired please take a look at the FreeNet wired service Install package. This contract provides an active Cat5 Ethernet cable from a single point in your room direct to the FreeNet 100 Mb/sec (10MB/sec) Ethernet switch hub. With wired LAN you can access The Internet at up to 1Mb/sec (1000kB/sec) and local services at 10 megabyte per second including video movies play on demand, system PXE boot off LAN with Ubuntu 9.1 and other operating systems, shared music files, VNC services and more. Service personnel are on premises so no delays when you have any problems we can fix it normally within minutes not days. This is a limited offer only the first 20 users in the PBC building will be provided service at this time until we can get access to more bandwidth from CAT or other Internet providers in the future.', '', 21),
(34, 1, 'FreeNet 30 days Wired Service', 'This is a contract for one month (30 days) of FreeNet wired Internet services.  This contract only applies to rooms in PBC that are already pre-wired for FreeNet wired services.  If your room is NOT prewired please take a look at the FreeNet wired service Install package.  This contract provides  an active Cat5 Ethernet cable from a single point in your room direct to the FreeNet 100 Mb/sec (10MB/sec) Ethernet switch hub. With wired LAN you can access The Internet  at up to 1Mb/sec (1000kB/sec) and local services at 10 megabyte per second including video movies play on demand, system PXE boot off LAN with Ubuntu 9.1 and other operating systems, shared music files, VNC services and more. Service personnel are on premises so no delays when you have any problems we can fix it normally within minutes not days. This is a limited offer only the first 20 users in the PBC building will be provided service at this time until we can get access to more bandwidth from CAT or other Internet providers in the future.', '', 100),
(31, 1, 'Freenet WiFi 50 baht donation (now also get 24hours service)', 'This is a small donation to help us continue free internet services to everyone. Thank you and all the others that have helped by donating there time and money. Note: We have added that this also provides or adds one day (24 hours) to your existing account balance for VIP Unlimited WiFi service. Thank You.', '', 404),
(32, 1, 'Freenet WiFi 7 days unlimited VIP WiFi', 'This gives you unlimited VIP high speed WiFi service for 7 days at all of Freenet locations for a single registered computer or laptop. With VIP service you are provided 24 hour high speed access. You will be provided 100% of the available 200kb/sec speed instead of only unused bandwidth. There is a slight discount when paid online with paypal. If you choose cash on delivery we will come pick up the money at you residence or at a meeting place. You will be provided 24 hours to make due on cash on deliver orders. Thank you for your donation.', '', 339),
(29, 1, 'Freenet WiFi 30 days  unlimited VIP WiFi service', 'This gives you unlimited VIP high speed WiFi service at all of Freenet locations for a single registered computer or laptop. With VIP service you are provided 24 hour high speed access. You will be provided 100% of the available 200kb/sec speed instead of only unused bandwidth. There is a slight discount when paid online with paypal. If you choose cash on delivery we will come pick up the money at you residence or at a meeting place. You will be provided 24 hours to make due on cash on deliver orders. Thank you for your donation.', '', 1096),
(30, 1, 'FreeNet Wired Service install package', 'FreeNet Wired services LAN RJ45 connect direct to the FreeNet Ethernet hub with 100 Megabyte per second local LAN connection direct connection to 900kB/sec Internet connection from True our Internet service provider.  We can have any room in PBC wired installed and working within 24 hours from when you make your order to instantly connect you to the Internet and more. this is a one time installation fee that includes one month (30 days) of service from the time of install. What we install is a brand new Cat5 cable from a single point in your room direct to the FreeNet 100 meg/sec Ethernet switch hub. With wired LAN you can access local services at 100 megabyte per second including video movies play on demand, system PXE boot off LAN with Ubuntu 9.1 and other operating systems, shared music files, VNC services and more. Service personnel are on premises so no delays when you have any problems we can fix it normally within minutes not days. This is a limited offer only the first 20 users in the PBC building will be provided service at this time until we can get access to more bandwidth from CAT or other Internet providers in the future. I built this system for me so you can expect to get better and more reliable services than ever before seen in Thailand. FreeNet will continue to add more services and improve with the help of your feedback and donations. Feel free to call me if you have any problem or questions. The wifi in this aria is now saturated with too many signals (over 50 access points last I checked) so if your having problems with wifi take the next step with FreeNet wired. If you have a FreeNet wifi account we will credit your new wired account with what is left on your account. If a FreeNet wire is already present in your room there is no installation fee, you can try it before you buy it preinstalled units with 2 free days service same as our wifi account customers. After the two day trail period you will be required to advance 800 baht for your first months service. Our services are prepaid so you don''t need to contact us to tell us that you are leaving.  Your account will terminate automatically on failure to continue payment. Each month is billable as 30 days from start of contract for 800 baht each month. Failure to make payment after the 30th day will be flagged and Internet service will be disabled but can be re-enabled in minutes with a call to FreeNet to extend your services and an agreement is made on how you wish to make payment. New accounts in preinstalled rooms can be started by the user just plug your computer into the FreeNet network connector into your computer and bring up your browser. You will be redirected if not registered to the FreeNet login screen. From here go to the FreeNet store and sign up and buy the needed time and service you desire. Once you sign up you can go back to the FreeNet login screen and login and start surfing. We will contact you at a later time to collect the money you owe us if any. Or just give us a call and we will setup your account for you.  Contact us for added details on install at 086-827-0277 and to setup an appointment for installation.', '', 254),
(33, 1, 'FreeNet Franchise licence offer', 'FreeNet franchise license\r\n\r\nBecome an Internet service provider with the well known and trusted FreeNet brand name. You can start your own Internet service in any building with 10 units or more with the help of our FreeNet software, training and consulting services to get you started. The FreeNet software license is provided free when used with 4 or less steady customers per month in a single building so you can start with what you already have for free and build up your customers over time before you ever need to pay us any monthly fee''s or if you loss customers for a time you don''t have costs if you don''t have income to pay them. With 5 customers or more FreeNet expects to receive 10% of the gross income of the installations that is automatically tracked with the FreeNet software. When you become a true member of FreeNet (paid franchise members only) you will receive all the benefits FreeNet has to offer including auto fault detection and call in support for you and your customers to get quick installation and customer trouble service support with true English speaking personnel. Remote diagnostics and training are also available. You will have access to all the needed contractors and consultants to add or repair any part of your systems as needed. You are not likely to find a cheaper franchise to start. All you need is one small low powered computer for a server such as a 1 ghz CPU with 512meg memory and 20 gig hard drive (about 3000 baht used), a single internet service provider such as TOT (500 baht per month) and a single wifi access point (about 2000 baht). You might already have some or all of these things. Then all you need is a one to three day lesson on how to setup and maintain the system. If you know anything about computers and or hardware it may go faster or slower depending on your skill levels. A two hour lesson is 500 baht for the FreeNet teacher to teach you what you will need to know to setup and maintain the system and show you how to setup the software on your server. So just think you might be able to start your first franchise for only 500 Baht!! Later if you want you can become a certified FreeNet instructor so you can teach others to become FreeNet franchise holders and make even more money. More advanced teaching can be done with remote teaching over the Internet where the instructor can control and see the students screen using remote desktop where the teacher can show you how to diagnose problems, do system administration, accounting, network monitoring and more. You don''t even need to invest to be a part of the FreeNet franchise. You can become a FreeNet sales representative. All you need to do is find people that want to become FreeNet Franchise holders. If you get a new FreeNet franchise to sign you will receive up to 5% of the gross of each franchise holder that signs up through you for the first year of each franchise plus 20% of the lessons and consulting fee''s that the franchise holder pays to FreeNet.\r\n\r\nWhy do people select FreeNet as a provider?\r\n\r\n1. Quick installation. With FreeNet our customers expect to have service within 24 hours from the time they order it. FreeNet accomplishes this with local on premises support and installation personnel. We train the franchise holder to support and install new customers within that 24 hour window of time and provides backup contractors to do the things that the franchise holder can''t or doesn''t want to do. We also enable the customer to do there own online registration so that they can get started without any help at all from the site owner. With the skills we teach you and our contractors, you can also become a backup contractor to other franchise holders that need your help. All the FreeNet franchise members work together to make the best Internet service available today. Where residents only stay for short stays of 1 to 6 months they don''t want to wait 2 to 3 months to get service. They prefer a quick reliable connection and that is FreeNet.\r\n\r\n\r\n2. Competitively priced service. When someone sees the name FreeNet they know it will cost less than most other Internet services available and provide extra services that no other service has yet to offer. And as the name suggest we also provide FREE service to promote new customers to try before they buy our services. Free services are only provided on off hours or days when VIP (paid customers) won''t be effected and a two day free trail after self registration.\r\n\r\n\r\n3. Reliable Service. With FreeNet''s auto fault detection and redundant ISP providers we offer unmatched reliability to any other internet provider in a building with 10 or more customers.\r\n\r\n\r\n4. Internet bandwidth and speed. FreeNet provides local DNS lookup and cache on the on site server to provide faster Internet browsing without filtered restrictions. We also monitor our Internet service providers and make sure they provide what is needed for the number of customers being serviced. We also categorize Internet traffic to keep high bandwidth user traffic from slowing down the browser traffic by using the dual redundant providers to service each group separately.\r\n\r\n\r\n5. Security. FreeNet has been trusted by thousands of users over the last 4+ years. We use the very best and secure Ubuntu Linux operating system that prevents the possibility of virus or any other exposure to our systems to the outside world. The user information that is collected for user accounts is never sold or used to spam our customers. We collect and keep a minimal amount of info from our customers to make for quick registration and security.\r\n\r\n\r\nSo if your ready to see more details or to schedule a demonstration or lesson give FreeNet a call at 086-827-0277 ask for Scotty and start up the easiest money maker ever.', 'freenet.surething.biz/docs/', 279);

-- --------------------------------------------------------

--
-- Table structure for table `products_notifications`
--

CREATE TABLE IF NOT EXISTS `products_notifications` (
  `products_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`products_id`,`customers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_notifications`
--

INSERT INTO `products_notifications` (`products_id`, `customers_id`, `date_added`) VALUES
(29, 12, '2010-11-12 00:44:43'),
(32, 33, '2011-07-23 13:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `products_options`
--

CREATE TABLE IF NOT EXISTS `products_options` (
  `products_options_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_options_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`products_options_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_options`
--

INSERT INTO `products_options` (`products_options_id`, `language_id`, `products_options_name`) VALUES
(1, 1, 'Color'),
(2, 1, 'Size'),
(3, 1, 'Model'),
(4, 1, 'Memory'),
(5, 1, 'Version');

-- --------------------------------------------------------

--
-- Table structure for table `products_options_values`
--

CREATE TABLE IF NOT EXISTS `products_options_values` (
  `products_options_values_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_options_values_name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`products_options_values_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_options_values`
--

INSERT INTO `products_options_values` (`products_options_values_id`, `language_id`, `products_options_values_name`) VALUES
(1, 1, '4 mb'),
(2, 1, '8 mb'),
(3, 1, '16 mb'),
(4, 1, '32 mb'),
(5, 1, 'Value'),
(6, 1, 'Premium'),
(7, 1, 'Deluxe'),
(8, 1, 'PS/2'),
(9, 1, 'USB'),
(10, 1, 'Download: Windows - English'),
(13, 1, 'Box: Windows - English');

-- --------------------------------------------------------

--
-- Table structure for table `products_options_values_to_products_options`
--

CREATE TABLE IF NOT EXISTS `products_options_values_to_products_options` (
  `products_options_values_to_products_options_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_options_id` int(11) NOT NULL,
  `products_options_values_id` int(11) NOT NULL,
  PRIMARY KEY (`products_options_values_to_products_options_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `products_options_values_to_products_options`
--

INSERT INTO `products_options_values_to_products_options` (`products_options_values_to_products_options_id`, `products_options_id`, `products_options_values_id`) VALUES
(1, 4, 1),
(2, 4, 2),
(3, 4, 3),
(4, 4, 4),
(5, 3, 5),
(6, 3, 6),
(7, 3, 7),
(8, 3, 8),
(9, 3, 9),
(10, 5, 10),
(13, 5, 13);

-- --------------------------------------------------------

--
-- Table structure for table `products_to_categories`
--

CREATE TABLE IF NOT EXISTS `products_to_categories` (
  `products_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  PRIMARY KEY (`products_id`,`categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_to_categories`
--

INSERT INTO `products_to_categories` (`products_id`, `categories_id`) VALUES
(29, 21),
(30, 21),
(31, 21),
(32, 21),
(33, 21),
(34, 21),
(35, 21),
(36, 21),
(37, 21);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `reviews_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `customers_name` varchar(255) NOT NULL,
  `reviews_rating` int(1) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `reviews_read` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reviews_id`),
  KEY `idx_reviews_products_id` (`products_id`),
  KEY `idx_reviews_customers_id` (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `reviews`
--


-- --------------------------------------------------------

--
-- Table structure for table `reviews_description`
--

CREATE TABLE IF NOT EXISTS `reviews_description` (
  `reviews_id` int(11) NOT NULL,
  `languages_id` int(11) NOT NULL,
  `reviews_text` text NOT NULL,
  PRIMARY KEY (`reviews_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reviews_description`
--


-- --------------------------------------------------------

--
-- Table structure for table `sec_directory_whitelist`
--

CREATE TABLE IF NOT EXISTS `sec_directory_whitelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `directory` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `sec_directory_whitelist`
--

INSERT INTO `sec_directory_whitelist` (`id`, `directory`) VALUES
(1, 'admin/backups'),
(2, 'admin/images/graphs'),
(3, 'images'),
(4, 'images/banners'),
(5, 'images/dvd'),
(6, 'images/gt_interactive'),
(7, 'images/hewlett_packard'),
(8, 'images/matrox'),
(9, 'images/microsoft'),
(10, 'images/sierra'),
(11, 'includes/work'),
(12, 'pub');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `sesskey` varchar(32) NOT NULL,
  `expiry` int(11) unsigned NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`sesskey`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `specials`
--

CREATE TABLE IF NOT EXISTS `specials` (
  `specials_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL,
  `specials_new_products_price` decimal(15,4) NOT NULL,
  `specials_date_added` datetime DEFAULT NULL,
  `specials_last_modified` datetime DEFAULT NULL,
  `expires_date` datetime DEFAULT NULL,
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specials_id`),
  KEY `idx_specials_products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `specials`
--


-- --------------------------------------------------------

--
-- Table structure for table `tax_class`
--

CREATE TABLE IF NOT EXISTS `tax_class` (
  `tax_class_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_class_title` varchar(32) NOT NULL,
  `tax_class_description` varchar(255) NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`tax_class_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tax_class`
--

INSERT INTO `tax_class` (`tax_class_id`, `tax_class_title`, `tax_class_description`, `last_modified`, `date_added`) VALUES
(1, 'Taxable Goods', 'The following types of products are included non-food, services, etc', '2010-09-22 09:41:18', '2010-09-22 09:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE IF NOT EXISTS `tax_rates` (
  `tax_rates_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_zone_id` int(11) NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `tax_priority` int(5) DEFAULT '1',
  `tax_rate` decimal(7,4) NOT NULL,
  `tax_description` varchar(255) NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`tax_rates_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`tax_rates_id`, `tax_zone_id`, `tax_class_id`, `tax_priority`, `tax_rate`, `tax_description`, `last_modified`, `date_added`) VALUES
(1, 1, 1, 1, '7.0000', 'FL TAX 7.0%', '2010-09-22 09:41:18', '2010-09-22 09:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `whos_online`
--

CREATE TABLE IF NOT EXISTS `whos_online` (
  `customer_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `time_entry` varchar(14) NOT NULL,
  `time_last_click` varchar(14) NOT NULL,
  `last_page_url` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `whos_online`
--

INSERT INTO `whos_online` (`customer_id`, `full_name`, `session_id`, `ip_address`, `time_entry`, `time_last_click`, `last_page_url`) VALUES
(0, 'Guest', '', '66.249.72.211', '1320307325', '1320307325', '/catalog2/product_info.php?manufacturers_id=10&products_id=29');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE IF NOT EXISTS `zones` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_country_id` int(11) NOT NULL,
  `zone_code` varchar(32) NOT NULL,
  `zone_name` varchar(255) NOT NULL,
  PRIMARY KEY (`zone_id`),
  KEY `idx_zones_country_id` (`zone_country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=182 ;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES
(1, 223, 'AL', 'Alabama'),
(2, 223, 'AK', 'Alaska'),
(3, 223, 'AS', 'American Samoa'),
(4, 223, 'AZ', 'Arizona'),
(5, 223, 'AR', 'Arkansas'),
(6, 223, 'AF', 'Armed Forces Africa'),
(7, 223, 'AA', 'Armed Forces Americas'),
(8, 223, 'AC', 'Armed Forces Canada'),
(9, 223, 'AE', 'Armed Forces Europe'),
(10, 223, 'AM', 'Armed Forces Middle East'),
(11, 223, 'AP', 'Armed Forces Pacific'),
(12, 223, 'CA', 'California'),
(13, 223, 'CO', 'Colorado'),
(14, 223, 'CT', 'Connecticut'),
(15, 223, 'DE', 'Delaware'),
(16, 223, 'DC', 'District of Columbia'),
(17, 223, 'FM', 'Federated States Of Micronesia'),
(18, 223, 'FL', 'Florida'),
(19, 223, 'GA', 'Georgia'),
(20, 223, 'GU', 'Guam'),
(21, 223, 'HI', 'Hawaii'),
(22, 223, 'ID', 'Idaho'),
(23, 223, 'IL', 'Illinois'),
(24, 223, 'IN', 'Indiana'),
(25, 223, 'IA', 'Iowa'),
(26, 223, 'KS', 'Kansas'),
(27, 223, 'KY', 'Kentucky'),
(28, 223, 'LA', 'Louisiana'),
(29, 223, 'ME', 'Maine'),
(30, 223, 'MH', 'Marshall Islands'),
(31, 223, 'MD', 'Maryland'),
(32, 223, 'MA', 'Massachusetts'),
(33, 223, 'MI', 'Michigan'),
(34, 223, 'MN', 'Minnesota'),
(35, 223, 'MS', 'Mississippi'),
(36, 223, 'MO', 'Missouri'),
(37, 223, 'MT', 'Montana'),
(38, 223, 'NE', 'Nebraska'),
(39, 223, 'NV', 'Nevada'),
(40, 223, 'NH', 'New Hampshire'),
(41, 223, 'NJ', 'New Jersey'),
(42, 223, 'NM', 'New Mexico'),
(43, 223, 'NY', 'New York'),
(44, 223, 'NC', 'North Carolina'),
(45, 223, 'ND', 'North Dakota'),
(46, 223, 'MP', 'Northern Mariana Islands'),
(47, 223, 'OH', 'Ohio'),
(48, 223, 'OK', 'Oklahoma'),
(49, 223, 'OR', 'Oregon'),
(50, 223, 'PW', 'Palau'),
(51, 223, 'PA', 'Pennsylvania'),
(52, 223, 'PR', 'Puerto Rico'),
(53, 223, 'RI', 'Rhode Island'),
(54, 223, 'SC', 'South Carolina'),
(55, 223, 'SD', 'South Dakota'),
(56, 223, 'TN', 'Tennessee'),
(57, 223, 'TX', 'Texas'),
(58, 223, 'UT', 'Utah'),
(59, 223, 'VT', 'Vermont'),
(60, 223, 'VI', 'Virgin Islands'),
(61, 223, 'VA', 'Virginia'),
(62, 223, 'WA', 'Washington'),
(63, 223, 'WV', 'West Virginia'),
(64, 223, 'WI', 'Wisconsin'),
(65, 223, 'WY', 'Wyoming'),
(66, 38, 'AB', 'Alberta'),
(67, 38, 'BC', 'British Columbia'),
(68, 38, 'MB', 'Manitoba'),
(69, 38, 'NF', 'Newfoundland'),
(70, 38, 'NB', 'New Brunswick'),
(71, 38, 'NS', 'Nova Scotia'),
(72, 38, 'NT', 'Northwest Territories'),
(73, 38, 'NU', 'Nunavut'),
(74, 38, 'ON', 'Ontario'),
(75, 38, 'PE', 'Prince Edward Island'),
(76, 38, 'QC', 'Quebec'),
(77, 38, 'SK', 'Saskatchewan'),
(78, 38, 'YT', 'Yukon Territory'),
(79, 81, 'NDS', 'Niedersachsen'),
(80, 81, 'BAW', 'Baden-WÃ¼rttemberg'),
(81, 81, 'BAY', 'Bayern'),
(82, 81, 'BER', 'Berlin'),
(83, 81, 'BRG', 'Brandenburg'),
(84, 81, 'BRE', 'Bremen'),
(85, 81, 'HAM', 'Hamburg'),
(86, 81, 'HES', 'Hessen'),
(87, 81, 'MEC', 'Mecklenburg-Vorpommern'),
(88, 81, 'NRW', 'Nordrhein-Westfalen'),
(89, 81, 'RHE', 'Rheinland-Pfalz'),
(90, 81, 'SAR', 'Saarland'),
(91, 81, 'SAS', 'Sachsen'),
(92, 81, 'SAC', 'Sachsen-Anhalt'),
(93, 81, 'SCN', 'Schleswig-Holstein'),
(94, 81, 'THE', 'ThÃ¼ringen'),
(95, 14, 'WI', 'Wien'),
(96, 14, 'NO', 'NiederÃ¶sterreich'),
(97, 14, 'OO', 'OberÃ¶sterreich'),
(98, 14, 'SB', 'Salzburg'),
(99, 14, 'KN', 'KÃ¤rnten'),
(100, 14, 'ST', 'Steiermark'),
(101, 14, 'TI', 'Tirol'),
(102, 14, 'BL', 'Burgenland'),
(103, 14, 'VB', 'Voralberg'),
(104, 204, 'AG', 'Aargau'),
(105, 204, 'AI', 'Appenzell Innerrhoden'),
(106, 204, 'AR', 'Appenzell Ausserrhoden'),
(107, 204, 'BE', 'Bern'),
(108, 204, 'BL', 'Basel-Landschaft'),
(109, 204, 'BS', 'Basel-Stadt'),
(110, 204, 'FR', 'Freiburg'),
(111, 204, 'GE', 'Genf'),
(112, 204, 'GL', 'Glarus'),
(113, 204, 'JU', 'GraubÃ¼nden'),
(114, 204, 'JU', 'Jura'),
(115, 204, 'LU', 'Luzern'),
(116, 204, 'NE', 'Neuenburg'),
(117, 204, 'NW', 'Nidwalden'),
(118, 204, 'OW', 'Obwalden'),
(119, 204, 'SG', 'St. Gallen'),
(120, 204, 'SH', 'Schaffhausen'),
(121, 204, 'SO', 'Solothurn'),
(122, 204, 'SZ', 'Schwyz'),
(123, 204, 'TG', 'Thurgau'),
(124, 204, 'TI', 'Tessin'),
(125, 204, 'UR', 'Uri'),
(126, 204, 'VD', 'Waadt'),
(127, 204, 'VS', 'Wallis'),
(128, 204, 'ZG', 'Zug'),
(129, 204, 'ZH', 'ZÃ¼rich'),
(130, 195, 'A CoruÃ±a', 'A CoruÃ±a'),
(131, 195, 'Alava', 'Alava'),
(132, 195, 'Albacete', 'Albacete'),
(133, 195, 'Alicante', 'Alicante'),
(134, 195, 'Almeria', 'Almeria'),
(135, 195, 'Asturias', 'Asturias'),
(136, 195, 'Avila', 'Avila'),
(137, 195, 'Badajoz', 'Badajoz'),
(138, 195, 'Baleares', 'Baleares'),
(139, 195, 'Barcelona', 'Barcelona'),
(140, 195, 'Burgos', 'Burgos'),
(141, 195, 'Caceres', 'Caceres'),
(142, 195, 'Cadiz', 'Cadiz'),
(143, 195, 'Cantabria', 'Cantabria'),
(144, 195, 'Castellon', 'Castellon'),
(145, 195, 'Ceuta', 'Ceuta'),
(146, 195, 'Ciudad Real', 'Ciudad Real'),
(147, 195, 'Cordoba', 'Cordoba'),
(148, 195, 'Cuenca', 'Cuenca'),
(149, 195, 'Girona', 'Girona'),
(150, 195, 'Granada', 'Granada'),
(151, 195, 'Guadalajara', 'Guadalajara'),
(152, 195, 'Guipuzcoa', 'Guipuzcoa'),
(153, 195, 'Huelva', 'Huelva'),
(154, 195, 'Huesca', 'Huesca'),
(155, 195, 'Jaen', 'Jaen'),
(156, 195, 'La Rioja', 'La Rioja'),
(157, 195, 'Las Palmas', 'Las Palmas'),
(158, 195, 'Leon', 'Leon'),
(159, 195, 'Lleida', 'Lleida'),
(160, 195, 'Lugo', 'Lugo'),
(161, 195, 'Madrid', 'Madrid'),
(162, 195, 'Malaga', 'Malaga'),
(163, 195, 'Melilla', 'Melilla'),
(164, 195, 'Murcia', 'Murcia'),
(165, 195, 'Navarra', 'Navarra'),
(166, 195, 'Ourense', 'Ourense'),
(167, 195, 'Palencia', 'Palencia'),
(168, 195, 'Pontevedra', 'Pontevedra'),
(169, 195, 'Salamanca', 'Salamanca'),
(170, 195, 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife'),
(171, 195, 'Segovia', 'Segovia'),
(172, 195, 'Sevilla', 'Sevilla'),
(173, 195, 'Soria', 'Soria'),
(174, 195, 'Tarragona', 'Tarragona'),
(175, 195, 'Teruel', 'Teruel'),
(176, 195, 'Toledo', 'Toledo'),
(177, 195, 'Valencia', 'Valencia'),
(178, 195, 'Valladolid', 'Valladolid'),
(179, 195, 'Vizcaya', 'Vizcaya'),
(180, 195, 'Zamora', 'Zamora'),
(181, 195, 'Zaragoza', 'Zaragoza');

-- --------------------------------------------------------

--
-- Table structure for table `zones_to_geo_zones`
--

CREATE TABLE IF NOT EXISTS `zones_to_geo_zones` (
  `association_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_country_id` int(11) NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `geo_zone_id` int(11) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`association_id`),
  KEY `idx_zones_to_geo_zones_country_id` (`zone_country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `zones_to_geo_zones`
--

INSERT INTO `zones_to_geo_zones` (`association_id`, `zone_country_id`, `zone_id`, `geo_zone_id`, `last_modified`, `date_added`) VALUES
(1, 223, 18, 1, NULL, '2010-09-22 09:41:18');
