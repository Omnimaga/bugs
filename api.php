<?php
	// MYSQL default bugs:bugs
	function retj($json){
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
					retj($ret);
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