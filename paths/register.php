<?php
	Bugs::actions(
		'user.register',
		'user.activate'
	);
	Router::paths(array(
		'/register'=>function($res,$args){
			$res->write(
				Bugs::template('register')
			);
		},
		'/register/error'=>function($res,$args){
			$res->write(
				Bugs::template('register')
					->run(array(
						'error'=>'Invalid input.'
					))
			);
		},
		'/register/error/{error}'=>function($res,$args){
			$res->write(
				Bugs::template('register')
					->run(array(
						'error'=>$args->error
					))
			);
		},
		'/register/complete'=>function($res,$args){
			if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])){
				Router::redirect(
					Router::url(Router::$base.'/register/error')
				);
			}else{
				if(!Bugs::user_id($_POST['name'])){
					$user = Bugs::user($_POST['name'],$_POST['email'],$_POST['password']);
					$res->write(
						Bugs::template('registered')
							->run($user)
					);
					$user->email('Registered',"
						<a href=\"http://".Bugs::setting('url.host').Bugs::setting('url.base')."/register/activate/{$user->name}/{$user->activation_code}\">Activate Account</a>
					");
					Bugs::activity('user.register',$user);
				}else{
					Router::redirect(
						Router::url(Router::$base."/register/error/User {$_POST['name']} already exists.")
					);
				}
			}
		},
		'/register/activate/{user}/{code}'=>function($res,$args){
			$user = Bugs::user($args->user);
			if($args->code == $user->activation_code){
				if(!$user->active){
					$user->active = true;
					$res->write(
						Bugs::template('activated')
							->run($user)
					);
					Bugs::activity('user.activate',$user);
				}else{
					trigger_error("User is already active");
				}
			}else{
				trigger_error("Invalid activation code for user {$args->user}");
			}
		}
	));
?>