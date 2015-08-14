<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'Off');
	set_error_handler(function($errno, $errstr, $errfile, $errline){
		Router::write(
			Bugs::template('error')
				->run(new Arguments(array(
					'error'=> array(
						'type'=> $errno,
						'message'=> $errstr,
						'file'=> $errfile,
						'line'=> $errline
					),
					'backtrace'=> debug_backtrace(),
					'included'=> get_included_files()
				)))
		);
		die();
	},E_ALL);
	register_shutdown_function(function(){
		$error = error_get_last();
		if(!is_null($error) && $error['type'] == 1){
			Router::clear();
			Router::write(
				Bugs::template('error')
					->run(new Arguments(array(
						'error'=> $error,
						'backtrace'=> debug_backtrace(),
						'included'=> get_included_files()
					)))
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
	require_once('lib/bugs.class.php');
	Bugs::connect();
	Router::base('/bugs/');
	foreach(glob("paths/*.php") as $filename){
		require_once($filename);
	}
	Router::handle(rtrim($_SERVER['REDIRECT_URL'],'/'),null,function($res,$url){
		$res->header('Content-Type','application/json');
		$res->json(array(
			'error'=> 'Not implemented',
			'url'=> $url,
			'included'=> get_included_files(),
			'paths'=> Router::$paths
		));
	});
?>