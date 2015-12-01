-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 01, 2015 at 05:59 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `myo`
--
CREATE DATABASE IF NOT EXISTS `myo` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `myo`;

-- --------------------------------------------------------

--
-- Table structure for table `acceleration_data_points`
--

CREATE TABLE IF NOT EXISTS `acceleration_data_points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adpXAcceleration` double(8,2) NOT NULL,
  `adpYAcceleration` double(8,2) NOT NULL,
  `adpZAcceleration` double(8,2) NOT NULL,
  `adpDateTime` datetime NOT NULL,
  `sessionID` bigint(20) NOT NULL,
  `adpDeleted` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=430652 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_graphs`
--

CREATE TABLE IF NOT EXISTS `dashboard_graphs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dgSize` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dgRow` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dgtID` bigint(20) NOT NULL,
  `loginID` bigint(20) NOT NULL,
  `dgDeleted` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_graph_types`
--

CREATE TABLE IF NOT EXISTS `dashboard_graph_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dgtName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dgtSubType` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dgtDeleted` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `emg_data_points`
--

CREATE TABLE IF NOT EXISTS `emg_data_points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emgpPod1` double(8,2) NOT NULL,
  `emgpPod2` double(8,2) NOT NULL,
  `emgpPod3` double(8,2) NOT NULL,
  `emgpPod4` double(8,2) NOT NULL,
  `emgpPod5` double(8,2) NOT NULL,
  `emgpPod6` double(8,2) NOT NULL,
  `emgpPod7` double(8,2) NOT NULL,
  `emgpPod8` double(8,2) NOT NULL,
  `emgpDateTime` datetime NOT NULL,
  `sessionID` bigint(20) NOT NULL,
  `emgpDeleted` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=426784 ;

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE IF NOT EXISTS `logins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loginUsername` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loginPassword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loginFirstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loginLastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loginEmail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loginDeleted` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orientation_data_points`
--

CREATE TABLE IF NOT EXISTS `orientation_data_points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `odpXRotation` double(8,2) NOT NULL,
  `odpYRotation` double(8,2) NOT NULL,
  `odpZRotation` double(8,2) NOT NULL,
  `odpDateTime` datetime NOT NULL,
  `sessionID` bigint(20) NOT NULL,
  `odpDeleted` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=819811 ;

-- --------------------------------------------------------

--
-- Table structure for table `rotation_data_points`
--

CREATE TABLE IF NOT EXISTS `rotation_data_points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rdpXRotation` double(8,2) NOT NULL,
  `rdpYRotation` double(8,2) NOT NULL,
  `rdpZRotation` double(8,2) NOT NULL,
  `rdpDateTime` datetime NOT NULL,
  `sessionID` bigint(20) NOT NULL,
  `rdpDeleted` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=819814 ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sessionStartTime` datetime NOT NULL,
  `sessionEndTime` datetime NOT NULL,
  `loginID` bigint(20) NOT NULL,
  `sessionQuality` bigint(20) NOT NULL,
  `sessionTypeID` int(11) NOT NULL,
  `sessionDeleted` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=147 ;

-- --------------------------------------------------------

--
-- Table structure for table `session_types`
--

CREATE TABLE IF NOT EXISTS `session_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stDeleted` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
