<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	$mysqli = new mysqli(get('host'),get('user'),get('password'),get('database'));
	if($mysqli->connect_errno){
		echo "Failed to connect to MySQL: ".$mysqli->connect_error;
	}
	$mysqli->autocommit(true);
	function query($query,$args=Array()){
		global $mysqli;
		for ($i=0;$i<count($args);$i++){
			if(is_string($args[$i])){
				$args[$i] = $mysqli->real_escape_string($args[$i]);
			}else{
				return false;
			}
		}
		return $mysqli->query(vsprintf($query,$args));
	}
	function fetch_all($result,$type=MYSQLI_NUM){
		if(method_exists('mysqli_result', 'fetch_all')){
			$res = $result->fetch_all($resulttype);
		}else{
			for($res = array(); $tmp = $result->fetch_array($resulttype);){
				$res[] = $tmp;
			}
		}
		return $res;
	}
?>