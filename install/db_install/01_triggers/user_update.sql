DROP TRIGGER IF EXISTS `user_update`;
CREATE TRIGGER `user_update`
BEFORE UPDATE ON `users`
FOR EACH ROW BEGIN
	SET new.date_modified = NOW();
	UPDATE sessions
	SET u_id = new.id
	WHERE u_id = old.id;
END;