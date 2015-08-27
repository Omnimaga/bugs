--
-- Constraints for table `r_permission_user`
--
ALTER TABLE `r_permission_user`
  ADD CONSTRAINT `r_permission_user_ibfk_1` FOREIGN KEY (`per_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_permission_user_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;