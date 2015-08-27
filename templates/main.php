<?php
	global $context;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Bugs</title>
		<script src="<?=Router::$base?>/js/juju/core.js"></script>
		<script src="<?=Router::$base?>/js/juju/page.js"></script>
		<script src="<?=Router::$base?>/js/juju/dom.js"></script>
		<script src="<?=Router::$base?>/js/juju/keyboard.js"></script>
		<script src="<?=Router::$base?>/js/juju/mouse.js"></script>
		<link rel="stylesheet" href="<?=Router::$base?>/css/main.css"></link>
	</head>
	<body>
		<?php
			if(Bugs::$user){
		?>
			Hello World!
			<a href="<?=Router::url(Router::$base.'/sessions')?>">Sessions</a>
			<a href="<?=Router::url(Router::$base.'/logout')?>">Logout</a>
		<?php
			}else{
		?>
			<a href="<?=Router::url(Router::$base.'/login')?>">Login</a>
			<a href="<?=Router::url(Router::$base.'/register')?>">Register</a>
		<?php
			}
		?>
	</body>
</html>