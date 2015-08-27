DROP TRIGGER IF EXISTS `user_update_date_modified`;
CREATE TRIGGER `user_update_date_modified`
BEFORE UPDATE ON `users`
	FOR EACH ROW
		SET new.date_modified = NOW();