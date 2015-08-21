<?php
	Router::paths(array(
		'/!{issue}'=>function($res,$args){
			$res->header('Content-Type','application/json');
			$res->json(array(
				'issue'=> Bugs::issue($args->issue)
			));
		},
		'/issue/{issue}'=>function($res,$args){
			Router::redirect(Router::url(Router::$base.'/!'.$args->issue));
		}
	));
?>