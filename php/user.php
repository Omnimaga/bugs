<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'security.php');
	function addUser($username,$password,$email){
		$salt = get_sql()->escape_string(salt());
		$email = get_sql()->escape_string($email);
		$username = get_sql()->escape_string($username);
		$hash = get_sql()->escape_string(saltedHash($password,$salt));
		$res = query("INSERT INTO `users` (email,name,password,salt) VALUES ('%s','%s','%s','%s')",Array($email,$username,$hash,$salt));
		alog('u',mysqli_insert_id(get_sql()),'User Created');
		return $res;
	}
	function isUser($name){
		$res = query("SELECT id FROM `users` WHERE name='%s'",Array($name));
		return $res && $res->num_rows == 1;
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
		if($res = query("SELECT u.id,u.name,u.email,u.password,u.salt,u.key,u.last_pm_check FROM `users` AS u WHERE id=%d",Array($id))){
			if($res->num_rows == 1){
				if($user = $res->fetch_assoc()){
					unset($user['password']);
					unset($user['salt']);
					unset($user['key']);
					return $user;
				}
			}
		}
		return false;
	}
?>