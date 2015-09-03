<?php
	require_once('sql.class.php');
	require_once('template.class.php');
	require_once('user.class.php');
	require_once('project.class.php');
	require_once('router.class.php');
	class Bugs {
		public static $sql;
		public static $cache = array(
			'users'=>array(),
			'issue'=>array(),
			'projects'=>array(),
			'statuses'=>array(),
			'priorities'=>array()
		);
		public static $user = false;
		public function __construct(){

		}
		public function __set($name,$value){
			switch($name){
				default:
					static::$cache[$name] = $value;
			}
		}
		public function __get($name){
			switch($name){
				default:
					if(isset(static::$cache)){
						return static::$cache[$name];
					}
			}
		}
		static function connect($server='localhost',$user='bugs',$pass='bugs',$db='bugs'){
			static::$sql = new SQL($server,$user,$pass,$db);
			static::$sql->query("
				DELETE FROM sessions
				WHERE date < TIMESTAMP(DATE_SUB(NOW(), INTERVAL 10 day))
			")->execute();
			if(session_status() == PHP_SESSION_NONE){
				session_start();
			}
			if(isset($_COOKIE['key']) && isset($_SESSION['key']) && isset($_SESSION['user']) && static::user_id($_SESSION['user']) && $_SESSION['key'] == $_COOKIE['key']){
				$user = static::user($_SESSION['user']);
				$session = static::$sql->query("
					SELECT count(id) AS count
					FROM sessions
					WHERE id = ?
					AND u_id = ?
					AND ip = ?
				",'sis',$_SESSION['key'],$user->id,static::ip())->assoc_result;
				if($session && intval($session['count']) == 1){
					static::$user = $user;
					static::$sql->query("
						UPDATE sessions
						SET date = CURRENT_TIMESTAMP
						WHERE id = ?
						AND u_id = ?
						AND ip = ?
					",'sis',$_SESSION['key'],$user->id,static::ip())->execute();
				}else{
					static::logout();
				}
			}
			Router::base(static::setting('url.base'));
		}
		static function ip(){
		    if(getenv('HTTP_CLIENT_IP')){
				$ipaddress = getenv('HTTP_CLIENT_IP');
		    }else if(getenv('HTTP_X_FORWAR0DED_FOR')){
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		    }else if(getenv('HTTP_X_FORWARDED')){
				$ipaddress = getenv('HTTP_X_FORWARDED');
		    }else if(getenv('HTTP_FORWARDED_FOR')){
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
		    }else if(getenv('HTTP_FORWARDED')){
				$ipaddress = getenv('HTTP_FORWARDED');
		    }else if(getenv('REMOTE_ADDR')){
				$ipaddress = getenv('REMOTE_ADDR');
		    }else{
				$ipaddress = 'UNKNOWN';
		    }
		    return $ipaddress;
		}
		static function user_agent(){
			return substr($_SERVER['HTTP_USER_AGENT'],0,4000);
		}
		static function session(){
			return $_SESSION['key'];
		}
		static function login($user,$pass){
			if(!$user instanceof User && static::user_id($user)){
				$user = static::user($user);
			}
			if($user instanceof User && $user->active && $user->hash($pass) == $user->password){
				$key = $user->login_key;
				static::$sql->query("
					INSERT INTO sessions (id,u_id,ip,info)
					VALUES (?,?,?,?)
				",'siss',$key,$user->id,static::ip(),static::user_agent())->execute();
				$_SESSION['user'] = $user->name;
				setcookie('user',$user->name,0,'/');
				$_SESSION['key'] = $key;
				setcookie('key',$key,0,'/');
				static::$user = $user;
			}
			return static::$user !== false;
		}
		static function logout(){
			if(static::$user){
				static::$sql->query("
					DELETE FROM sessions
					WHERE id = ?
					AND u_id = ?
					AND ip = ?
				",'sis',$_SESSION['key'],static::$user->id,static::ip())->execute();
			}
			unset($_SESSION['user']);
			unset($_SESSION['key']);
			unset($_COOKIE['user']);
			unset($_COOKIE['key']);
			setcookie("user", "", time()-3600, '/');
			setcookie("key", "", time()-3600, '/');
			static::$user = false;
		}
		static function user($id){
			if(func_num_args()==1){
				if(is_string($id)){
					$id = static::user_id($id);
					if(!$id){
						trigger_error("User {$id} does not exist");
					}
				}
			}else{
				$id = new User(func_get_arg(0),func_get_arg(1),func_get_arg(2));
				$id = $id->id;
			}
			if(!isset(static::$cache['users'][$id])){
				static::$cache['users'][$id] = new User($id);
			}
			return static::$cache['users'][$id];
		}
		static function user_id($name){
			$user = static::$sql->query("
				SELECT id
				FROM users
				WHERE name = ?;
			",'s',$name)->assoc_result;
			if(is_null($user)){
				return false;
			}else{
				return $user['id'];
			}
		}
		static function project_id($name){
			$project = static::$sql->query("
				SELECT id
				FROM projects
				WHERE name = ?;
			",'s',$name)->assoc_result;
			if(is_null($project)){
				return false;
			}else{
				return $project['id'];
			}
		}
		static function issue($id){
			if(func_num_args()>1){
				$id = new Issue(
					func_get_arg(0),
					func_get_arg(1),
					func_num_args()>=3?func_get_arg(2):null,
					func_num_args()>=4?func_get_arg(3):null,
					func_num_args()==5?func_get_arg(4):null
				);
				$id = $id->id;
			}
			if(!isset(static::$cache['issues'][$id])){
				static::$cache['issues'][$id] = new Issue($id);
			}
			return static::$cache['issues'][$id];
		}
		static function project($id){
			if(func_num_args()==1){
				if(is_string($id)){
					$id = static::$sql->query("
						SELECT id
						FROM projects
						WHERE name = ?;
					",'s',$id)->assoc_result['id'];
				}
			}else{
				$id = new Project(func_get_arg(0),func_get_arg(1),func_num_args()==3?func_get_arg(2):null);
				$id = $id->id;
			}
			if(!isset(static::$cache['projects'][$id])){
				static::$cache['projects'][$id] = new Project($id);
			}
			return static::$cache['projects'][$id];
		}
		static function template($name){
			return new Template($name);
		}
		static function actions(){
			$args = func_get_args();
			foreach($args as $action){
				static::action($action);
			}
		}
		static function status($id){
			if(empty(static::$cache['statuses'][$id])){
				static::$cache['statuses'][$id] = static::$sql->query("
					SELECT max(name) name
					FROM statuses
					WHERE id = ?
				",'i',intval($id))->assoc_result['name'];
			}
			return static::$cache['statuses'][$id];
		}
		static function priority($id){
			if(empty(static::$cache['priorities'][$id])){
				static::$cache['priorities'][$id] = static::$sql->query("
					SELECT max(name) name
					FROM priorities
					WHERE id = ?
				",'i',intval($id))->assoc_result['name'];
			}
			return static::$cache['priorities'][$id];
		}
		static function action($action){
			$id = static::$sql->query("
				SELECT id
				FROM actions
				where name = ?
			",'s',$action)->assoc_result;
			if($id){
				$id = $id['id'];
			}else{
				static::$sql->query("
					INSERT INTO actions (name)
					VALUES (?)
				",'s',$action)->execute();
				$id = static::$sql->insert_id;
			}
			return $id;
		}
		static function activity($action,$description){
			static::$sql->query("
				INSERT INTO activities (a_id,description)
				VALUES (?,?)
			",'is',static::action($action),$description)->execute();
		}
		static function setting($name){
			return static::$sql->query("
				SELECT getsetting(?)
				FROM DUAL;
			",'s',$name)->num_result[0];
		}
	}
	register_shutdown_function(function(){
		$emails = Bugs::$sql->query("
			SELECT	u.email,
					u_id,
					e.subject,
					e.body,
					e.date_created
			FROM emails e
			JOIN users u
				ON u.id = e.u_id
			ORDER by e.date_created ASC
		")->assoc_results;
		foreach($emails as $email){
			$status = @mail($email['email'],$email['subject'],$email['body'],"From: ".Bugs::setting('admin.email')."\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\n");
			if($status){
				Bugs::$sql->query("
					DELETE FROM emails
					WHERE subject = ?
					AND body = ?
					AND u_id = ?
				",'ssi',$email['subject'],$email['body'],$email['u_id'])->execute();
			}
		}
	});
?>