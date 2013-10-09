<?php
	require_once(PATH_PHP.'config.php');
	$mysqli = new mysqli(get('host'),get('user'),get('password'),get('database'));
	if($mysqli->connect_errno){
		echo "Failed to connect to MySQL: ".$mysqli->connect_error;
	}
?>