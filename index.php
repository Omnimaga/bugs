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
				die(
					json_encode(
						array(
							'state'=>stateObj($type,$id)
						)
					)
				);
			break;
			case 'api':
				require_once('api.php');
			break;
			case 'settings':
				$settings = array();
				$keys = array('expire');
				foreach($keys as $key){
					$settings[$key] = get($key);
				}
				die(json_encode(array(
					'settings'=>$settings,
					'version'=>file_get_contents(PATH_DATA.'version')
				)));
			break;
			case 'captcha':
				generate_captcha();
			break;
			case 'error':
				die(
					json_encode(
						array(
							'error'=>isset($_GET['error'])?$_GET['error']:'Error'
						)
					)
				);
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
		<link href="css/ui.notify.css" rel="stylesheet" type="text/css"/>
		<!-- <link href="css/style.css" rel="stylesheet" type="text/css"/> -->
		<link href="css/action_menu.css" rel="stylesheet"></link>
		<link href="css/buttons.css" rel="stylesheet"></link>
		<link href="css/confirm.css" rel="stylesheet"></link>
		<link href="css/edit_mode.css" rel="stylesheet"></link>
		<link href="css/headers.css" rel="stylesheet"></link>
		<link href="css/input_areas.css" rel="stylesheet"></link>
		<link href="css/status.css" rel="stylesheet"></link>
		<link href="css/switches.css" rel="stylesheet"></link>
		<link href="css/drawer.css" rel="stylesheet"></link>
		<link href="css/lists.css" rel="stylesheet"></link>
		<link href="css/progress_activity.css" rel="stylesheet"></link>
		<link href="css/scrolling.css" rel="stylesheet"></link>
		<link href="css/seekbars.css" rel="stylesheet"></link>
		<link href="css/tabs.css" rel="stylesheet"></link>
		<link href="css/toolbars.css" rel="stylesheet"></link>
		<link href="css/icons/styles/action_icons.css" rel="stylesheet"></link>
		<link href="css/icons/styles/media_icons.css" rel="stylesheet"></link>
		<link href="css/icons/styles/comms_icons.css" rel="stylesheet"></link>
		<link href="css/icons/styles/settings_icons.css" rel="stylesheet"></link>
		<link href="css/transitions.css" rel="stylesheet"></link>
		<link href="css/util.css" rel="stylesheet"></link>
		<link href="css/cross_browser.css" rel="stylesheet"></link>
		<link href="css/app.css" rel="stylesheet"></link>
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
				if(typeof window.screen.lockOrientation != 'undefined'){
					window.screen.lockOrientation('portrait');
				}
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
		<script src="js/jquery.notify.js"></script>
		<script src="js/shortcut.js"></script>
		<script src="js/index.js"></script>
	</head>
	<body lang="en" role="application">
		<section id="index" data-position="current">
			<section id="sidebar" data-type="sidebar"></section>
			<section id="drawer" role="region">
				<header class="fixed" id="topbar">
				</header>
				<article id="content" class="scrollable header"></article>
			</section>
		</section>
		<!-- <section id="action-menu" data-position="back" class="fade-in">
			<form role="dialog" data-type="action">
				<header>
					Title
				</header>
				<menu></menu>
			</form>
		</section> -->
		<div id="loading"></div>
		<div id="dialog"></div>
		<div id="comment" style="display:none;">
			<form>
				<input type="hidden" name="comment_type"/>
				<input type="hidden" name="comment_id"/>
				<textarea name="message"></textarea>
			</form>
		</div>
		<div id="notification-container" class="top" style="display:none;">
			<div id="basic-template">
				<a class="ui-notify-cross ui-notify-close" href="#">
					x
				</a>
				<h1>
					#{title}
				</h1>
				<p>
					#{text}
				</p>
			</div>
		</div>
		<script src="js/status.js" defer="" type="text/javascript"></script>
		<script src="js/seekbars.js" defer="" type="text/javascript"></script>
		<script src="js/app.js" defer="" type="text/javascript"></script>
	</body>
</html>