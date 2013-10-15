<?php
	if(!file_exists('config.default.json')){
		header('Location: install');
		die();
	}elseif(file_exists('install')){
		$files = scandir('install');
		if(empty($files)){
			@rmdir('install');
		}
	}
	require_once('php/include.php');
	$salt = salt();
	if(isset($_GET['get'])){
		$get = $_GET['get'];
		unset($_GET['get']);
		if(!isset($_GET['type']) || !isset($_GET['id'])){
			$type = 'page';
			$id = 'index';
		}else{
			$type = $_GET['type'];
			$id = $_GET['id'];
		}
		switch($get){
			case 'state':
				$json = Array();
				$json['state'] = Array();
				$json['state']['data'] = $_GET;
				switch($type){
					case 'user':$url='~'.$id;break;
					case 'group':$url='+'.$id;break;
					case 'issue':$url='!'.$id;break;
					case 'page':$url='page-'.$id;break;
					default:$url=$type.'-'.$id;
				}
				$json['state']['url'] = $url;
				$json['state']['title'] = ucwords($type.' - '.$id);
				die(json_encode($json));
			break;
			case 'api':
				require_once('api.php');
			break;
			case 'settings':
				$settings = Array();
				$keys = Array('timeout');
				foreach($keys as $key){
					$settings[$key] = get($key);
				}
				die(json_encode($settings));
			break;
		}
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset=utf-8> 
		<title>Bugs</title>
		<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
		<script src="js/handlebars.js"></script>
		<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="js/jquery.history.js"></script>
		<script src="js/jquery.cookie.js"></script>
		<script src="js/index.js"></script>
		<link href="http://code.jquery.com/ui/1.10.3/themes/black-tie/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<link href="css/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css"/>
		<link href="css/style.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
		<div id="topbar"></div>
		<div id="content"></div>
		<div id="loading"></div>
	</body>
</html>