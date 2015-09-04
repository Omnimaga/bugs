-- --------------------------------------------------------

--
-- Table structure for table `priorities`
--
-- Creation: Aug 20, 2015 at 12:10 AM
--

DROP TABLE IF EXISTS `priorities`;
CREATE TABLE IF NOT EXISTS `priorities` (
  `id` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `priorities`:
--

--
-- Indexes for table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`id`);
  
--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;