-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--
-- Creation: Aug 20, 2015 at 12:10 AM
--

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `statuses`:
--

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`, `open`) VALUES
(1, 'New', 1),
(2, 'In Progress', 1),
(3, 'On Hold', 1),
(4, 'Completed', 0),
(5, 'Cancelled', 0);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);
  
--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;