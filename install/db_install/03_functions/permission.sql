CREATE FUNCTION `permission`(
	a_name VARCHAR(50)
) RETURNS INT(10)
DETERMINISTIC
READS SQL DATA
SQL SECURITY INVOKER
BEGIN
	DECLARE t_id INT(10);
	SELECT id
	INTO t_id
	FROM permissions
	WHERE name = a_name;
	IF t_id IS NULL THEN
		SET @error := CONCAT('Permission "',a_name,'" is not defined');
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = @error;
	END IF;
	return t_id;
END;