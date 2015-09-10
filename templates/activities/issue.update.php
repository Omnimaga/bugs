<?php
	global $context;
	if(is_null($context->data->p_id)){
		$project = false;
	}else{
		$project = Bugs::project(intval($context->data->p_id));
	}
?>
<div>
	<time datetime="<?=date('c',$context->date);?>">
		<?=date('Y-m-d g:i:s A',$context->date);?>
	</time>
	<div>
		Issue <a href="<?=Router::url(Router::$base."/!{$context->data->id}")?>"><?=$context->data->id?></a> updated<?=$project?" on Project <a href=\"".Router::url(Router::$base."/project/{$project->name}")."\">{$project->name}</a>":'.'?>
	</div>
</div>