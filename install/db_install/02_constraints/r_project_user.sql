--
-- Constraints for table `r_project_user`
--
ALTER TABLE `r_project_user`
  ADD CONSTRAINT `r_project_user_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_project_user_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_project_user_ibfk_3` FOREIGN KEY (`r_id`) REFERENCES `project_roles` (`id`);