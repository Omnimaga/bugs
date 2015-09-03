-- --------------------------------------------------------

--
-- Table structure for table `r_issue_role_permission`
--
-- Creation: Sep 03, 2015 at 12:02 PM
--

DROP TABLE IF EXISTS `r_issue_role_permission`;
CREATE TABLE IF NOT EXISTS `r_issue_role_permission` (
  `per_id` int(10) NOT NULL,
  `r_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `r_issue_role_permission`:
--

--
-- Indexes for table `r_issue_role_permission`
--
ALTER TABLE `r_issue_role_permission`
  ADD PRIMARY KEY (`per_id`,`r_id`);