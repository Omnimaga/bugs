--
-- Constraints for table `r_project_role_permission`
--
ALTER TABLE `r_project_role_permission`
  ADD CONSTRAINT `r_project_role_permission_ibfk_1` FOREIGN KEY (`per_id`) REFERENCES `permissions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `r_project_role_permission_ibfk_2` FOREIGN KEY (`r_id`) REFERENCES `project_roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;