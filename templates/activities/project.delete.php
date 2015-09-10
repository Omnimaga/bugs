<?php
	global $context;
?>
<div>
	<time datetime="<?=date('c',$context->date);?>">
		<?=date('Y-m-d g:i:s A',$context->date);?>
	</time>
	<div>
		Project <?=$context->data->name?> deleted.
	</div>
</div>