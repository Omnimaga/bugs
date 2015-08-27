-- --------------------------------------------------------

--
-- Table structure for table `r_permission_user`
--
-- Creation: Aug 27, 2015 at 04:22 PM
--

DROP TABLE IF EXISTS `r_permission_user`;
CREATE TABLE IF NOT EXISTS `r_permission_user` (
  `per_id` int(10) NOT NULL,
  `u_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `r_permission_user`:
--

--
-- Indexes for table `r_permission_user`
--
ALTER TABLE `r_permission_user`
  ADD PRIMARY KEY (`per_id`,`u_id`);