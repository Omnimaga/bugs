-- --------------------------------------------------------

--
-- Table structure for table `r_issue_user_needinfo`
--
-- Creation: Sep 11, 2015 at 11:31 AM
--

DROP TABLE IF EXISTS `r_issue_user_needinfo`;
CREATE TABLE IF NOT EXISTS `r_issue_user_needinfo` (
  `i_id` int(10) NOT NULL,
  `u_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `r_issue_user_needinfo`:
--   `i_id`
--       `issues` -> `id`
--   `u_id`
--       `users` -> `id`
--

--
-- Indexes for table `r_issue_user_needinfo`
--
ALTER TABLE `r_issue_user_needinfo`
  ADD UNIQUE KEY `i_id` (`i_id`,`u_id`),
  ADD KEY `u_id` (`u_id`);