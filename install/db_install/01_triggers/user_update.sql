DROP TRIGGER IF EXISTS `user_update`;
CREATE TRIGGER `user_update`
BEFORE UPDATE ON `users`
	FOR EACH ROW
		SET new.date_modified = NOW();