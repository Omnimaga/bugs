<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	$mysqli = new mysqli(get('host'),get('user'),get('password'),get('database'));
	if($mysqli && $mysqli->connect_errno){
		die("Failed to connect to MySQL: ".$mysqli->connect_error);
	}
	if(!$mysqli->autocommit(true)){
		die("Failed to connect to MySQL: ".$mysqli->connect_error);
	}
	function query($query,$args=Array()){
		global $mysqli;
		for ($i=0;$i<count($args);$i++){
			if(is_string($args[$i])){
				$args[$i] = $mysqli->real_escape_string($args[$i]);
			}elseif(!is_numeric($args[$i])){
				return false;
			}
		}
		return $mysqli->query(vsprintf($query,$args));
	}
	function fetch_all($result,$type=MYSQLI_NUM){
		if(method_exists('mysqli_result', 'fetch_all')){
			$res = $result->fetch_all($type);
		}else{
			for($res = array(); $tmp = $result->fetch_array($type);){
				$res[] = $tmp;
			}
		}
		return $res;
	}
?>