<?php
	error_reporting(E_ALL);
	//ini_set('display_errors', 'Off');
	function error_handle_type($type=null){
		static $etype;
		if(!$etype){
			$etype = 'html';
		}
		if(!empty($type)){
			$etype = $type;
		}
		return $etype;
	}
	function handle_error($error,$backtrace,$included){
		$context = array(
			'error'=>$error,
			'backtrace'=>$backtrace,
			'included'=>$included
		);
		Router::clear();
		switch(error_handle_type()){
			case 'json':
				Router::json($context);
			break;
			case 'html':default:
				Router::write(
					Bugs::template('error')
						->run(new Arguments($context))
				);
		}
		die();
	}
	set_error_handler(function($errno, $errstr, $errfile, $errline){
		handle_error(
			array(
				'type'=> $errno,
				'message'=> $errstr,
				'file'=> $errfile,
				'line'=> $errline
			),
			debug_backtrace(),
			get_included_files()
		);
	},E_ALL);
	register_shutdown_function(function(){
		$error = error_get_last();
		if(!is_null($error) && $error['type'] == 1){
			handle_error(
				$error,
				debug_backtrace(),
				get_included_files()
			);
		} 
	});
?>