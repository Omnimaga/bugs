<?php
	// Expecting the context to be a user
	global $context;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Login</title>
		<script src="js/juju/core.js"></script>
		<script src="js/juju/page.js"></script>
		<script src="js/juju/dom.js"></script>
		<script src="js/juju/keyboard.js"></script>
		<script src="js/juju/mouse.js"></script>
		<link rel="stylesheet" href="css/main.css"></link>
	</head>
	<body>
		<a href="<?=Router::url(Router::$base)?>">Home</a>
		<?php
			if(!empty($context['error'])){
		?>
			<div class="error">
				<?=$context['error']?>
			</div>
		<?php
			}
		?>
		<form action="<?=Router::url(Router::$base.'/login/complete')?>" method="POST">
			<div>
				<label for="name">
					Name:
				</label>
				<input name="name"/>
			</div>
			<div>
				<label for="password">
					Password:
				</label>
				<input name="password" type="password"/>
			</div>
			<div>
				<input type="submit" value="Login"/>
			</div>
		</form>
	</body>
</html>