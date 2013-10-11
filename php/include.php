<?php
	@session_start();
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'functions.php');
	require_once(PATH_PHP.'security.php');
	require_once(PATH_PHP.'user.php');
	if(isset($_GET['key'])&&isset($SESSION['key'])){
		if($_GET['key'] != $SESSION['key']){
			unset($SESSION['key']);
			retj(Array('error'=>'Invalid key, you were logged out.'));
		}
	}
?>