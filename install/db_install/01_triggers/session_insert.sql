DROP TRIGGER IF EXISTS `session_insert`;
CREATE TRIGGER `session_insert`
BEFORE INSERT ON `sessions`
FOR EACH ROW BEGIN
	IF new.u_id NOT IN (
		SELECT id
		FROM users
		WHERE active = 1
	) THEN
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Cannot create a session for this user';
	END IF;
END;