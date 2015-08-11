<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'Off');
	set_error_handler(function($errno, $errstr, $errfile, $errline){
		header('Content-Type: application/json');
		die(
			json_encode(
				array(
					'number'=> $errno,
					'msg'=> $errstr,
					'file'=> $errfile,
					'line'=> $errline,
					'backtrace'=> debug_backtrace(),
					'included'=> get_included_files()
				)
			)
		);
	},E_ALL);
	register_shutdown_function(function(){
		$error = error_get_last();
		if ($error['type'] == 1) {
			header('Content-Type: application/json');
			die(
				json_encode(
					array(
						'number'=> $error['type'],
						'msg'=> $error['message'],
						'file'=> $error['file'],
						'line'=> $error['line'],
						'backtrace'=> array(),
						'included'=> get_included_files()
					)
				)
			);
		} 
	});
	if($_SERVER['REQUEST_METHOD']!='GET'){
		$_RAWDATA = file_get_contents( 'php://input','r');
		$_DATA = json_decode($_RAWDATA,true);
	}else{
		$_RAWDATA = '';
		$_DATA = array();
	}
	require_once('lib/router.class.php');
	require_once('lib/sql.class.php');
	foreach(glob("paths/*.php") as $filename){
		require_once($filename);
	}
	Router::base('/bugs/');
	Router::handle(rtrim($_SERVER['REDIRECT_URL'],'/'),null,function($res,$url){
		header('Content-Type: application/json');
		$res->json(array(
			'error'=> 'Not implemented',
			'url'=> $url,
			'included'=> get_included_files(),
			'paths'=> Router::$paths
		));
	});
?>