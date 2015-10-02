<?php
	// Expecting the context to be a project or nothing at all
	global $context;
	$ctx = $context;
	($ctx?$ctx->permission('read'):Bugs::permission('project.read')&&Bugs::permission('project.create')) or trigger_error('You are not allowed to view this project');
	$update = $ctx?$ctx->permission('update'):Bugs::permission('project.create');
	$delete = $ctx?$ctx->permission('delete'):Bugs::permission('project.delete');
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Project <?=($ctx?$ctx->name:null);?></title>
		<script src="<?=Router::url(Router::$base)?>/js/juju/core.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/juju/page.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/juju/dom.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/juju/keyboard.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/juju/mouse.js"></script>
		<script src="<?=Router::url(Router::$base)?>/js/project.js"></script>
		<script>
			BASE_URL = '<?=Router::url(Router::$base)?>';
		</script>
		<link rel="stylesheet" href="<?=Router::url(Router::$base)?>/css/main.css"></link>
	</head>
	<body>
		<a href="<?=Router::url(Router::$base)?>">Home</a>
		<form id="form-project" method="post">
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
					<label>User:</label>
					<a href="<?=Router::url(Router::$base.'/~'.($ctx?$ctx->user->name:null))?>">
						<?=($ctx?$ctx->user->name:null)?>
					</a>
				</div>
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
		<?php
			if($ctx){
				echo Bugs::template('subs/issues')
						->run($ctx);
			}
		?>
	</body>
</html>