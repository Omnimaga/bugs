DROP TRIGGER IF EXISTS `issue_insert_date_modified`;
CREATE TRIGGER `issue_insert_date_modified`
BEFORE INSERT ON `issues`
	FOR EACH ROW
		SET new.date_modified = NOW();