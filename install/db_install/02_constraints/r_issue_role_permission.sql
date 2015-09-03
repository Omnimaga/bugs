--
-- Constraints for table `r_issue_role_permission`
--
ALTER TABLE `r_issue_role_permission`
  ADD CONSTRAINT `r_issue_role_permission_ibfk_1` FOREIGN KEY (`per_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_issue_role_permission_ibfk_2` FOREIGN KEY (`r_id`) REFERENCES `issue_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;