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
	function login($username,$password){
		global $mysqli;
		if($res = query("SELECT name,password,salt FROM `users` WHERE name = '%s'",Array($username))){
			if($res->num_rows == 1){
				$row = $res->fetch_assoc();
				if(compareSaltedHash($password,$row['salt'],$row['password'])){
					return securityKey($username,salt());
				}			
			}
		}
		return false;
	}
	function isUser($name){
		if(query("SELECT id FROM `users` WHERE name='%s'",Array($name))){
			return true;
		}else{
			return false;
		}
	}
?>