<?php
	define('PATH_ROOT',realpath(dirname(__FILE__)).'/../');
	define('PATH_CONFIG',PATH_ROOT.'config.json');
	define('PATH_PHP',PATH_ROOT.'php/');
	define('PATH_JS',PATH_ROOT.'js/');
	define('PATH_CSS',PATH_ROOT.'css/');
	define('PATH_DATA',PATH_ROOT.'data/');
	$GLOBALS['config'] = json_decode(file_get_contents(PATH_CONFIG),true);
	function get($setting){
		return $GLOBALS['config'][$setting];
	}
	function set($setting,$value){
		$GLOBALS['config'][$setting] = $value;
		file_put_contents(PAT_CONFIG,json_encode($GLOBALS['config']));
		return $value;
	}
?>