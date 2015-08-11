<?php
	Router::paths(array(
		'/~{user}'=>function($res,$args){
			$res->write($args->user);
		}
	));
?>