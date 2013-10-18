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
	if(!isset($_GET['type'])||!isset($_GET['id'])){
		header('Location: page-index');
		die();
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
				$keys = Array('expire');
				foreach($keys as $key){
					$settings[$key] = get($key);
				}
				die(json_encode($settings));
			break;
			case 'captcha':
				generate_captcha();
			break;
		}
	}
?>
<!doctype html>
<html manifest="bugs.appcache">
	<head>
		<meta charset=utf-8>
		<meta name="viewport" content="width=device-width, user-scalable=false, initial-scale=1, maximum-scale=1.0, user-scalable=0, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="shortcut icon" href="img/favicon.ico" />
		<link rel="icon" type="image/png" href="img/favicon-60.png" />
		<link href="http://code.jquery.com/ui/1.10.3/themes/black-tie/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<link href="css/style.css" rel="stylesheet" type="text/css"/>
		<title>Bugs</title>
		<script src="js/modernizr.js"></script>
		<script>
			(function(window,Modernizr){
				Modernizr.mediaquery = Modernizr.mq('only all');
				var checks = [
						'csscalc',
						'cookies',
						'localstorage',
						'history',
						'rgba',
						'applicationcache',
						'mediaquery',
						'fontface'
					],i,
					msg = '';
				for(i in checks){
					if(!Modernizr[checks[i]]){
						msg += (msg==''?'':', ')+checks[i];
					}

				}
				if(msg != ''){
					alert("Your browser is unable to support all the features this site needs.\nChecks failed: "+msg);
				}
				window.applicationCache.addEventListener('updateready',function(){
					location.reload();
				});
			})(window,Modernizr);
		</script>
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/handlebars.js"></script>
		<script src="js/jquery.history.js"></script>
		<script src="js/jquery.storage.js"></script>
		<script src="js/jquery.cookie.js"></script>
		<script src="js/jquery.nicescroll.js"></script>
		<script src="js/shortcut.js"></script>
		<script src="js/index.js"></script>
	</head>
	<body lang="en">
		<div id="topbar"></div>
		<div id="content" class="container"></div>
		<div id="loading"></div>
	</body>
</html>