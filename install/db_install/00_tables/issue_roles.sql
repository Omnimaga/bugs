-- --------------------------------------------------------

--
-- Table structure for table `issue_roles`
--
-- Creation: Aug 20, 2015 at 10:03 PM
--

DROP TABLE IF EXISTS `issue_roles`;
CREATE TABLE IF NOT EXISTS `issue_roles` (
  `id` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `issue_roles`:
--

--
-- Indexes for table `issue_roles`
--
ALTER TABLE `issue_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for table `issue_roles`
--
ALTER TABLE `issue_roles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;