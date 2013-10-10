<?php
	if(!file_exists('config.json')){
		header('Location: install');
		die();
	}
	session_start();
	require_once('php/include.php');
	if(isset($_GET['get'])){
		$get = $_GET['get'];
		unset($_GET['get']);
		if(!isset($_GET['type']) || !isset($_GET['id'])){
			$type = 'template';
			$id = 'index';
		}else{
			$type = $_GET['type'];
			$id = $_GET['id'];
		}
		if($get == 'state'){
			$json = Array();
			$json['state'] = Array();
			$json['state']['data'] = $_GET;
			switch($type){
				case 'user':$url='~'.$id;break;
				case 'group':$url='+'.$id;break;
				case 'issue':$url='!'.$id;break;
				case 'template':$url='page-'.$id;break;
				default:$url=$type.'-'.$id;
			}
			$json['state']['url'] = $url;
			die(json_encode($json));
			
		}elseif($get == 'api'){
			require_once('api.php');
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
	</body>
</html>