<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'messages.php');
	function projectObj($id){
		if($res = query("SELECT p.title,p.id,p.description,u.name as user FROM `projects` p JOIN `users` u ON u.id = p.u_id  WHERE p.id='%d'",array($id))){
			if($res->num_rows == 1){
				$project = $res->fetch_assoc();
				$project['user'] = userObj($project['user']);
				$project['comments'] = messages($project['id'],'project');
				$project['scrums'] = scrums($project['id']);
				return $project;
			}
		}
		return false;
	}
	function newProject($title,$description,$user=null){
		global $LOGGEDIN;
		global $mysqli;
		if($LOGGEDIN){
			if(is_null($user)){
				$user = $_SESSION['username'];
			}
			$user = userId($user);
			if(false != $user){
				if($res = query("SELECT id FROM `projects` WHERE title = '%s' AND u_id = %d",array($title,$user))){
					if($res->num_rows){
						return false;
					}
				}
				if(query("INSERT INTO `projects` (title,description,u_id) VALUES ('%s','%s',%d)",array($title,$description,$user))){
					if($res = query("SELECT id FROM `projects` WHERE title = '%s' AND description = '%s' AND u_id = %d",array($title,$description,$user))){
						if($res->num_rows == 1){
							$res = $res->fetch_assoc();
							project_comment($res['id'],'Project created');
						}
						return true;
					}
				}
			}
		}
		return false;
	}
?>