CREATE FUNCTION `setsetting`(
	a_name VARCHAR(50),
	a_value VARCHAR(100)
) RETURNS VARCHAR(100)
DETERMINISTIC
MODIFIES SQL DATA
SQL SECURITY INVOKER
BEGIN
	UPDATE settings
	SET value = a_value
	WHERE name = a_name;
	return getsetting(a_name);
END;