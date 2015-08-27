DROP TRIGGER IF EXISTS `message_update`;
CREATE TRIGGER `message_update`
BEFORE UPDATE ON `messages`
	FOR EACH ROW
		IF new.i_id IS NOT NULL AND new.p_id IS NOT NULL THEN
			SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'Messages can only be related to one thing';
		ELSE
			SET new.date_modified = NOW();
		END IF;