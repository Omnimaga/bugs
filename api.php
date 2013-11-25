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
					back(true);
					if(!isset($_GET['template'])){
						$ret['template'] = file_get_contents(PATH_DATA.'pages/user.template');
					}
					if($user = userObj($id)){
						$context = Array(
							'name'=>$user['name'],
							'email'=>$user['email']
						);
						if($LOGGEDIN){
							$context['key'] = true;
							$context['user'] = userObj($_SESSION['username']);
						};
						$ret['context'] = $context;
					}else{
						$ret['state'] = Array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'User - '.$context['name']);
				break;
				case 'group':
					back(true);
					// TODO - handle group requests
					if(false){
						// TODO
					}else{
						$ret['state'] = Array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'Project - '.$context['title']);
				break;
				case 'issue':
					back(true);
					// TODO - handle issue requests
					if(false){
						// TODO
					}else{
						$ret['state'] = Array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'Project - '.$context['title']);
				break;
				case 'scrum':
					back(true);
					// TODO - handle scrum requests
					if(false){
						// TODO
					}else{
						$ret['state'] = Array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'Project - '.$context['title']);
				break;
				case 'project':
					back(true);
					if(!isset($_GET['template'])){
						$ret['template'] = file_get_contents(PATH_DATA.'pages/project.template');
					}
					if($context = projectObj($id)){
						$context['user'] = userObj($context['user']);
						if($LOGGEDIN){
							$context['key'] = true;
							$context['user'] = userObj($_SESSION['username']);
						};
						$ret['context'] = $context;
					}else{
						$ret['state'] = Array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'Project - '.$context['title']);
				break;
				case 'message':
					// TODO - handle message requests
					if(false){
						// TODO
					}else{
						$ret['state'] = Array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'Project - '.$context['title']);
				break;
				case 'admin':
					back(true);
					// TODO - handle admin requests
					if(false){
						// TODO
					}else{
						$ret['state'] = Array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'Project - '.$context['title']);
				break;
				case 'page':
					$title = $id;
					if(file_exists(PATH_DATA.'pages/'.$id.'.template')){
						if(!isset($_GET['template'])||$_GET['template']=='true'){
							$ret['template'] = file_get_contents(PATH_DATA.'pages/'.$id.'.template');
						}
						$context = Array();
						if($LOGGEDIN){
							$context['key'] = true;
							$context['user'] = userObj($_SESSION['username']);
						};
						if(file_exists(PATH_DATA.'pages/'.$id.'.options')){
							$options = objectToArray(json_decode(file_get_contents(PATH_DATA.'pages/'.$id.'.options'),true));
							if(isset($options['secure'])&&$options['secure']&&!$LOGGEDIN){
								back(true);
							}
							if(isset($options['title'])){
								$title = $options['title'];
							}
							if(isset($options['context'])){
								foreach($options['context'] as $key){
									switch($key){
										case 'users':
											if($res = query("SELECT name FROM `users`;")){
												$context['users'] = fetch_all($res,MYSQLI_ASSOC);
											}
										break;
										case 'projects':
											if($res = query("SELECT p.title,p.id,p.description,u.name as user FROM `projects` p JOIN `users` u ON u.id = p.u_id")){
												$context['projects'] = fetch_all($res,MYSQLI_ASSOC);
												foreach($context['projects'] as $key => $project){
													$context['projects'][$key]['user'] = userObj($project['user']);
												}
											}
										break;
										case 'messages':
											if($LOGGEDIN){
												$context['messages'] = messages($context['user']['id'],'user');
											}else{
												$context['messages'] = Array();
											}
										break;
									}
								}
							}
						}
						$ret['context'] = $context;
					}else{
						$ret['error'] = 'That page does not exist';
					}
					retj($ret,$title);
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
								if(is_valid('username')&&is_valid('password')&&is_valid('password1')&&is_valid('email')&&is_valid('captcha')){
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
							case 'project':
								back(true);
								$ret['state'] = Array(
									'data'=>Array(
										'type'=>'page',
										'id'=>$id,
									)
								);
								if(isset($_GET['pid'])){
									$ret['error'] = 'Invalid Action';
								}elseif(is_valid('title')&&is_valid('description')){
									if(!newProject($_GET['title'],$_GET['description'])){
										$ret['error'] = 'Unable to create project.';
									}
								}else{
									$ret['error'] = 'Fill in all the details.';
								}
								retj($ret,$id);
							break;
							case 'comment':
								if(isset($_GET['comment_type'])&&isset($_GET['comment_id'])&&isset($_GET['message'])){
									$cid = $_GET['comment_id'];
									$ret = Array(
										'state'=>stateObj($_GET['comment_type'],$cid)
									);
									switch($_GET['comment_type']){
										case 'project':
											if(!function_exists('project_comment')){
												$ret['error'] = "fn doesn't exist!";
											}
											if(!project_comment($cid,$_GET['message'])){
												$ret = Array(
													'error'=>'Could not comment on project'
												);
											}
										break;
										default:
											$ret['error'] = 'Comment type not implemented';
									}
								}else{
									$ret['error'] = 'Missing comment paremeters';
									$ret['state'] = Array(
										'title'=>'error'
									);
								}
								retj($ret,$ret['state']['title']);
							break;
							default:
								retj(Array(
									'error'=>'Invalid action.'
								));
						}
				break;
				default:
					retj(Array(
						'error'=>'Invalid type.'
					));
			}
		}else{
			retj(Array(
				'error'=>'ID missing.'
			));
		}
	}else{
		retj(Array(
		'error'=>'Type missing.'
	));
	}
?>