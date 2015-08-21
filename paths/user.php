<?php
	Bugs::actions(
		'view_profile'
	);
	Router::paths(array(
		'/~{user}'=>function($res,$args){
			$user = Bugs::user($args->user);
			if($user->active){
				$res->write(
					Bugs::template('user')
						->run($user)
				);
			}else{
				trigger_error("User {$args->user} is inactive");
			}
		},
		'/user/{user}'=>function($res,$args){
			Router::redirect(Router::url(Router::$base.'/~'.$args->user));
		}
	));
?>