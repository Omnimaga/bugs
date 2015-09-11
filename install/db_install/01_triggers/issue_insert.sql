DROP TRIGGER IF EXISTS `issue_insert`;
CREATE TRIGGER `issue_insert`
BEFORE INSERT ON `issues`
FOR EACH ROW BEGIN
	IF NEW.p_id IS NOT NULL AND NEW.name IN (
		SELECT name
		FROM issues
		WHERE p_id = NEW.p_id
	) THEN
		SET @error = CONCAT('An issue with the name "',NEW.name,'"" already exists on the project "',project_name(NEW.p_id),'"');
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = @error;
	ELSE
		SET new.date_modified = NOW();
	END IF;
END;