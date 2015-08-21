<?php
	require_once('sql.class.php');
	require_once('template.class.php');
	require_once('user.class.php');
	require_once('project.class.php');
	class Bugs {
		public static $sql;
		public static $cache = array(
			'users'=>array(),
			'issue'=>array()
		);
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
		}
		static function user($id){
			if(func_num_args()==1){
				if(is_string($id)){
					$id = Bugs::user_id($id);
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
		static function issue($id){
			if(!isset(static::$cache['issues'][$id])){
				static::$cache['issues'][$id] = new Issue($id);
			}
			return static::$cache['issues'][$id];
		}
		static function project($id){
			if(is_string($id)){
				$id = static::$sql->query("
					SELECT id
					FROM projects
					WHERE name = ?;
				",'s',$id)->assoc_result['id'];
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
			$status = @mail($email['email'],$email['subject'],$email['body'],"From: ".ADMIN_EMAIL."\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\n");
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