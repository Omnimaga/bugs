<?php
	error_reporting(E_ALL);
	//ini_set('display_errors', 'Off');
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
?>