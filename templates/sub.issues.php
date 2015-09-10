<?php
	global $context;
?>
<div>
	<h3>Issues</h3>
	<?php
		if(
			(
				$context instanceof Project &&
				$context->permission('issue.create')
			) ||
			(
				!$context instanceof Project &&
				Bugs::permission('issue.create')
			)
		){
	?>
		<a href="<?=Router::url(Router::$base.($context instanceof Project?"/project/{$context->name}/":'').'/create/issue')?>">New</a>
	<?php
		}
	?>
	<ul>
		<?php
			foreach($context->issues as $issue){
				echo "<li>({$issue->status} - {$issue->priority}) <a href=\"".Router::url(Router::$base."/!{$issue->id}")."\">{$issue->name}</a></li>";
			}
		?>
	</ul>
</div>