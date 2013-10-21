<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	global $mysqli;
	function projectObj($id){
		if($res = query("SELECT * FROM `projects` WHERE id='%d'",Array($id))){
			if($res->num_rows == 1){
				return $res->fetch_assoc();
			}
		}
		return false;
	}
?>