DROP TRIGGER IF EXISTS `user_insert_date_modified`;
CREATE TRIGGER `user_insert_date_modified`
BEFORE INSERT ON `users`
	FOR EACH ROW
		SET new.date_modified = NOW();