--
-- Constraints for table `r_message_user`
--
ALTER TABLE `r_message_user`
  ADD CONSTRAINT `r_message_user_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `r_message_user_ibfk_2` FOREIGN KEY (`m_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;