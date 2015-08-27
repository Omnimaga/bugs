-- --------------------------------------------------------

--
-- Table structure for table `projects`
--
-- Creation: Aug 20, 2015 at 12:10 AM
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) NOT NULL,
  `p_id` int(10) NOT NULL,
  `s_id` int(10) NOT NULL,
  `u_id` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `projects`:
--   `u_id`
--       `users` -> `id`
--   `p_id`
--       `projects` -> `id`
--   `s_id`
--       `statuses` -> `id`
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `s_id` (`s_id`);
  
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;