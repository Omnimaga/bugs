<?php
	Router::paths(array(
		'/logout'=>function($res,$args){
			Bugs::logout();
			// Manual header to fix issue
			$res->header('Location',Router::url(Router::$base));
		}
	));
?>