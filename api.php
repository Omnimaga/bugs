<?php
	require_once('php/include.php');
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
				case 'action':
						switch($id){
							case 'login':
								if(isset($_GET['username'])&&isset($_GET['password'])){
									$key = login($_GET['username'],$_GET['password']);
									if($key){
										$ret['key'] = $key;
									}else{
										$ret['error'] = "Login failed. Username or Password didn't match.";
									}
								}else{
									$ret['error'] = "Please provide a valid username and password.";
								}
								retj($ret,$id);
							break;
							case 'register':
								if(isset($_GET['username'])&&isset($_GET['password'])&&isset($_GET['email'])){
									if(addUser($_GET['username'],$_GET['password'],$_GET['email'])){
										$ret['key'] = securityKey($_GET['username'],salt());
										setKey($ret['key']);
									}else{
										$ret['error'] = "Could not add user. ".$mysqli->error;
									}
								}else{
									$ret['error'] = "That username already exists!";
								}
								retj($ret,$id);
							break;
							default:
								die('invalid action');
						}
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