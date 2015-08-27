<?php
	// Expecting the context to be a user
	global $context;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Sessions for <?=$context->name?></title>
		<script src="js/juju/core.js"></script>
		<script src="js/juju/page.js"></script>
		<script src="js/juju/dom.js"></script>
		<script src="js/juju/keyboard.js"></script>
		<script src="js/juju/mouse.js"></script>
		<link rel="stylesheet" href="css/main.css"></link>
	</head>
	<body>
		<a href="<?=Router::url(Router::$base)?>">Home</a>
		<ul>
			<?php
				foreach($context->sessions as $session){
					echo "<li>{$session['ip']}<br/>{$session['info']}</li>";
				}
			?>
		</ul>
	</body>
</html>