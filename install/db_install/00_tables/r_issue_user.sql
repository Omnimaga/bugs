-- --------------------------------------------------------

--
-- Table structure for table `r_issue_user`
--
-- Creation: Aug 21, 2015 at 06:53 PM
--

DROP TABLE IF EXISTS `r_issue_user`;
CREATE TABLE IF NOT EXISTS `r_issue_user` (
  `i_id` int(10) NOT NULL,
  `u_id` int(10) NOT NULL,
  `r_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `r_issue_user`:
--   `i_id`
--       `issues` -> `id`
--   `u_id`
--       `users` -> `id`
--   `r_id`
--       `issue_roles` -> `id`
--

--
-- Indexes for table `r_issue_user`
--
ALTER TABLE `r_issue_user`
  ADD UNIQUE KEY `i_id` (`i_id`,`u_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `r_id` (`r_id`);