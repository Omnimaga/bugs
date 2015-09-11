<?php
	global $context;
	$actions = array();
	$rows = Bugs::$sql->query("
		SELECT id,name
		FROM actions
	")->assoc_results;
	foreach($rows as $action){
		$actions[intval($action['id'])] = $action['name'];
	}
	$activities = Bugs::$sql->query("
		SELECT	date,
				a_id,
				data
		FROM activities
		ORDER BY date DESC
		LIMIT ?,?
	",'ii',$context->page,$context->amount)->assoc_results;
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Bugs - Timeline</title>
		<script src="<?=Router::$base?>/js/juju/core.js"></script>
		<script src="<?=Router::$base?>/js/juju/page.js"></script>
		<script src="<?=Router::$base?>/js/juju/dom.js"></script>
		<script src="<?=Router::$base?>/js/juju/keyboard.js"></script>
		<script src="<?=Router::$base?>/js/juju/mouse.js"></script>
		<link rel="stylesheet" href="<?=Router::$base?>/css/main.css"></link>
		<link rel="stylesheet" href="<?=Router::$base?>/css/timeline.css"></link>
	</head>
	<body>
		<a href="<?=Router::url(Router::$base)?>">Home</a>
		<div class="timeline">
			<?php
				foreach($activities as $activity){
					$activity = new Arguments(array(
						'date'=> strtotime($activity['date']),
						'action'=> $actions[intval($activity['a_id'])],
						'data'=> new Arguments(json_decode($activity['data'],true)),
						'template'=> 'default'
					));
					if(file_exists("templates/activities/{$activity->action}.php")){
						$activity->template = $activity->action;
					}
					echo Bugs::template("activities/{$activity->template}")
						->run($activity);
				}
			?>
		</div>
	</body>
</html>