<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'messages.php');
	function issueObj($id){
		if($res = query("SELECT i.id,i.title,i.description,i.s_id,u.name as user FROM `issues` i JOIN `users` u ON u.id = i.u_id  WHERE i.id='%d'",array($id))){
			if($res->num_rows == 1){
				$issue = $res->fetch_assoc();
				$issue['user'] = userObj($issue['user']);
				$issue['comments'] = messages($issue['id'],'issue');
				return $issue;
			}
		}
		return false;
	}
	function newIssue($title,$description,$user=null){
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