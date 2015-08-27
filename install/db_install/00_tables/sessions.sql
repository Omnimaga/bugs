-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--
-- Creation: Aug 21, 2015 at 06:55 PM
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(256) COLLATE utf8_bin NOT NULL,
  `u_id` int(11) NOT NULL,
  `ip` varchar(39) COLLATE utf8_bin NOT NULL,
  `info` varchar(4000) COLLATE utf8_bin NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `sessions`:
--   `u_id`
--       `users` -> `id`
--

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`,`ip`);