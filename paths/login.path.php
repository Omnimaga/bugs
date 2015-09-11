<?php
	Router::paths(array(
		'/login'=>function($res,$args){
			$res->write(
				Bugs::template('login')
			);
		},
		'/login/error'=>function($res,$args){
			$res->write(
				Bugs::template('login')
					->run(array(
						'error'=>'Invalid input.'
					))
			);
		},
		'/login/error/{error}'=>function($res,$args){
			$res->write(
				Bugs::template('login')
					->run(array(
						'error'=>$args->error
					))
			);
		},
		'/login/complete'=>function($res,$args){
			if(empty($_POST['name']) || empty($_POST['password'])){
				Router::redirect(Router::url(Router::$base.'/login/error'));
			}elseif(Bugs::login($_POST['name'],$_POST['password'])){
				// Manual header to fix issue
				Router::redirect(Router::url(Router::$base));
			}else{
				Router::redirect(Router::url(Router::$base.'/login/error/Login failed.'));
			}
		}
	));
?>