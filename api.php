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
					if(!isset($_GET['template'])){
						$ret['template'] = file_get_contents(PATH_DATA.'pages/user.template');
					}
					$user = userObj($id);
					$context = Array(
						'name'=>$user['name'],
						'email'=>$user['email']
					);
					if($LOGGEDIN){
						$context['key'] = true;
						$context['user'] = userObj($_SESSION['username']);
					};
					$ret['context'] = $context;
					retj($ret,$id);
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
				case 'page':
					if(file_exists(PATH_DATA.'pages/'.$id.'.template')){
						if(!isset($_GET['template'])){
							$ret['template'] = file_get_contents(PATH_DATA.'pages/'.$id.'.template');
						}
						$context = Array();
						if($LOGGEDIN){
							$context['key'] = true;
							$context['user'] = userObj($_SESSION['username']);
						};
						if(file_exists(PATH_DATA.'pages/'.$id.'.options')){
							$options = objectToArray(json_decode(file_get_contents(PATH_DATA.'pages/'.$id.'.options'),true));
							foreach($options as $key){
								switch($key){
									case 'users':
										$res = query("SELECT name FROM `users`;");
										$users = fetch_all($res,MYSQLI_ASSOC);
										$context['users'] = $users;
									break;
								}
							}
						}
						$ret['context'] = $context;
					}else{
						$ret['error'] = 'That page does not exist';
					}
					retj($ret,$id);
				break;
				case 'action':
						switch($id){
							case 'login':
								$ret['state'] = Array(
									'data'=>Array(
										'type'=>'page',
										'id'=>'login',
									)
								);
								if(isset($_GET['username'])&&isset($_GET['password'])){
									$key = login($_GET['username'],$_GET['password']);
									if($key){
										$_SESSION['username'] = $_GET['username'];
									}else{
										$ret['error'] = "Login failed. Username or Password didn't match.";
									}
								}else{
									$ret['error'] = "Please provide a valid username and password.";
								}
								retj($ret,$id);
							break;
							case 'register':
								$ret['state'] = Array(
									'data'=>Array(
										'type'=>'page',
										'id'=>'register'
									)
								);
								if(isvalid('username')&&isvalid('password')&&isvalid('password1')&&isvalid('email')&&isvalid('captcha')){
									if($_GET['password']==$_GET['password1']){
										if(compare_captcha($_GET['captcha'])){
											if(addUser($_GET['username'],$_GET['password'],$_GET['email'])){
												$key = login($_GET['username'],$_GET['password']);
												$_SESSION['username'] = $_GET['username'];
												sendMail('welcome','Welcome!',$_GET['email'],get('email'),Array($_GET['username'],$_GET['password'],get('email')));
											}else{
												$ret['error'] = "Could not add user. ".$mysqli->error;
											}
										}else{
											$ret['error'] = "Captcha did not match.";
										}
									}else{
										$ret['error'] = "Passwords didn't match.";
									}
								}else{
									$ret['error'] = "Please fill in all the fields.";
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