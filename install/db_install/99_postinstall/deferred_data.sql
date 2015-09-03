INSERT INTO `permissions` (`name`) VALUES
('*'),
('project-read'),
('project-update'),
('project-create'),
('project-delete'),
('issue-read'),
('issue-update'),
('issue-create'),
('issue-delete'),
('user-read'),
('user-update'),
('user-create'),
('user-delete'),
('message-read'),
('message-update'),
('message-create'),
('message-delete'),
('priority-read'),
('priority-update'),
('priority-create'),
('priority-delete'),
('session-read'),
('session-update'),
('session-create'),
('session-delete'),
('issue-role-read'),
('issue-role-update'),
('issue-role-create'),
('issue-role-delete'),
('project-role-read'),
('project-role-update'),
('project-role-create'),
('project-role-delete'),
('email-read'),
('email-update'),
('email-create'),
('email-delete');

INSERT INTO `issue_roles` (`name`) VALUES
('Developer'),
('Tester'),
('Contact');

INSERT INTO `r_issue_role_permission` (`r_id`,`per_id`) VALUES
(issue_role('Developer'),permission('issue-read')),
(issue_role('Developer'),permission('issue-update')),
(issue_role('Tester'),permission('issue-read')),
(issue_role('Tester'),permission('issue-update')),
(issue_role('Contact'),permission('issue-read'));

INSERT INTO `project_roles` (`name`) VALUES
('Project Manager'),
('Developer'),
('Tester');

INSERT INTO `r_project_role_permission` (`r_id`,`per_id`) VALUES
(project_role('Project Manager'),permission('issue-read')),
(project_role('Project Manager'),permission('issue-create')),
(project_role('Project Manager'),permission('issue-delete')),
(project_role('Project Manager'),permission('project-read')),
(project_role('Project Manager'),permission('project-update')),
(project_role('Project Manager'),permission('project-delete')),
(project_role('Developer'),permission('issue-create')),
(project_role('Developer'),permission('project-read')),
(project_role('Tester'),permission('project-read'));