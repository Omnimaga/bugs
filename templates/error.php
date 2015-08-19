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
		<link rel="stylesheet" href="css/error.css"></link>
	</head>
	<body>
		<h2>
			<?=$context->error['message']?>
		</h2>
		<div class="error">
			<?php
				foreach($context->backtrace as $trace){
					echo "<div><span class=\"collapse-arrow collapsed\">&#10097;</span>&nbsp;";
					if(isset($trace['class'])){
						echo "<span>Function: {$trace['class']}{$trace['type']}{$trace['function']}</span>";
					}elseif(isset($trace['function'])){
						echo "<span>Function: {$trace['function']}</span>";
					}
					echo "<div class=\"collapsable collapsed\">";
					if(isset($trace['file'])){
						echo "<div>Location: {$trace['file']}:{$trace['line']}</div>";
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
					echo "</div></div>";
				}
			?>
		</div>
	</body>
</html>