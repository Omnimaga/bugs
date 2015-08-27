--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issues_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `issues_ibfk_3` FOREIGN KEY (`pr_id`) REFERENCES `priorities` (`id`),
  ADD CONSTRAINT `issues_ibfk_4` FOREIGN KEY (`s_id`) REFERENCES `statuses` (`id`);