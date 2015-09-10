CREATE FUNCTION `project_name`(
	a_id INT(10)
) RETURNS VARCHAR(50)
DETERMINISTIC
READS SQL DATA
SQL SECURITY INVOKER
BEGIN
	DECLARE t_name VARCHAR(50);
	SELECT name
	INTO t_name
	FROM projects
	WHERE id = a_id;
	IF t_name IS NULL THEN
		SET @error := CONCAT('Project with id ',a_id,' does not exist');
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = @error;
	END IF;
	return t_name;
END;