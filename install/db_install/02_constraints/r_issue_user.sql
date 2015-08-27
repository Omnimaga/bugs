--
-- Constraints for table `r_issue_user`
--
ALTER TABLE `r_issue_user`
  ADD CONSTRAINT `r_issue_user_ibfk_1` FOREIGN KEY (`i_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_issue_user_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_issue_user_ibfk_3` FOREIGN KEY (`r_id`) REFERENCES `issue_roles` (`id`);
