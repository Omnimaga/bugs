<?php
	// Expecting the context to be a issue or nothing at all
	global $context;
	($context?$context->permission('read'):Bugs::permission('issue.read')) or trigger_error('You are not allowed to view this issue');
	$update = $context?$context->permission('update'):Bugs::permission('issue.create');
	$delete = $context?$context->permission('delete'):Bugs::permission('issue.delete');
	function getval($name){
		global $context;
		return $context?$context->{$name}:null;
	}
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Issue <?=getval('name');?></title>
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
				<input value="<?=getval('name');?>" <?=$update?'name="name"':'disabled="disabled"';?>/>
			</div>
			<div>
				<label for="description">Description:</label>
				<input type="description" value="<?=getval('description');?>" <?=$update?'name="description"':'disabled="disabled"';?>/>
			</div>
			<?php
				if($context){
			?>
				<div>
					<label>Status:</label>
					<?=getval('status')?>
				</div>
				<div>
					<label>Priority:</label>
					<?=getval('priority')?>
				</div>
				<div>
					<label>User:</label>
					<a href="<?=Router::url(Router::$base.'/~'.getval('user')->name)?>">
						<?=getval('user')->name;?>
					</a>
				</div>
				<?php
					if($context->project){
				?>
					<div>
						<label>Project:</label>
						<a href="<?=Router::url(Router::$base.'/project/'.getval('project')->name)?>">
							<?=getval('project')->name;?>
						</a>
					</div>
				<?php
					}
				?>
				<div>
					<label>Date Registered:</label>
					<time datetime="<?=date('c',getval('date_created'));?>"><?=date('Y-m-d',getval('date_created'));?></time>
				</div>
				<div>
					<label>Date Modified:</label>
					<time datetime="<?=date('c',getval('date_modified'));?>"><?=date('Y-m-d',getval('date_modified'));?></time>
				</div>
				<input type="hidden" name="id" value="<?=getval('id')?>"/>
			<?php
				}
				if($update){
			?>
				<input type="submit" value="<?=$context?'Update':'Create'?>"/>
			<?php
				}
			?>
		</form>
	</body>
</html>