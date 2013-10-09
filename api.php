<?php
	@session_start();
	require_once('php/include.php');
	// MYSQL default bugs:bugs
	function retj($json,$title){
		$type=$_GET['type'];
		$id=$_GET['id'];
		$json['state'] = Array();
		$json['state']['data'] = $_GET;
		$json['state']['title'] = $title;
		switch($type){
			case 'user':$url='~'.$id;break;
			case 'group':$url='+'.$id;break;
			case 'issue':$url='!'.$id;break;
			case 'template':$url='page-'.$id;break;
			default:$url=$type.'-'.$id;
		}
		$json['state']['url'] = $url;
		die(json_encode($json));
	}
	// TODO - Add API handling.
	$method = $_SERVER['REQUEST_METHOD'];
	$ret = Array();
	if(isset($_GET['type'])){
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			switch($_GET['type']){
				case 'user':
					// TODO - handle user requests
				break;
				case 'group':
					// TODO - handle group requests
				break;
				case 'issue':
					// TODO - handle issue requests
				break;
				case 'scrum':
					// TODO - handle scrum requests
				break;
				case 'admin':
					// TODO - handle admin requests
				break;
				case 'template':
					$ret['template'] = file_get_contents('data/'.$id.'.template.html');
					$ret['context'] = json_decode(file_get_contents('data/'.$id.'.context.json'));
					retj($ret,$id);
				break;
				case 'login':
						// TODO - handle logins
				break;
				default:
					die("invalid type");
			}
		}else{
			die("id missing");
		}
	}else{
		die("type missing");
	}
?>