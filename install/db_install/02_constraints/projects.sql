--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `projects_ibfk_3` FOREIGN KEY (`s_id`) REFERENCES `statuses` (`id`);