DROP TRIGGER IF EXISTS `project_update`;
CREATE TRIGGER `project_update`
BEFORE UPDATE ON `projects`
FOR EACH ROW BEGIN
	IF new.name REGEXP '^[[:alpha:]]' THEN
		SET new.date_modified = NOW();
	ELSE
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Project names must start with a letter';
	END IF;
END;