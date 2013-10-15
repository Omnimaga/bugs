<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'security.php');
	$mysqli = $GLOBALS['mysqli'];
	function addUser($username,$password,$email){
		global $mysqli;
		$salt = $mysqli->escape_string(salt());
		$email = $mysqli->escape_string($email);
		$username = $mysqli->escape_string($username);
		$hash = $mysqli->escape_string(saltedHash($password,$salt));
		return query("INSERT INTO `users` (email,name,password,salt) VALUES ('%s','%s','%s','%s')",Array($email,$username,$hash,$salt));
	}
	function isUser($name){
		$res = query("SELECT id FROM `users` WHERE name='%s'",Array($name));
		if($res->num_rows == 1){
			return true;
		}else{
			return false;
		}
	}
	function userId($name){
		if($user = query("SELECT id FROM `users` WHERE name='%s'",Array($name))){
			if($user->num_rows == 1){
				$user = $user->fetch_assoc();
				return $user['id'];
			}
		}
		return false;
	}
	function userObj($id){
		if(is_string($id)){
			$id = userId($id);
		}
		if($res = query("SELECT * FROM `users` WHERE id='%d'",Array($id))){
			if($res->num_rows == 1){
				return $res->fetch_assoc();
			}
		}
		return false;
	}
?>