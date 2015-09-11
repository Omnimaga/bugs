<?php
	global $context;
?>
<div>
	<h3>Projects</h3>
	<?php
		if(Bugs::permission('project.create')){
	?>
		<a href="<?=Router::url(Router::$base.'/create/project')?>">New</a>
	<?php
		}
	?>
	<ul>
		<?php
			foreach($context->projects as $project){
				echo "<li>({$project->status}) <a href=\"".Router::url(Router::$base."/project/{$project->name}")."\">{$project->name}</a></li>";
			}
		?>
	</ul>
</div>