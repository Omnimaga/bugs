-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--
-- Creation: Aug 27, 2015 at 04:22 PM
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `permissions`:
--

--
-- Dumping data for table `priorities`
--

INSERT INTO `permissions` (`id`, `name`) VALUES
(1,  '*'),
(3,  'project-read'),
(4,  'project-update'),
(5,  'project-create'),
(6,  'project-delete'),
(7,  'issue-read'),
(8,  'issue-update'),
(9,  'issue-create'),
(10, 'issue-delete'),
(11, 'user-read'),
(12, 'user-update'),
(13, 'user-create'),
(14, 'user-delete'),
(15, 'message-read'),
(16, 'message-update'),
(17, 'message-create'),
(18, 'message-delete'),
(19, 'priority-read'),
(20, 'priority-update'),
(21, 'priority-create'),
(23, 'priority-delete'),
(24, 'session-read'),
(25, 'session-update'),
(26, 'session-create'),
(27, 'session-delete'),
(28, 'issue-role-read'),
(29, 'issue-role-update'),
(30, 'issue-role-create'),
(31, 'issue-role-delete'),
(32, 'project-role-read'),
(33, 'project-role-update'),
(34, 'project-role-create'),
(35, 'project-role-delete'),
(36, 'email-read'),
(37, 'email-update'),
(38, 'email-create'),
(39, 'email-delete');

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;