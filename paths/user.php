<?php
	Bugs::actions(
		'user_update'
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
		},
		'/user/{user}/update'=>function($res,$args){
			if(Bugs::$user){
					if(!empty($_POST['password'])&&!empty($_POST['id'])&&!empty($_POST['name'])&&!empty($_POST['email'])){
					$user = Bugs::user(intval($args->user));
					if($user->password == $user->hash($_POST['password'])){
						$user->name = $_POST['name'];
						$user->email = $_POST['email'];
						$res->json(array(
							'name'=>$user->name
						));
						if($user->id == Bugs::$user->id){
							Bugs::login($user,$_POST['password']);
						}
						Bugs::activity('user_update',$_POST['name'].' has updated their profile.');
					}else{
						$res->json(array(
							'error'=>'Invalid password.'
						));
					}
				}else{
					$res->json(array(
						'error'=>'Missing information.'
					));
				}
			}else{
				$res->json(array(
					'error'=>'Not logged in.'
				));
			}
		}
	));
?>