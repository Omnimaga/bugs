<?php
	Router::paths(array(
		'/logout'=>function($res,$args){
			session_destroy();
			// Manual header to fix issue
			$res->header('Location',Router::url(Router::$base));
		}
	));
?>