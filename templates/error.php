<?php
	global $context;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Error</title>
		<script src="js/juju/core.js"></script>
		<script src="js/juju/page.js"></script>
		<script src="js/juju/dom.js"></script>
		<script src="js/juju/keyboard.js"></script>
		<script src="js/juju/mouse.js"></script>
		<script src="js/error.js"></script>
	</head>
	<body>
		<h2>
			<?=$context->error['message']?>
		</h2>
		<?php
			foreach($context->backtrace as $trace){
				echo "<div>";
				if(isset($trace['file'])){
					echo "<div>Location: {$trace['file']}:{$trace['line']}</div>";
				}
				if(isset($trace['class'])){
					echo "<div>Function: {$trace['class']}{$trace['type']}{$trace['function']}</div>";
				}elseif(isset($trace['function'])){
					echo "<div>Function: {$trace['function']}</div>";
				}
				if(isset($trace['args'])){
					echo "<div>Arguments:<ul>";
					foreach($trace['args'] as $arg){
						echo "<li><pre>".json_encode($arg,JSON_PRETTY_PRINT)."</pre></li>";
					}
					echo "</ul></div>";
				}
				if(isset($trace['object'])){
					echo "<div>Object:<pre>".json_encode($trace['object'],JSON_PRETTY_PRINT)."</pre></div>";
				}
				echo "</div>";
			}
		?>
	</body>
</html>