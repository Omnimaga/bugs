<?php
	require_once(PATH_PHP.'config.php');
	$mysqli = new mysqli(get('host'),get('user'),get('password'),get('database'));
	if($mysqli->connect_errno){
		echo "Failed to connect to MySQL: ".$mysqli->connect_error;
	}
	$mysqli->autocommit(true);
	function query($query,$args = []){
		global $mysqli;
		for ($i=0;$i<count($args);$i++){
			$args[$i] = $mysqli->real_escape_string($args[$i]);
		}
		$result = $mysqli->query(vsprintf($query,$args));
		if (!$result){
			die("Query: ".vsprintf($query,$args));
		}
		return $result;
	}
?>