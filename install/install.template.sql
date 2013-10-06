SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `$DATABASENAME`
--
CREATE DATABASE IF NOT EXISTS `$DATABASENAME` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `$DATABASENAME`;

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--
-- Creation: Oct 04, 2013 at 03:32 AM
--

DROP TABLE IF EXISTS `issues`;
CREATE TABLE IF NOT EXISTS `issues` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `u_id` int(100) NOT NULL,
  `s_id` int(100) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`),
  KEY `u_id_2` (`u_id`),
  KEY `s_id` (`s_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- RELATIONS FOR TABLE `issues`:
--   `s_id`
--       `scrums` -> `id`
--   `u_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--
-- Creation: Oct 05, 2013 at 02:24 AM
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `from_id` int(100) NOT NULL,
  `to_id` int(100) DEFAULT NULL,
  `s_id` int(100) DEFAULT NULL,
  `i_id` int(100) DEFAULT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `from_id` (`from_id`,`to_id`,`s_id`,`i_id`),
  KEY `to_id` (`to_id`),
  KEY `s_id` (`s_id`),
  KEY `i_id` (`i_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- RELATIONS FOR TABLE `messages`:
--   `i_id`
--       `issues` -> `id`
--   `from_id`
--       `users` -> `id`
--   `to_id`
--       `users` -> `id`
--   `s_id`
--       `scrums` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `rels`
--
-- Creation: Oct 06, 2013 at 06:52 PM
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
--   `s_id`
--       `scrums` -> `id`
--   `u_id`
--       `users` -> `id`
--   `i_id`
--       `issues` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `scrums`
--
-- Creation: Oct 04, 2013 at 03:33 AM
--

DROP TABLE IF EXISTS `scrums`;
CREATE TABLE IF NOT EXISTS `scrums` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `u_id` int(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- RELATIONS FOR TABLE `scrums`:
--   `u_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Oct 04, 2013 at 03:23 AM
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(50) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `salt` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_ibfk_2` FOREIGN KEY (`s_id`) REFERENCES `scrums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_4` FOREIGN KEY (`i_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`s_id`) REFERENCES `scrums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rels`
--
ALTER TABLE `rels`
  ADD CONSTRAINT `rels_ibfk_3` FOREIGN KEY (`s_id`) REFERENCES `scrums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rels_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rels_ibfk_2` FOREIGN KEY (`i_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scrums`
--
ALTER TABLE `scrums`
  ADD CONSTRAINT `scrums_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
