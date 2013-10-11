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
		die(saltedHash($pass,$salt)."\n".$hash);
		return $hash == saltedHash($pass,$salt);
	}
	function securityKey($username,$salt){
		return saltedHash($username,$salt);
	}
?>