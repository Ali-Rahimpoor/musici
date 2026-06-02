-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2026 at 05:12 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `musici`
--
CREATE DATABASE IF NOT EXISTS `musici` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `musici`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int DEFAULT NULL,
  `music_count` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `title`, `parent`, `music_count`) VALUES
(8, 'رپ', NULL, 2),
(9, 'پاپ', NULL, 6),
(11, 'دنس', NULL, 2),
(13, 'غمگین', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `musics`
--

DROP TABLE IF EXISTS `musics`;
CREATE TABLE IF NOT EXISTS `musics` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `music_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` smallint UNSIGNED DEFAULT NULL,
  `like_count` int DEFAULT '0',
  `download_count` int DEFAULT '0',
  `created_at` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `musics`
--

INSERT INTO `musics` (`ID`, `title`, `content`, `cover`, `status`, `music_url`, `duration`, `like_count`, `download_count`, `created_at`) VALUES
(4, 'سلام دنیا', 'متن آهنگ سلام دنیا\r\nدسته بندی پاپ و ...', 'http://localhost/myphp/musici/uploads/2026/05/bL1.jpg', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/aAlYXEyKlvJ.mp3', NULL, 5, 3, '2024-03-03'),
(5, 'حد', 'متن آهنگ سلام دنیا\r\nدسته بندی پاپ و ...', 'http://localhost/myphp/musici/uploads/2026/05/PDShottrotter-lifeofpix-369822369909.jpeg', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/ZYBYZBQLeax.mp3', NULL, 3, 13, '2021-06-03'),
(6, 'مجموع', 'متن آهنگ سلام دنیا\r\nدسته بندی پاپ و ...', 'http://localhost/myphp/musici/uploads/2026/05/REdreamina-2026-02-12-8762-Standalone semi-realistic 3D hero scene ....jpeg', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/vtMdgeWivzi.mp3', NULL, 3, 12, '2021-06-09'),
(7, 'پریشب', 'متن آهنگ سلام دنیا\r\nدسته بندی پاپ و ...', 'http://localhost/myphp/musici/uploads/2026/05/Jrmiles-morales-spider-man-web-swing-hd-wallpaper-uhdpaper.com-604@0@j.jpg', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/DCdWXkzKzrB.mp3', NULL, 8, 40, '2021-06-09'),
(8, 'تلافی', 'تلافی آهنگ جدید', 'http://localhost/myphp/musici/uploads/2026/05/xYocean-car-floating-full-moon-scenery-digital-art-2k-wallpaper-uhdpaper.com-664@0@j.jpg', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/jlUjfiidSTV.mp3', NULL, 2, 0, '2014-01-01'),
(9, 'تلافی 2', 'تلافی آهنگ جدید 2', 'http://localhost/myphp/musici/uploads/2026/05/ahsauron-lord-of-the-rings-hd-wallpaper-uhdpaper.com-112@5@f.jpg', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/YPwlvYCyfvO.mp3', NULL, 0, 1, '2019-01-04'),
(11, 'همه باهم', 'با همیینضیضی', 'http://localhost/myphp/musici/uploads/2026/05/jXmilky-way-space-4k-wallpaper-uhdpaper.com-151@5@c.jpg', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/vFJfgSVjSrO.mp3', NULL, 4, 3, '2026-05-11'),
(12, 'غم دنیا', 'متن غم', 'http://localhost/myphp/musici/uploads/2026/05/laIMG_6586.png', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/PhRzTZvNmZM.mp3', NULL, 3, 0, '2006-01-18'),
(14, 'آهنگ بیکلام', 'پاپی', 'http://localhost/myphp/musici/uploads/2026/05/DItree-mountain-sunset-nature-scenery-hd-wallpaper-uhdpaper.com-645@2@b.jpg', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/ctXwVTXedFQ.mp3', NULL, 4, 0, '2024-03-03'),
(15, 'همین حالا بهم', 'بدضخهبدضصخبدهصض', 'http://localhost/myphp/musici/uploads/2026/05/JSlake-nature-italy-scenery-hd-wallpaper-1920x1080-uhdpaper.com-420.0_b.jpg', 'publish', 'http://localhost/myphp/musici/uploads/2026/05/bBEWSMXMzWW.mp3', NULL, 1, 0, '2024-03-03');

-- --------------------------------------------------------

--
-- Table structure for table `music_category`
--

DROP TABLE IF EXISTS `music_category`;
CREATE TABLE IF NOT EXISTS `music_category` (
  `music_id` int NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `music_category`
--

INSERT INTO `music_category` (`music_id`, `category_id`) VALUES
(3, 9),
(6, 8),
(7, 8),
(8, 9),
(9, 9),
(10, 10),
(12, 13),
(14, 9),
(11, 9),
(15, 11),
(13, 10),
(5, 11),
(4, 9);

-- --------------------------------------------------------

--
-- Table structure for table `music_favorite`
--

DROP TABLE IF EXISTS `music_favorite`;
CREATE TABLE IF NOT EXISTS `music_favorite` (
  `music_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `music_favorite`
--

INSERT INTO `music_favorite` (`music_id`, `user_id`) VALUES
(11, 5),
(12, 5),
(8, 5),
(11, 7),
(14, 8),
(14, 9),
(4, 9),
(11, 9),
(12, 9),
(6, 9),
(4, 4),
(14, 4);

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

DROP TABLE IF EXISTS `otp_codes`;
CREATE TABLE IF NOT EXISTS `otp_codes` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL DEFAULT '0',
  `sms_id` int NOT NULL,
  `phone` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` mediumint UNSIGNED NOT NULL,
  `status` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `try_count` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expire_at` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`ID`, `userID`, `sms_id`, `phone`, `code`, `status`, `try_count`, `created_at`, `expire_at`) VALUES
(23, 0, 89545112, '09394895034', 9640, 'used', 1, '2026-05-16 14:33:08', '2026-05-16 14:33:18'),
(24, 0, 89545112, '09394895034', 1557, 'pending', 1, '2026-05-16 14:33:22', '2026-05-16 14:33:32'),
(25, 0, 89545112, '09394985034', 8189, 'used', 1, '2026-05-16 14:35:10', '2026-05-16 14:37:10'),
(26, 0, 89545112, '09394985034', 2605, 'used', 1, '2026-05-18 10:52:43', '2026-05-18 10:54:43'),
(27, 0, 89545112, '09394985034', 6483, 'used', 1, '2026-05-18 18:55:56', '2026-05-18 18:57:56'),
(28, 0, 89545112, '09394985034', 7818, 'used', 1, '2026-05-19 12:35:10', '2026-05-19 12:37:10'),
(29, 0, 89545112, '09394985034', 600957, 'pending', 1, '2026-05-19 19:29:02', '2026-05-19 19:30:32'),
(30, 0, 89545112, '09394985034', 558155, 'used', 1, '2026-05-19 19:30:46', '2026-05-19 19:32:16'),
(31, 0, 89545112, '09909986792', 2870, 'used', 1, '2026-05-19 21:50:37', '2026-05-19 21:52:07'),
(32, 0, 89545112, '09394985034', 4633, 'used', 1, '2026-05-19 21:52:01', '2026-05-19 21:53:31'),
(33, 0, 89545112, '09394985034', 57880, 'used', 1, '2026-05-19 22:05:55', '2026-05-19 22:07:25'),
(34, 0, 89545112, '09394985034', 9749, 'used', 1, '2026-05-21 18:19:10', '2026-05-21 18:20:40');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'موزیکیفا', '2026-05-19 15:01:14', '2026-05-21 14:49:52'),
(2, 'site_description', 'پلتفرم آنلاین پخش موزیک', '2026-05-19 15:01:14', '2026-05-19 18:22:33'),
(3, 'site_status', 'active', '2026-05-19 15:01:14', '2026-05-21 14:51:09'),
(4, 'otp_duration', '90', '2026-05-19 15:01:14', '2026-05-19 15:56:54'),
(5, 'otp_length', '4', '2026-05-19 15:01:14', '2026-05-19 18:36:18'),
(6, 'home_music_count', '4', '2026-05-19 15:01:14', '2026-05-19 15:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `role` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `password`, `phone`, `name`, `birthday`, `role`, `avatar`) VALUES
(5, 'ali', 'ali', '0900000', 'علی رحیم پور', NULL, 'admin', 'http://localhost/myphp/musici/uploads/2026/05/HECFKuXwfXd.png'),
(6, 'ramin', 'ramin', '099066', 'رامبد', NULL, 'user', NULL),
(7, 'mohamad', 'mohamad', '0972312', 'محمد قلی', NULL, 'user', 'http://localhost/myphp/musici/uploads/2026/05/mSzoLiAFcIw.png'),
(8, 'alir', 'alir', '0990992', 'علی رضایی', NULL, 'user', NULL),
(9, 'alirahimpoor', 'ali', '04767625', 'علی', NULL, 'user', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
