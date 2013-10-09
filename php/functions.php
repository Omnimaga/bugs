<?php
	@session_start();
	require_once(PATH_PHP.'database.php');
	// TODO - create php functions for the api
	function addUser($username,$password,$email){
		$salt = $mysqli->escape_string(salt());
		$email = $mysqli->escape_string($email);
		$username = $mysqli->escape_string($username);
		$hash = $mysqli->escape_string(saltedHash($password,$salt));
		return $mysqli->query("INSERT INTO `bugs`.`users` (email,name,pass,salt) VALUES '{$email}','{$username}','{$password}','{$salt}'");
	}
	function salt(){
		return uniqid(mt_rand(0,61), true);
	}
	function saltedHash($pass,$salt){
		$hash = $pass.$salt;
		for($i = 0;$i<50;$i++){
			$hash = hash('sha512',$pass.$hash.$salt);
		}
		return $hash;
	}
	function compareSaltedHash($pass,$salt,$hash){
		return $hash == saltedHash($pass,$salt);
	}
	
?>