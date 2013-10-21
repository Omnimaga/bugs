<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	global $mysqli;
	function projectObj($id){
		if($res = query("SELECT p.title,p.id,p.description,u.name as user FROM `projects` p JOIN `users` u ON u.id = p.u_id  WHERE p.id='%d'",Array($id))){
			if($res->num_rows == 1){
				$project = $res->fetch_assoc();
				$project['user'] = userObj($project['user']);
				return $project;
			}
		}
		return false;
	}
?>