<?php
	if(!is_writable(session_save_path())){
		retj(Array(
			'error'=>'Session save path ('.session_save_path().') is not writable.'
		),'error');
	}
	@session_start();
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'functions.php');
	require_once(PATH_PHP.'security.php');
	require_once(PATH_PHP.'captcha.php');
	require_once(PATH_PHP.'user.php');
	require_once(PATH_PHP.'project.php');
	require_once(PATH_PHP.'emails.php');
	authenticate();
?>