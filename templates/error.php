<?php
	global $context;
	if(!function_exists('get_class_name')){
		function get_class_name($obj){
			$name = @get_class($obj);
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
	}
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Error</title>
		<script src="<?=Router::$base?>/js/juju/core.js"></script>
		<script src="<?=Router::$base?>/js/juju/page.js"></script>
		<script src="<?=Router::$base?>/js/juju/dom.js"></script>
		<script src="<?=Router::$base?>/js/juju/keyboard.js"></script>
		<script src="<?=Router::$base?>/js/juju/mouse.js"></script>
		<script src="<?=Router::$base?>/js/error.js"></script>
		<link rel="stylesheet" href="<?=Router::$base?>/css/main.css"></link>
		<link rel="stylesheet" href="<?=Router::$base?>/css/error.css"></link>
	</head>
	<body>
		<h2>
			<?=$context->error['message']?>
		</h2>
		<br/>
		<?php
			if(defined('DEBUG') && DEBUG){
		?>
			<div class="error">
				<span class="collapse-arrow collapsed">
					&#10097;
				</span>
				<span>
					Call Stack
				</span>
				<div class="collapsable collapsed">
					<?php
						foreach($context->backtrace as $k => $trace){
							echo "<div><span class=\"collapse-arrow collapsed\">&#10097;</span>&nbsp;";
							if(isset($trace['class'])){
								echo "<span>{$trace['class']}{$trace['type']}{$trace['function']}</span>";
							}elseif(isset($trace['function'])){
								echo "<span>{$trace['function']}</span>";
							}
							if(isset($trace['file'])){
								echo "<span class=\"right\">{$trace['file']}:{$trace['line']}</span>";
							}
							echo "<div class=\"collapsable collapsed\">";
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
							echo "</div><div class=\"clear\"></div></div>";
						}
					?>
				</div>
			</div>
			<div class="error">
				<span class="collapse-arrow collapsed">
					&#10097;
				</span>
				<span>
					Included Files
				</span>
				<div class="collapsable collapsed">
					<?php
						foreach($context->included as $file){
							echo "<div>{$file}</div>";
						}
					?>
				</div>
			</div>
		<?php
			}
		?>
	</body>
</html>