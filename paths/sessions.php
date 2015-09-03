<?php
	Router::paths(array(
		'/sessions'=>function($res,$args){
			if(Bugs::$user){
				$res->write(
					Bugs::template('sessions')
						->run(Bugs::$user)
				);
			}else{
				Router::redirect(Router::$base);
			}
		},
		'/sessions/remove/{id}'=>function($res,$args){
			error_handle_type('json');
			if(Bugs::$user){
				Bugs::$sql->query("
					DELETE FROM sessions
					WHERE u_id = ?
					AND id = ?
				",'is',Bugs::$user->id,$args->id)->execute();
			}
			$res->json(array());
		}
	));
?>