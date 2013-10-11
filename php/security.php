<?php
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
	function securityKey($username,$salt){
		return saltedHash($username,$salt);
	}
	function authenticate(){
		global $SESSION;
		if(isset($_GET['key'])&&isset($SESSION['key'])&&isset($SESSION['username'])&&isUser($SESSION['username'])){
			if($_GET['key'] != $SESSION['key']){
				setKey(null);
				retj(Array('error'=>'Invalid key, you were logged out.'));
			}
			setKey($SESSION['key']);
		}else{
			setKey(null);
		}
	}
	function setKey($key){
		global $SESSION;
		if($key == null){
			unset($SESSION['key']);
			unset($SESSION['username']);
		}else{
			$SESSION['key'] = $key;
			setcookie('key',$key,time()+3600);
		}
	}
?>