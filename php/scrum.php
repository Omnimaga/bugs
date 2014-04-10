<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	function scrums($id){
		$arr = array();
		if($res = query("SELECT s.id,s.title,s.description,u.name as user FROM `scrums` s JOIN `users` u ON u.id = s.u_id WHERE s.p_id='%d' ORDER BY s.title DESC",array($id))){
			while($row = $res->fetch_assoc()){
				array_push($arr,$row);
			}
		}
		return $arr;
	}
	function scrumObj($id){
		if($res = query("SELECT s.id,s.title,s.description,u.name as user FROM `scrums` s JOIN `users` u ON u.id = s.u_id WHERE s.id='%d'",array($id))){
			if($res->num_rows == 1){
				$scrum = $res->fetch_assoc();
				$scrum['user'] = userObj($scrum['user']);
				return $scrum;
			}
		}
		return false;
	}
?>