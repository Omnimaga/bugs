-- --------------------------------------------------------

--
-- Table structure for table `emails`
--
-- Creation: Aug 21, 2015 at 06:53 PM
--

DROP TABLE IF EXISTS `emails`;
CREATE TABLE IF NOT EXISTS `emails` (
  `u_id` int(10) NOT NULL,
  `subject` varchar(77) COLLATE utf8_bin NOT NULL,
  `body` text COLLATE utf8_bin NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `emails`:
--

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD KEY `u_id` (`u_id`),
  ADD KEY `date` (`date_created`);