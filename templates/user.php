<?php
	// Expecting the context to be a user
	global $context;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>User - <?=$context->name;?></title>
	</head>
	<body>
		<form>
			<div>
				<label for="name">Name:</label>
				<input value="<?=$context->name;?>" name="name"/>
			</div>
			<div>
				<label for="email">Email:</label>
				<input type="email" value="<?=$context->email;?>" name="email"/>
			</div>
			<div>
				<label>Date Registered:</label>
				<time datetime="<?=date('c',$context->date_registered);?>"><?=date('Y-m-d',$context->date_registered);?></time>
			</div>
			<div>
				<label>Date Modified:</label>
				<time datetime="<?=date('c',$context->date_modified);?>"><?=date('Y-m-d',$context->date_modified);?></time>
			</div>
		</form>
	</body>
</html>