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
					$ret['template'] = file_get_contents(PATH_DATA.'pages/'.$id.'.template');
					if(file_exists(PATH_DATA.'context/'.$id.'.json')){
						$context = json_decode(file_get_contents(PATH_DATA.'context/'.$id.'.json'));
					}else{
						$context = Array();
					}
					$ret['context'] = $context;
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
								$ret['state'] = Array('data'=>Array('type'=>'template','id'=>'login'));
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