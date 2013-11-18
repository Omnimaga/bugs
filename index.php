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
				$keys = Array('expire');
				foreach($keys as $key){
					$settings[$key] = get($key);
				}
				die(json_encode(Array(
					'settings'=>$settings,
					'version'=>file_get_contents(PATH_DATA.'version')
				)));
			break;
			case 'captcha':
				generate_captcha();
			break;
		}
	}
	if(!isset($_GET['type'])||!isset($_GET['id'])){
		header('Location: page-index');
		die();
	}
?>
<!doctype html>
<html manifest="bugs.appcache">
	<head>
		<meta charset=utf-8>
		<meta name="viewport" content="width=device-width, user-scalable=false, initial-scale=1, maximum-scale=1.0, user-scalable=0, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<link rel="apple-touch-icon" sizes="128-128" href="img/favicon-128.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="img/favicon-120.png" />
		<link rel="apple-touch-icon" sizes="90x90" href="img/favicon-90.png" />
		<link rel="apple-touch-icon" sizes="60x60" href="img/favicon-60.png" />
		<link rel="apple-touch-startup-image" href="img/startup.png">
		<link rel="shortcut icon" href="img/favicon.ico" />
		<link rel="icon" type="image/png" href="img/favicon-60.png" />
		<link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
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
				window.screen.lockOrientation('portrait');
			})(window,Modernizr);
		</script>
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/handlebars.js"></script>
		<script src="js/jquery.history.js"></script>
		<script src="js/jquery.storage.js"></script>
		<script src="js/jquery.cookie.js"></script>
		<script src="js/jquery.nicescroll.js"></script>
		<script src="js/jquery.timeago.js"></script>
		<script src="js/shortcut.js"></script>
		<script src="js/index.js"></script>
	</head>
	<body lang="en">
		<div id="topbar" class="overflow-hide"></div>
		<div id="content" class="container"></div>
		<div id="loading"></div>
		<div id="dialog"></div>
		<div id="comment">
			<form>
				<input type="hidden" name="type"/>
				<input type="hidden" name="id"/>
				<textarea name="message"></textarea>
			</form>
		</div>
	</body>
</html>