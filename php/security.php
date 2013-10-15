<?php
	function salt(){
		return uniqid(mt_rand(0,61),true);
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
	function securityKey($username,$salt){
		return saltedHash($username,$salt);
	}
	function authenticate(){
		if(loggedIn()){
			setKey(getKey());
		}
	}
	function login($username,$password){
		global $LOGGEDIN;
		if($res = query("SELECT name,password,salt FROM `users` WHERE name = '%s'",Array($username))){
			if($res->num_rows == 1){
				$row = $res->fetch_assoc();
				if(compareSaltedHash($password,$row['salt'],$row['password'])){
					$_SESSION['username'] = $username;
					$key = securityKey($username,$_SERVER['REMOTE_ADDR']);
					setKey($key);
					$LOGGEDIN = true;
					return $key;
				}
			}
		}
		return false;
	}
	function loggedIn(){
		global $LOGGEDIN;
		global $_COOKIE;
		if(isset($_COOKIE['username'])&&isset($_COOKIE['key'])){
			if(securityKey($_COOKIE['username'],$_SERVER['REMOTE_ADDR'])==$_COOKIE['key']){
				$_SESSION['username'] = $_COOKIE['username'];
				setKey($_COOKIE['key']);
				$LOGGEDIN = true;
				return true;
			}
		}
		setKey(null);
		$LOGGEDIN = false;
		return false;
	}
	function setKey($key){
		if($key == null){
			unset($_SESSION['key']);
			unset($_SESSION['username']);
		}else{
			$_SESSION['key'] = $key;
			setcookie('username',$_SESSION['username'],time()+get('timeout'));
			setcookie('key',$key,time()+get('timeout'));
		}
		return $key;
	}
	function getKey(){
		return isset($_SESSION['key'])?$_SESSION['key']:null;
	}
?>