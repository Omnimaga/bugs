-- --------------------------------------------------------

--
-- Table structure for table `activities`
--
-- Creation: Aug 21, 2015 at 06:53 PM
--

DROP TABLE IF EXISTS `activities`;
CREATE TABLE IF NOT EXISTS `activities` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `a_id` int(10) NOT NULL,
  `data` varchar(4000) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `activities`:
--   `a_id`
--       `actions` -> `id`
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`date`),
  ADD KEY `a_id` (`a_id`);