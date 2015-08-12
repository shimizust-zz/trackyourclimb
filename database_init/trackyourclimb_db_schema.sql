-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 09, 2015 at 10:50 PM
-- Server version: 5.5.36-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trackyou_climbtracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(80) NOT NULL,
  `gymid` int(11) NOT NULL,
  `event_startdate` date NOT NULL,
  `event_enddate` date NOT NULL,
  `event_desc` text NOT NULL,
  `event_website` varchar(512) NOT NULL,
  `event_facebook` varchar(512) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- Table structure for table `eventtags`
--

CREATE TABLE IF NOT EXISTS `eventtags` (
  `event_tagid` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) NOT NULL,
  `tag_desc_name` varchar(150) NOT NULL,
  PRIMARY KEY (`event_tagid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_eventtags`
--

CREATE TABLE IF NOT EXISTS `event_eventtags` (
  `event_id` int(11) NOT NULL,
  `event_tagid` int(11) NOT NULL,
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gyms`
--

CREATE TABLE IF NOT EXISTS `gyms` (
  `gymid` int(11) NOT NULL AUTO_INCREMENT,
  `gym_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `countryCode` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `indoor` tinyint(1) NOT NULL,
  PRIMARY KEY (`gymid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=396 ;

-- --------------------------------------------------------

--
-- Table structure for table `password_change_requests`
--

CREATE TABLE IF NOT EXISTS `password_change_requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time_request` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `request_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

CREATE TABLE IF NOT EXISTS `userdata` (
  `userid` int(11) NOT NULL,
  `firstname` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date NOT NULL,
  `userimage` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userimage_thumb` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date_climbingstart` date DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `armspan` int(11) DEFAULT NULL,
  `apeindex` decimal(10,0) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `gender` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `main_gym` int(11) DEFAULT NULL,
  `aboutme` text COLLATE utf8_unicode_ci NOT NULL,
  `countryCode` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `main_crag` int(11) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contains optional user information';

-- --------------------------------------------------------

--
-- Table structure for table `userprefs`
--

CREATE TABLE IF NOT EXISTS `userprefs` (
  `userid` int(11) NOT NULL,
  `show_boulder` tinyint(1) NOT NULL,
  `show_TR` tinyint(1) NOT NULL,
  `show_Lead` tinyint(1) NOT NULL,
  `show_project` tinyint(1) NOT NULL,
  `show_redpoint` tinyint(1) NOT NULL,
  `show_flash` tinyint(1) NOT NULL,
  `show_onsight` tinyint(1) NOT NULL,
  `minV` tinyint(4) NOT NULL,
  `maxV` tinyint(4) NOT NULL,
  `minTR` tinyint(4) NOT NULL,
  `maxTR` tinyint(4) NOT NULL,
  `minL` tinyint(4) NOT NULL,
  `maxL` tinyint(4) NOT NULL,
  `boulderGradingSystemID` int(11) NOT NULL,
  `routeGradingSystemID` int(11) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userrecords`
--

CREATE TABLE IF NOT EXISTS `userrecords` (
  `userid` int(11) NOT NULL,
  `highestBoulderProject` int(11) NOT NULL,
  `highestBoulderRedpoint` int(11) NOT NULL,
  `highestBoulderFlash` int(11) NOT NULL,
  `highestBoulderOnsight` int(11) NOT NULL,
  `highestTRProject` int(11) NOT NULL,
  `highestTRRedpoint` int(11) NOT NULL,
  `highestTRFlash` int(11) NOT NULL,
  `highestTROnsight` int(11) NOT NULL,
  `highestLeadProject` int(11) NOT NULL,
  `highestLeadRedpoint` int(11) NOT NULL,
  `highestLeadFlash` int(11) NOT NULL,
  `highestLeadOnsight` int(11) NOT NULL,
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contain workout records (e.g. highest grades achieved) for quicker lookup';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pass_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contains basic user login information' AUTO_INCREMENT=963 ;

-- --------------------------------------------------------

--
-- Table structure for table `verify_requests`
--

CREATE TABLE IF NOT EXISTS `verify_requests` (
  `userid_new` int(11) NOT NULL,
  `verify_hash` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

CREATE TABLE IF NOT EXISTS `workouts` (
  `workout_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `date_workout` date NOT NULL,
  `gymid` int(11) NOT NULL,
  `boulder_points` int(11) NOT NULL,
  `TR_points` int(11) NOT NULL,
  `Lead_points` int(11) NOT NULL,
  `boulder_notes` text COLLATE utf8_unicode_ci NOT NULL,
  `tr_notes` text COLLATE utf8_unicode_ci NOT NULL,
  `lead_notes` text COLLATE utf8_unicode_ci NOT NULL,
  `other_notes` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`workout_id`),
  KEY `userid` (`userid`),
  KEY `gymid` (`gymid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4239 ;

-- --------------------------------------------------------

--
-- Table structure for table `workout_segments`
--

CREATE TABLE IF NOT EXISTS `workout_segments` (
  `workout_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL AUTO_INCREMENT,
  `climb_type` varchar(20) NOT NULL,
  `ascent_type` varchar(20) NOT NULL,
  `grade_index` int(11) NOT NULL,
  `reps` int(11) NOT NULL,
  PRIMARY KEY (`segment_id`),
  KEY `workout_id` (`workout_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22873 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `userdata`
--
ALTER TABLE `userdata`
  ADD CONSTRAINT `userdata_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userprefs`
--
ALTER TABLE `userprefs`
  ADD CONSTRAINT `userprefs_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userrecords`
--
ALTER TABLE `userrecords`
  ADD CONSTRAINT `userrecords_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workouts`
--
ALTER TABLE `workouts`
  ADD CONSTRAINT `workouts_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `workouts_ibfk_2` FOREIGN KEY (`gymid`) REFERENCES `gyms` (`gymid`);

--
-- Constraints for table `workout_segments`
--
ALTER TABLE `workout_segments`
  ADD CONSTRAINT `workout_segments_ibfk_1` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`workout_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
