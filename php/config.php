<?php
	error_reporting(0);
	ob_start();
	// START ERROR HANDLING
	function shutdown_handler(){
		$error = error_get_last();
		if(!is_null($error)){
			ob_end_clean();
			switch($error['type']){
				case E_USER_ERROR:$errnostr='Fatal Error';break;
				case E_USER_WARNING:$errnostr='Warning';break;
				case E_USER_NOTICE:$errnostr='Notice';break;
				case E_ERROR:$errnostr='Error';break;
				case E_PARSE:$errnostr='Parse Error';break;
				case E_CORE_ERROR:$errnostr='Core Error';break;
				case E_CORE_WARNING:$errnostr='Core Warning';break;
				case E_COMPILE_ERROR:$errnostr='Compile Error';break;
				case E_COMPILE_WARNING:$errnostr='Compile Warning';break;
				//case E_:$errnostr='';break;
				default:$errnostr='Unkown Error';
			}
			echo json_encode(Array(
				'error'=>"\n{$errnostr}: {$error['message']} on {$error['file']}[{$error['line']}]"
			));
		}else{
			obj_end_flush();
		}
		echo PHP_EOL;
	}
	register_shutdown_function('shutdown_handler');
	// END ERROR HANDLING
	if(session_id() == ''){
		@session_start();
	}
	define('PATH_ROOT',realpath(dirname(__FILE__)).'/../');
	define('PATH_CONFIG',PATH_ROOT.'config.json');
	define('PATH_DEFAULT_CONFIG',PATH_ROOT.'config.default.json');
	define('PATH_PHP',PATH_ROOT.'php/');
	define('PATH_JS',PATH_ROOT.'js/');
	define('PATH_CSS',PATH_ROOT.'css/');
	define('PATH_DATA',PATH_ROOT.'data/');
	global $config;
	if(file_exists(PATH_CONFIG)){
		$config = objectToArray(json_decode(file_get_contents(PATH_CONFIG),true));
	}else{
		$config = Array();
	}
	$config = array_merge($config,objectToArray(json_decode(file_get_contents(PATH_DEFAULT_CONFIG),true)));
	function get($setting){
		global $config;
		if(isset($config[$setting])){
			return $config[$setting];
		}else{
			return false;
		}
	}
	function set($setting,$value){
		global $config;
		$config[$setting] = $value;
		file_put_contents(PAT_CONFIG,json_encode($config));
		return $value;
	}
	function objectToArray($d){
		if(is_object($d)){
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
		if(is_array($d)){
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}else{
			// Return array
			return $d;
		}
	}
	function arrayToObject($d){
		if(is_array($d)){
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return (object)array_map(__FUNCTION__, $d);
		}else{
			// Return object
			return $d;
		}
	}
?>