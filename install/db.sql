-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2015 at 01:15 AM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `bugs`
--
CREATE DATABASE IF NOT EXISTS `bugs` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bugs`;

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--
-- Creation: Aug 11, 2015 at 10:42 PM
--

DROP TABLE IF EXISTS `actions`;
CREATE TABLE IF NOT EXISTS `actions` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `actions`:
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--
-- Creation: Aug 11, 2015 at 10:59 PM
--

DROP TABLE IF EXISTS `activities`;
CREATE TABLE IF NOT EXISTS `activities` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `a_id` int(10) NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `activities`:
--   `a_id`
--       `actions` -> `id`
--   `a_id`
--       `actions` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--
-- Creation: Aug 11, 2015 at 10:52 PM
--

DROP TABLE IF EXISTS `issues`;
CREATE TABLE IF NOT EXISTS `issues` (
  `id` int(10) NOT NULL,
  `p_id` int(10) NOT NULL,
  `u_id` int(10) DEFAULT NULL,
  `pr_id` int(10) NOT NULL,
  `s_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `issues`:
--   `p_id`
--       `projects` -> `id`
--   `pr_id`
--       `priorities` -> `id`
--   `s_id`
--       `statuses` -> `id`
--   `u_id`
--       `users` -> `id`
--   `p_id`
--       `projects` -> `id`
--   `u_id`
--       `users` -> `id`
--   `pr_id`
--       `priorities` -> `id`
--   `s_id`
--       `statuses` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `priorities`
--
-- Creation: Aug 11, 2015 at 10:49 PM
--

DROP TABLE IF EXISTS `priorities`;
CREATE TABLE IF NOT EXISTS `priorities` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `priorities`:
--

--
-- Dumping data for table `priorities`
--

INSERT INTO `priorities` (`id`, `name`) VALUES
(1, 'Critical'),
(2, 'High'),
(3, 'Medium'),
(4, 'Low');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--
-- Creation: Aug 11, 2015 at 10:48 PM
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) NOT NULL,
  `p_id` int(10) NOT NULL,
  `s_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `u_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `projects`:
--   `p_id`
--       `projects` -> `id`
--   `s_id`
--       `statuses` -> `id`
--   `u_id`
--       `users` -> `id`
--   `u_id`
--       `users` -> `id`
--   `p_id`
--       `projects` -> `id`
--   `s_id`
--       `statuses` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--
-- Creation: Aug 11, 2015 at 10:16 PM
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `roles`:
--

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Developer'),
(2, 'Tester');

-- --------------------------------------------------------

--
-- Table structure for table `r_issue_user`
--
-- Creation: Aug 11, 2015 at 10:27 PM
--

DROP TABLE IF EXISTS `r_issue_user`;
CREATE TABLE IF NOT EXISTS `r_issue_user` (
  `i_id` int(10) NOT NULL,
  `u_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `r_issue_user`:
--   `i_id`
--       `issues` -> `id`
--   `u_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `r_project_user`
--
-- Creation: Aug 11, 2015 at 10:16 PM
--

DROP TABLE IF EXISTS `r_project_user`;
CREATE TABLE IF NOT EXISTS `r_project_user` (
  `p_id` int(10) NOT NULL,
  `u_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `r_project_user`:
--   `p_id`
--       `projects` -> `id`
--   `r_id`
--       `roles` -> `id`
--   `u_id`
--       `users` -> `id`
--   `p_id`
--       `projects` -> `id`
--   `u_id`
--       `users` -> `id`
--   `r_id`
--       `roles` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--
-- Creation: Aug 11, 2015 at 10:47 PM
--

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `statuses`:
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Aug 11, 2015 at 10:10 PM
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `users`:
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`date`),
  ADD KEY `a_id` (`a_id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`p_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `i_id` (`pr_id`),
  ADD KEY `s_id` (`s_id`);

--
-- Indexes for table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `s_id` (`s_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `r_issue_user`
--
ALTER TABLE `r_issue_user`
  ADD UNIQUE KEY `i_id` (`i_id`,`u_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `r_project_user`
--
ALTER TABLE `r_project_user`
  ADD UNIQUE KEY `p_id` (`p_id`,`u_id`,`r_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `r_id` (`r_id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`a_id`) REFERENCES `actions` (`id`);

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issues_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `issues_ibfk_3` FOREIGN KEY (`pr_id`) REFERENCES `priorities` (`id`),
  ADD CONSTRAINT `issues_ibfk_4` FOREIGN KEY (`s_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `projects_ibfk_3` FOREIGN KEY (`s_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `r_issue_user`
--
ALTER TABLE `r_issue_user`
  ADD CONSTRAINT `r_issue_user_ibfk_1` FOREIGN KEY (`i_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_issue_user_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `r_project_user`
--
ALTER TABLE `r_project_user`
  ADD CONSTRAINT `r_project_user_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_project_user_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_project_user_ibfk_3` FOREIGN KEY (`r_id`) REFERENCES `roles` (`id`);
COMMIT;
