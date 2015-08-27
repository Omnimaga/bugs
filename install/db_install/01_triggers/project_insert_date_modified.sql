DROP TRIGGER IF EXISTS `project_insert_date_modified`;
CREATE TRIGGER `project_insert_date_modified`
BEFORE INSERT ON `projects`
	FOR EACH ROW
		SET new.date_modified = NOW();