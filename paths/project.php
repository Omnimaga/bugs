<?php
	Router::paths(array(
		'/project/{project}'=>function($res,$args){
			$res->header('Content-Type','application/json');
			$res->json(array(
				'project'=> Bugs::project($args->project)
			));
		},
		'/project/{project}/issue/{issue}'=>function($res,$args){
			$res->header('Location',Router::url(Router::$base."/!{$args->issue}"));
		},
		'/project/{project}/!{issue}'=>function($res,$args){
			$res->header('Location',Router::url(Router::$base."!{$args->issue}"));
		}
	));
?>