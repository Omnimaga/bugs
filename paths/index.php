<?php
	Router::paths(array(
		'/'=>function($res,$args){
			$res->write('Hello World');
		}
	));
?>