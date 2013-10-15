<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'security.php');
	require_once(PATH_PHP.'user.php');
	function sendMail($template,$subject,$to=null,$from=null,$context=Array()){
		if(is_null($to)){
			$to = get('email');
		}
		if(is_null($from)){
			$from = get('email');
		}
		if(file_exists(PATH_DATA."/emails/{$template}.template")){
			$message = vsprintf(file_get_contents(PATH_DATA."/emails/{$template}.template"),$context);
			return @mail($to,$subject,$message,"From: {$from}\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\n");
		}else{
			return false;
		}
	}
?>