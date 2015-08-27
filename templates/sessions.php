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
		<script src="js/juju/canvas.js"></script>
		<script src="js/juju/fetch.js"></script>
		<script src="js/sessions.js"></script>
		<link rel="stylesheet" href="css/main.css"></link>
	</head>
	<body>
		<a href="<?=Router::url(Router::$base)?>">Home</a>
		<ul>
			<?php
				foreach($context->sessions as $session){
					echo "<li>{$session['ip']}".($session['id']==Bugs::session()?" (You)":'')."<br/>{$session['info']}".($context->id == Bugs::$user->id?"<br/><button class=\"session-delete\" name=\"{$session['id']}\" value=\"Delete\">Delete</button>":'')."</li>";
				}
			?>
		</ul>
	</body>
</html>