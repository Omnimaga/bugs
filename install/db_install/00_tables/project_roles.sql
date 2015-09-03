-- --------------------------------------------------------

--
-- Table structure for table `project_roles`
--
-- Creation: Aug 20, 2015 at 10:03 PM
--

DROP TABLE IF EXISTS `project_roles`;
CREATE TABLE IF NOT EXISTS `project_roles` (
  `id` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `project_roles`:
--

--
-- Indexes for table `project_roles`
--
ALTER TABLE `project_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `project_roles`
--
ALTER TABLE `project_roles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;