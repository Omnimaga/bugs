-- --------------------------------------------------------

--
-- Table structure for table `issues`
--
-- Creation: Aug 20, 2015 at 12:10 AM
--

DROP TABLE IF EXISTS `issues`;
CREATE TABLE IF NOT EXISTS `issues` (
  `id` int(10) NOT NULL,
  `p_id` int(10) NULL,
  `u_id` int(10) DEFAULT NULL,
  `pr_id` int(10) NOT NULL,
  `s_id` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` varchar(100) COLLATE utf8_bin NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `issues`:
--   `p_id`
--       `projects` -> `id`
--   `u_id`
--       `users` -> `id`
--   `pr_id`
--       `priorities` -> `id`
--   `s_id`
--       `statuses` -> `id`
--

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
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;