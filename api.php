<?php
	require_once('php/include.php');
	// TODO - Add API handling.
	$method = $_SERVER['REQUEST_METHOD'];
	$ret = array();
	if(isset($_GET['type'])){
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			switch($_GET['type']){
				case 'user':
					back(true);
					$ret['template'] = array(
						'type'=>'pages',
						'name'=>'user'
					);
					$ret['topbar'] = 'back';
					if($user = userObj($id)){
						$context = array(
							'name'=>$user['name'],
							'email'=>$user['email']
						);
						if($LOGGEDIN){
							$context['key'] = true;
							$context['user'] = userObj($_SESSION['username']);
						};
						$ret['context'] = $context;
					}else{
						$ret['state'] = array(
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
						$ret['state'] = array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret);
				break;
				case 'issue':
					back(true);
					$ret['template'] = array(
						'type'=>'pages',
						'name'=>'issue'
					);
					$ret['topbar'] = 'back';
					if($context = issueObj($id)){
						$context['user'] = userObj($context['user']);
						if($LOGGEDIN){
							$context['key'] = true;
							$context['user'] = userObj($_SESSION['username']);
						};
						$ret['context'] = $context;
					}else{
						$ret['state'] = array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'Issue #'.$id. ' - '.$context['title']);
				break;
				case 'scrum':
					back(true);
					$ret['template'] = array(
						'type'=>'pages',
						'name'=>'scrum'
					);
					$ret['topbar'] = 'back';
					if($context = scrumObj($id)){
						$context['user'] = userObj($context['user']);
						if($LOGGEDIN){
							$context['key'] = true;
							$context['user'] = userObj($_SESSION['username']);
						};
						$ret['context'] = $context;
					}else{
						$ret['state'] = array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'Scrum - '.$context['title']);
				break;
				case 'project':
					back(true);
					$ret['template'] = array(
						'type'=>'pages',
						'name'=>'project'
					);
					$ret['topbar'] = 'back';
					if($context = projectObj($id)){
						$context['user'] = userObj($context['user']);
						if($LOGGEDIN){
							$context['key'] = true;
							$context['user'] = userObj($_SESSION['username']);
						};
						$ret['context'] = $context;
					}else{
						$ret['state'] = array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,'Project - '.$context['title']);
				break;
				case 'message':
					// TODO - handle message requests
					$context = array();
					if(false){
						// TODO
					}else{
						$ret['state'] = array(
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
						$ret['state'] = array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret);
				break;
				case 'page':
					$title = $id;
					if(file_exists(PATH_DATA.'pages/'.$id.'.template')){
						$context = array();
						$ret['template'] = array(
							'type'=>'pages',
							'name'=>$id
						);
						if($LOGGEDIN){
							$context['key'] = true;
							$context['user'] = userObj($_SESSION['username']);
						};
						if(file_exists(PATH_DATA.'pages/'.$id.'.options')){
							$options = objectToarray(json_decode(file_get_contents(PATH_DATA.'pages/'.$id.'.options'),true));
							if(isset($options['secure'])&&$options['secure']&&!$LOGGEDIN){
								back(true);
							}
							if(isset($options['title'])){
								$title = $options['title'];
							}
							if(isset($options['topbar'])){
								$ret['topbar'] = $options['topbar'];
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
												$context['messages'] = array();
											}
										break;
										case 'issues':
											if($res = query("SELECT i.id,i.title,i.description,u.name as user,s.name as status,p.name as priority,p.color FROM `issues` i JOIN `users` u ON u.id = i.u_id LEFT JOIN `statuses` s ON s.id = i.st_id LEFT JOIN `priorities` p ON p.id = i.pr_id")){
												$context['issues'] = fetch_all($res,MYSQLI_ASSOC);
												foreach($context['issues'] as $key => $issue){
													$context['issues'][$key]['user'] = userObj($issue['user']);
												}
											}
										break;
										case 'latest':
											if($res = query("SELECT i.id,i.title,i.description,u.name as user,s.name as status,p.name as priority,p.color FROM `issues` i JOIN `users` u ON u.id = i.u_id LEFT JOIN `statuses` s ON s.id = i.st_id LEFT JOIN `priorities` p ON p.id = i.pr_id LIMIT 10")){
												$context['issues'] = fetch_all($res,MYSQLI_ASSOC);
												foreach($context['issues'] as $key => $issue){
													$context['issues'][$key]['user'] = userObj($issue['user']);
												}
											}
										break;
									}
								}
							}
							if(isset($options['actions'])){
								foreach($options['actions'] as $key){
									switch($key){
										case 'pm_mark_read':
											query("UPDATE `users` SET last_pm_check=CURRENT_TIMESTAMP WHERE id='%d'; ",array(userId($_SESSION['username'])));
										break;
									}
								}
							}
						}
						$ret['context'] = $context;
					}else{
						$ret['error'] = 'That page does not exist';
						$ret['state'] = array(
							'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
						);
					}
					retj($ret,$title);
				break;
				case 'manifest':
					case 'pages':
						if(isset($_GET['id'])){
							if($_GET['id'] != 'emails'){
								$manifest = array();
								$files = array_diff(scandir(PATH_DATA.'/'.$_GET['id']),array('..', '.','.htaccess','version'));
								foreach($files as $k => $file){
									if(pathinfo(PATH_DATA.'/'.$_GET['id'].'/'.$file,PATHINFO_EXTENSION) == 'template'){
										array_push($manifest,array(
											'name'=>basename($file,'.template'),
											'hash'=>md5_file(PATH_DATA.'/'.$_GET['id'].'/'.$file)
										));
									}
								}
								retj(array(
									'manifest'=>$manifest,
									'type'=>$_GET['id']
								));
							}else{
								retj(array(
									'error'=>'Cannot return that manifest'
								));
							}
						}else{
							retj(array(
								'error'=>'Manifest ID not defined'
							));
						}
					break;
				break;
				case 'template':
					if(isset($_GET['name'])){
						if($_GET['id'] != 'emails'){
							retj(array(
								'template'=>file_get_contents(PATH_DATA.'/'.$_GET['id'].'/'.$_GET['name'].'.template'),
								'name'=>$_GET['name'],
								'type'=>$_GET['id'],
								'hash'=>md5_file(PATH_DATA.'/'.$_GET['id'].'/'.$_GET['name'].'.template')
							));
						}else{
							retj(array(
								'error'=>'Cannot return that type of template'
							));
						}
					}else{
						retj(array(
							'error'=>'Template name missing'
						));
					}
				break;
				case 'action':
						switch($id){
							case 'login':
								$ret['state'] = array(
									'data'=>array(
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
								$ret['state'] = array(
									'data'=>array(
										'type'=>'page',
										'id'=>'register'
									)
								);
								if(is_valid('username')&& strpos($_GET['username'],' ') === false&&is_valid('password')&&is_valid('password1')&&is_valid('email')&&is_valid('captcha')){
									if($_GET['password']==$_GET['password1']){
										if(compare_captcha($_GET['captcha'])){
											if(addUser($_GET['username'],$_GET['password'],$_GET['email'])){
												$key = login($_GET['username'],$_GET['password']);
												$_SESSION['username'] = $_GET['username'];
												sendMail('welcome','Welcome!',$_GET['email'],get('email'),array($_GET['username'],$_GET['password'],get('email')));
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
								$ret['state'] = array(
									'data'=>array(
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
							case 'issue':
								back(true);
								$ret['state'] = array(
									'data'=>array(
										'type'=>'page',
										'id'=>$id,
									)
								);
								if(isset($_GET['pid'])){
									$ret['error'] = 'Invalid Action';
								}elseif(is_valid('title')&&is_valid('description')){
									if(!newIssue($_GET['title'],$_GET['description'])){
										$ret['error'] = 'Unable to create issue. ';
									}
								}else{
									$ret['error'] = 'Fill in all the details.';
								}
								retj($ret,$id);
							break;
							case 'message':
								back(true);
								if(isset($_GET['to'])&&isset($_GET['message'])){
									if($uid = userId($_GET['to'])){
										if(!personal_message($uid,$_GET['message'])){
											$ret['error'] = 'Could not send message';
										}
									}else{
										$ret['error'] = "That user doesn't exist";
									}
								}else{
									$ret['error'] = 'Empty details';
								}
								retj($ret,$id);
							break;
							case 'notifications':
								if($LOGGEDIN){
									if($res = query("SELECT count(m.id) as notifications,UNIX_TIMESTAMP(max(m.timestamp)) as timestamp FROM `messages` m JOIN `users` u ON u.id = m.to_id WHERE u.id = %d AND u.last_pm_check < m.timestamp;",array(userId($_SESSION['username'])))){
										$res = $res->fetch_assoc();
										$ret['count'] = $res['notifications'];
										$ret['timestamp'] = $res['timestamp'];
									}
								}
								retj($ret,$_GET['title']);
							break;
							case 'comment':
								if(isset($_GET['comment_type'])&&isset($_GET['comment_id'])&&isset($_GET['message'])){
									$cid = $_GET['comment_id'];
									$ret = array(
										'state'=>stateObj($_GET['comment_type'],$cid)
									);
									switch($_GET['comment_type']){
										case 'project':
											if(!function_exists('project_comment')){
												$ret['error'] = "fn doesn't exist!";
											}
											if(!project_comment($cid,$_GET['message'])){
												$ret = array(
													'error'=>'Could not comment on project'
												);
											}
										break;
										case 'issue':
											if(!function_exists('issue_comment')){
												$ret['error'] = "fn doesn't exist!";
											}
											if(!issue_comment($cid,$_GET['message'])){
												$ret = array(
													'error'=>'Could not comment on project'
												);
											}
										break;
										default:
											$ret['error'] = 'Comment type not implemented';
									}
								}else{
									$ret['error'] = 'Missing comment paremeters';
									$ret['state'] = array(
										'title'=>'error'
									);
								}
								retj($ret,$ret['state']['title']);
							break;
							case 'more':
								if(isset($_GET['of']) && isset($_GET['pid'])){
									$ret = array();
									$limit = array(
										isset($_GET['at'])?$_GET['at']:0,
										isset($_GET['amount'])?$_GET['amount']:10
									);
									$ret['messages'] = messages($_GET['pid'],$_GET['of'],$limit[0],$limit[1]);
									$ret['params'] = array($_GET['pid'],$_GET['of'],$limit[0],$limit[1]);
								}else{
									$ret['error'] = 'Missing comment parameters';
								}
								retj($ret);
							break;
							default:
								retj(array(
									'error'=>'Invalid action.'
								));
						}
				break;
				default:
					retj(array(
						'error'=>'Invalid type.'
					));
			}
		}else{
			retj(array(
				'error'=>'ID missing.'
			));
		}
	}else{
		$_GET['type'] = '';
		retj(array(
			'error'=>'Type missing.'
		));
	}
?>