<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'messages.php');
	function projectObj($id){
		if($res = query("SELECT p.title,p.id,p.description,u.name as user FROM `projects` p JOIN `users` u ON u.id = p.u_id  WHERE p.id='%d'",Array($id))){
			if($res->num_rows == 1){
				$project = $res->fetch_assoc();
				$project['user'] = userObj($project['user']);
				$project['comments'] = messages($project['id'],'project');
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
				if(query("INSERT INTO `projects` (title,description,u_id) VALUES ('%s','%s',%d)",Array($title,$description,$user))){
					return true;
				}
			}
		}
		return false;
	}
?>