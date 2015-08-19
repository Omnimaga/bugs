<?php
	global $context;
	function get_class_name($obj){
		$name = get_class($obj);
		if(!$name){
			if(is_string($obj)){
				$name = 'String';
			}elseif(is_numeric($obj)){
				$name = 'Number';
			}elseif(is_array($obj)){
				$name = 'Array';
			}elseif(is_null($obj)){
				$name = 'Null';
			}else{
				$name = 'Object';
			}

		}
		return $name;
	}
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
		<div>
			<strong>
				File:
			</strong>
			<?=$context->error['file']?>:<?=$context->error['line']?>
		</div>
		<br/>
		<div class="error">
			<span class="collapse-arrow collapsed">
				&#10097;
			</span>
			<span>
				Call Stack
			</span>
			<div class="collapsable collapsed">
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
								echo "<li><pre title=\"".get_class_name($arg)."\">".json_encode($arg,JSON_PRETTY_PRINT)."</pre></li>";
							}
							echo "</ul></div>";
						}
						if(isset($trace['object'])){
							echo "<div>Object:<pre title=\"".get_class_name($arg)."\">".json_encode($trace['object'],JSON_PRETTY_PRINT)."</pre></div>";
						}
						echo "</div></div>";
					}
				?>
			</div>
		</div>
	</body>
</html>