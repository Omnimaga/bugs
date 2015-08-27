-- --------------------------------------------------------

--
-- Table structure for table `r_project_user`
--
-- Creation: Aug 21, 2015 at 06:53 PM
--

DROP TABLE IF EXISTS `r_project_user`;
CREATE TABLE IF NOT EXISTS `r_project_user` (
  `p_id` int(10) NOT NULL,
  `u_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- RELATIONS FOR TABLE `r_project_user`:
--   `p_id`
--       `projects` -> `id`
--   `u_id`
--       `users` -> `id`
--   `r_id`
--       `project_roles` -> `id`
--

--
-- Indexes for table `r_project_user`
--
ALTER TABLE `r_project_user`
  ADD UNIQUE KEY `p_id` (`p_id`,`u_id`,`r_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `r_id` (`r_id`);