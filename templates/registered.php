<?php
	// Expecting the context to be a user
	global $context;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Register</title>
		<script src="js/juju/core.js"></script>
		<script src="js/juju/page.js"></script>
		<script src="js/juju/dom.js"></script>
		<script src="js/juju/keyboard.js"></script>
		<script src="js/juju/mouse.js"></script>
		<link rel="stylesheet" href="css/main.css"></link>
	</head>
	<body>
		<?=$context->name?> has been registered. An email has been sent to your email address with a link to activate your account.
	</body>
</html>