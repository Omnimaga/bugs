DROP TRIGGER IF EXISTS `issue_update_date_modified`;
CREATE TRIGGER `issue_update_date_modified`
BEFORE UPDATE ON `issues`
	FOR EACH ROW
		SET new.date_modified = NOW();