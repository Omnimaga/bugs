<?php
	global $context;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Bugs</title>
		<script src="<?=Router::$base?>/js/juju/core.js"></script>
		<script src="<?=Router::$base?>/js/juju/page.js"></script>
		<script src="<?=Router::$base?>/js/juju/dom.js"></script>
		<script src="<?=Router::$base?>/js/juju/keyboard.js"></script>
		<script src="<?=Router::$base?>/js/juju/mouse.js"></script>
		<link rel="stylesheet" href="<?=Router::$base?>/css/main.css"></link>
	</head>
	<body>
		<?php
			if(Bugs::$user){
		?>
			Hello World!
			<a href="<?=Router::url(Router::$base.'/sessions')?>">Sessions</a>
			<a href="<?=Router::url(Router::$base.'/~'.Bugs::$user->name)?>">Profile</a>
			<a href="<?=Router::url(Router::$base.'/logout')?>">Logout</a>
			<div>
				<h3>Projects</h3>
				<a href="<?=Router::url(Router::$base.'/create/project')?>">New</a>
				<ul>
					<?php
						foreach(Bugs::$user->projects as $project){
							echo "<li>({$project->status}) <a href=\"".Router::url(Router::$base."/project/{$project->name}")."\">{$project->name}</a></li>";
						}
					?>
				</ul>
			</div>
			<div>
				<h3>Issues</h3>
				<a href="<?=Router::url(Router::$base.'/create/issue')?>">New</a>
				<ul>
					<?php
						foreach(Bugs::$user->issues as $issue){
							echo "<li>({$issue->status} - {$issue->priority}) <a href=\"".Router::url(Router::$base."/!{$issue->id}")."\">{$issue->name}</a></li>";
						}
					?>
				</ul>
			</div>
		<?php
			}else{
		?>
			<a href="<?=Router::url(Router::$base.'/login')?>">Login</a>
			<a href="<?=Router::url(Router::$base.'/register')?>">Register</a>
		<?php
			}
		?>
	</body>
</html>