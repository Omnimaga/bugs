<?php
	Router::paths(array(
		'/timeline'=>function($res,$args){
			Router::redirect(Router::url(Router::$base.'/timeline/0/20'));
		},
		'/timeline/{page}'=>function($res,$args){
			Router::redirect(Router::url(Router::$base.'/timeline/'.$args->page.'/20'));
		},
		'/timeline/{page}/{amount}'=>function($res,$args){
			if(Bugs::$user){
				$res->write(
					Bugs::template('timeline')
						->run($args)
				);
			}else{
				Router::redirect(Router::$base);
			}
		}
	));
?>