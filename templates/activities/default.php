<?php
	global $context;
?>
<div>
	<time datetime="<?=date('c',$context->date);?>">
		<?=date('Y-m-d g:i:s A',$context->date);?>
	</time>
	<div>
		<?=$context->action?>
	</div>
	<pre><?=json_encode($context->data,JSON_PRETTY_PRINT)?></pre>
</div>