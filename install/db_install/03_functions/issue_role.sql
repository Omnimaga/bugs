CREATE FUNCTION `issue_role`(
	a_name VARCHAR(50)
) RETURNS INT(10)
DETERMINISTIC
READS SQL DATA
SQL SECURITY INVOKER
BEGIN
	DECLARE t_id INT(10);
	SELECT id
	INTO t_id
	FROM issue_roles
	WHERE name = a_name;
	IF t_id IS NULL THEN
		SET @error := CONCAT('Issue Role "',a_name,'" is not defined');
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = @error;
	END IF;
	return t_id;
END;