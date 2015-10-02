<?php
	// Expecting the context to be a issue or nothing at all
	global $context;
	$ctx = $context;
	($ctx?$ctx->permission('read'):Bugs::permission('issue.read')) or trigger_error('You are not allowed to view this issue');
	$update = $ctx?$ctx->permission('update'):Bugs::permission('issue.create');
	$delete = $ctx?$ctx->permission('delete'):Bugs::permission('issue.delete');
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Issue <?=($ctx?$ctx->name:null);?></title>
		<script src="<?=Router::url(Router::$base)?>/js/juju/core.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/juju/page.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/juju/dom.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/juju/keyboard.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/juju/mouse.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/issue.js"></script>
		<script>
			BASE_URL = '<?=Router::url(Router::$base)?>';
		</script>
		<link rel="stylesheet" href="<?=Router::url(Router::$base)?>/css/main.css"></link>
	</head>
	<body>
		<a href="<?=Router::url(Router::$base)?>">Home</a>
		<form id="form-issue" method="post">
			<div>
				<label for="name">Name:</label>
				<input value="<?=($ctx?$ctx->name:null);?>" <?=$update?'name="name"':'disabled="disabled"';?>/>
			</div>
			<div>
				<label for="description">Description:</label>
				<input type="description" value="<?=($ctx?$ctx->description:null);?>" <?=$update?'name="description"':'disabled="disabled"';?>/>
			</div>
			<?php
				if($ctx){
			?>
				<div>
					<label>Status:</label>
					<?=
						Bugs::template("subs/dropdown")
							->run(array(
								'value'=> $ctx?$ctx->s_id:null,
								'options'=> Bugs::options_statuses(),
								'name'=> 'status'
							))
					?>
				</div>
				<div>
					<label>Priority:</label>
					<?=
						Bugs::template("subs/dropdown")
							->run(array(
								'value'=> $ctx?$ctx->pr_id:null,
								'options'=> Bugs::options_priorities(),
								'name'=> 'priority'
							))
					?>
				</div>
				<div>
					<label>User:</label>
					<a href="<?=Router::url(Router::$base.'/~'.($ctx?$ctx->user->name:null))?>">
						<?=$ctx?$ctx->user->name:null;?>
					</a>
				</div>
				<?php
					if($ctx->project){
				?>
					<div>
						<label>Project:</label>
						<a href="<?=Router::url(Router::$base.'/project/'.($ctx?$ctx->project->name:null))?>">
							<?=($ctx?$ctx->project->name:null);?>
						</a>
					</div>
				<?php
					}
				?>
				<div>
					<label>Date Registered:</label>
					<time datetime="<?=date('c',($ctx?$ctx->date_created:null));?>"><?=date('Y-m-d',($ctx?$ctx->date_created:null));?></time>
				</div>
				<div>
					<label>Date Modified:</label>
					<time datetime="<?=date('c',($ctx?$ctx->date_modified:null));?>"><?=date('Y-m-d',($ctx?$ctx->date_modified:null));?></time>
				</div>
				<input type="hidden" name="id" value="<?=($ctx?$ctx->id:null)?>"/>
			<?php
				}
				if($update){
			?>
				<input type="submit" value="<?=$ctx?'Update':'Create'?>"/>
			<?php
				}
			?>
		</form>
	</body>
</html>