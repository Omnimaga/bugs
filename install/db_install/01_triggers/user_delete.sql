DROP TRIGGER IF EXISTS `user_delete`;
CREATE TRIGGER `user_delete`
BEFORE DELETE ON `users`
	FOR EACH ROW
		DELETE FROM sessions
		WHERE u_id = old.id;