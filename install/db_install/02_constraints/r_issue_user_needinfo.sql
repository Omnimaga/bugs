--
-- Constraints for table `r_issue_user_needinfo`
--
ALTER TABLE `r_issue_user_needinfo`
  ADD CONSTRAINT `r_issue_user_needinfo_ibfk_1` FOREIGN KEY (`i_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_issue_user_needinfo_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_issue_user_needinfo_ibfk_3` FOREIGN KEY (`i_id`,`u_id`) REFERENCES `r_issue_user` (`i_id`,`u_id`);
