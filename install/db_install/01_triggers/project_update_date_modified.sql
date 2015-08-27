DROP TRIGGER IF EXISTS `project_update_date_modified`;
CREATE TRIGGER `project_update_date_modified`
BEFORE UPDATE ON `projects`
	FOR EACH ROW
		SET new.date_modified = NOW();