<?php
	global $context;
?>
<div>
	<time datetime="<?=date('c',$context->date);?>">
		<?=date('Y-m-d g:i:s A',$context->date);?>
	</time>
	<div>
		Project <a href="<?=Router::url(Router::$base."/project/{$context->data->name}")?>"><?=$context->data->name?></a> created.
	</div>
</div>