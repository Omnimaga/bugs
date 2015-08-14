<?php
	global $context;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Error</title>
	</head>
	<body>
		<h2>
			<?=$context->error['message']?>
		</h2>
		<pre><?=print_r($context->backtrace,true)?></pre>
	</body>
</html>