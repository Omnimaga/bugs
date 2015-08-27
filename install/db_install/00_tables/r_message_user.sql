-- --------------------------------------------------------

--
-- Table structure for table `r_message_user`
--
-- Creation: Aug 21, 2015 at 06:53 PM
--

DROP TABLE IF EXISTS `r_message_user`;
CREATE TABLE IF NOT EXISTS `r_message_user` (
  `m_id` int(10) NOT NULL,
  `u_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `r_message_user`:
--   `u_id`
--       `users` -> `id`
--   `m_id`
--       `messages` -> `id`
--

--
-- Indexes for table `r_message_user`
--
ALTER TABLE `r_message_user`
  ADD UNIQUE KEY `m_id` (`m_id`,`u_id`),
  ADD KEY `u_id` (`u_id`);