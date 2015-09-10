DROP TRIGGER IF EXISTS `user_insert`;
CREATE TRIGGER `user_insert`
BEFORE INSERT ON `users`
	FOR EACH ROW
		SET new.date_modified = NOW();