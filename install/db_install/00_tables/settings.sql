-- --------------------------------------------------------

--
-- Table structure for table `settings`
--
-- Creation: Sep 02, 2015 at 11:35 AM
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `value` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `settings`:
--

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`name`);