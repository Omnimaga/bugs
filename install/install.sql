-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2014 at 05:14 AM
-- Server version: 5.6.11
-- PHP Version: 5.5.3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `issues`
--
-- Creation: Apr 06, 2014 at 11:26 PM
--

DROP TABLE IF EXISTS `issues`;
CREATE TABLE IF NOT EXISTS `issues` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `u_id` int(100) NOT NULL,
  `s_id` int(100) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `pr_id` int(100) DEFAULT NULL,
  `st_id` int(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`),
  KEY `u_id_2` (`u_id`),
  KEY `s_id` (`s_id`),
  KEY `pr_id` (`pr_id`,`st_id`),
  KEY `st_id` (`st_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- RELATIONS FOR TABLE `issues`:
--   `pr_id`
--       `priorities` -> `id`
--   `st_id`
--       `statuses` -> `id`
--   `u_id`
--       `users` -> `id`
--   `s_id`
--       `scrums` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--
-- Creation: Nov 18, 2013 at 07:30 PM
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `from_id` int(100) NOT NULL,
  `to_id` int(100) DEFAULT NULL,
  `p_id` int(11) DEFAULT NULL,
  `s_id` int(100) DEFAULT NULL,
  `i_id` int(100) DEFAULT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `from_id` (`from_id`,`to_id`,`s_id`,`i_id`),
  KEY `to_id` (`to_id`),
  KEY `s_id` (`s_id`),
  KEY `i_id` (`i_id`),
  KEY `p_id` (`p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- RELATIONS FOR TABLE `messages`:
--   `from_id`
--       `users` -> `id`
--   `to_id`
--       `users` -> `id`
--   `s_id`
--       `scrums` -> `id`
--   `i_id`
--       `issues` -> `id`
--   `p_id`
--       `projects` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `priorities`
--
-- Creation: Apr 06, 2014 at 11:24 PM
--

DROP TABLE IF EXISTS `priorities`;
CREATE TABLE IF NOT EXISTS `priorities` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--
-- Creation: Oct 24, 2013 at 05:53 PM
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `u_id` int(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- RELATIONS FOR TABLE `projects`:
--   `u_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `rels`
--
-- Creation: Oct 07, 2013 at 08:42 PM
--

DROP TABLE IF EXISTS `rels`;
CREATE TABLE IF NOT EXISTS `rels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL,
  `i_id` int(11) DEFAULT NULL,
  `s_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`,`i_id`,`s_id`),
  KEY `i_id` (`i_id`),
  KEY `s_id` (`s_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- RELATIONS FOR TABLE `rels`:
--   `u_id`
--       `users` -> `id`
--   `i_id`
--       `issues` -> `id`
--   `s_id`
--       `scrums` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `scrums`
--
-- Creation: Oct 07, 2013 at 08:42 PM
--

DROP TABLE IF EXISTS `scrums`;
CREATE TABLE IF NOT EXISTS `scrums` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `p_id` int(100) NOT NULL,
  `u_id` int(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`),
  KEY `p_id` (`p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- RELATIONS FOR TABLE `scrums`:
--   `u_id`
--       `users` -> `id`
--   `p_id`
--       `projects` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--
-- Creation: Apr 06, 2014 at 11:22 PM
--

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Nov 25, 2013 at 06:32 PM
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(25) NOT NULL,
  `key` varchar(128) NOT NULL,
  `last_pm_check` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Table structure for table `actions`
--
-- Creation: Apr 13, 2014 at 10:14 PM
--

DROP TABLE IF EXISTS `actions`;
CREATE TABLE IF NOT EXISTS `actions` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--
-- Creation: Apr 14, 2014 at 12:47 AM
--

DROP TABLE IF EXISTS `activity`;
CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_id` int(100) DEFAULT NULL,
  `p_id` int(100) DEFAULT NULL,
  `s_id` int(100) DEFAULT NULL,
  `i_id` int(100) DEFAULT NULL,
  `m_id` int(100) DEFAULT NULL,
  `a_id` int(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`,`p_id`,`s_id`,`i_id`,`m_id`,`a_id`),
  KEY `u_id_2` (`u_id`),
  KEY `p_id` (`p_id`),
  KEY `s_id` (`s_id`),
  KEY `i_id` (`i_id`),
  KEY `m_id` (`m_id`),
  KEY `a_id` (`a_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- RELATIONS FOR TABLE `activity`:
--   `a_id`
--       `actions` -> `id`
--   `i_id`
--       `issues` -> `id`
--   `m_id`
--       `messages` -> `id`
--   `p_id`
--       `projects` -> `id`
--   `s_id`
--       `scrums` -> `id`
--   `u_id`
--       `users` -> `id`
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_ibfk_3` FOREIGN KEY (`s_id`) REFERENCES `scrums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_ibfk_4` FOREIGN KEY (`i_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_ibfk_5` FOREIGN KEY (`m_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_ibfk_6` FOREIGN KEY (`a_id`) REFERENCES `actions` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issues_ibfk_2` FOREIGN KEY (`s_id`) REFERENCES `scrums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issues_ibfk_3` FOREIGN KEY (`st_id`) REFERENCES `statuses` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `issues_ibfk_4` FOREIGN KEY (`pr_id`) REFERENCES `priorities` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`s_id`) REFERENCES `scrums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_4` FOREIGN KEY (`i_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_5` FOREIGN KEY (`p_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rels`
--
ALTER TABLE `rels`
  ADD CONSTRAINT `rels_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rels_ibfk_2` FOREIGN KEY (`i_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rels_ibfk_3` FOREIGN KEY (`s_id`) REFERENCES `scrums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scrums`
--
ALTER TABLE `scrums`
  ADD CONSTRAINT `scrums_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `scrums_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Truncate table before insert `priorities`
--

TRUNCATE TABLE `priorities`;
--
-- Dumping data for table `priorities`
--

INSERT INTO `priorities` (`id`, `name`, `color`) VALUES
(1, 'low', 'green'),
(2, 'medium', 'orange'),
(3, 'high', 'red');

--
-- Truncate table before insert `statuses`
--

TRUNCATE TABLE `statuses`;
--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`) VALUES
(1, 'New'),
(2, 'More Info'),
(3, 'In Progress'),
(4, 'Wont Fix'),
(5, 'Blocked'),
(6, 'Closed');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
