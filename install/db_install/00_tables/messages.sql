-- --------------------------------------------------------

--
-- Table structure for table `messages`
--
-- Creation: Aug 20, 2015 at 10:03 PM
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) NOT NULL,
  `u_id` int(10) NOT NULL,
  `i_id` int(10) DEFAULT NULL,
  `p_id` int(10) DEFAULT NULL,
  `subject` varchar(50) COLLATE utf8_bin NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `messages`:
--   `u_id`
--       `users` -> `id`
--   `i_id`
--       `issues` -> `id`
--   `p_id`
--       `projects` -> `id`
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `i_id` (`i_id`),
  ADD KEY `p_id` (`p_id`);

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;