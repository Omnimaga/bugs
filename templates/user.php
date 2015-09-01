<?php
	// Expecting the context to be a user
	global $context;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>User - <?=$context->name;?></title>
		<script src="js/juju/core.js"></script>
		<script src="js/juju/dom.js"></script>
		<script src="js/juju/cookie.js"></script>
		<script src="js/user.js"></script>
		<link rel="stylesheet" href="css/main.css"></link>
	</head>
	<body>
		<a href="<?=Router::url(Router::$base)?>">Home</a>
		<form id="form-user" method="post">
			<div>
				<label for="name">Name:</label>
				<input value="<?=$context->name;?>" <?=(Bugs::$user&&$context->id==Bugs::$user->id)?'name="name"':'disabled="disabled"';?>/>
			</div>
			<div>
				<label for="email">Email:</label>
				<input type="email" value="<?=$context->email;?>" <?=(Bugs::$user&&$context->id==Bugs::$user->id)?'name="email"':'disabled="disabled"';?>/>
			</div>
			<div>
				<label>Date Registered:</label>
				<time datetime="<?=date('c',$context->date_registered);?>"><?=date('Y-m-d',$context->date_registered);?></time>
			</div>
			<div>
				<label>Date Modified:</label>
				<time datetime="<?=date('c',$context->date_modified);?>"><?=date('Y-m-d',$context->date_modified);?></time>
			</div>
			<?php
				if(Bugs::$user&&$context->id==Bugs::$user->id){
			?>
				<div>
					<label for="password">Password:</label>
					<input type="password" name="password"/>
				</div>
				<input type="hidden" name="id" value="<?=$context->id?>"/>
				<input type="submit" value="Update"/>
			<?php
				}
			?>
		</form>
		<div>
			<h3>Projects</h3>
			<ul>
				<?php
					foreach($context->projects as $project){
						echo "<li>({$project->status}) <a href=\"".Router::url(Router::$base."/project/{$project->name}")."\">{$project->name}</a></li>";
					}
				?>
			</ul>
		</div>
		<div>
			<h3>Issues</h3>
			<ul>
				<?php
					foreach($context->issues as $issue){
						echo "<li>({$issue->status} - {$issue->priority}) <a href=\"".Router::url(Router::$base."/!{$issue->id}")."\">{$issue->name}</a></li>";
					}
				?>
			</ul>
		</div>
	</body>
</html>