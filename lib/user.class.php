<?php
	class User implements JsonSerializable{
		public $id;
		public $cache = array(
			'name'=>null,
			'email'=>null,
			'date_registered'=>null,
			'date_modified'=>null,
			'active'=>null,
			'password'=>null,
			'salt'=> null
		);
		public function __construct($id){
			switch(func_num_args()){
				// name, email, password
				case 3:
					$name = func_get_arg(0);
					$email= func_get_arg(1);
					$this->cache['salt'] = md5($name.$email);
					$this->cache['password'] = $this->hash(func_get_arg(2));
					Bugs::$sql->query("
						INSERT INTO users (name,email,password,salt)
						VALUES (?,?,?,?)
					",'ssss',$name,$email,$this->password,$this->salt)->execute();
					$id = Bugs::$sql->insert_id;
					if($id === 0){
						trigger_error("Failed to create user with name {$name}.");
					}
				// id
				case 1:
					$this->id = intval($id);
					$cache = Bugs::$sql->query("
						SELECT	name,
								email,
								date_registered,
								date_modified,
								active,
								password,
								salt
						FROM users
						WHERE id = ?;
					",'i',$this->id)->assoc_result;
					if($cache){
						foreach($cache as $key => $value){
							$this->cache[$key] = $value;
						}
					}else{
						trigger_error("User with id {$id} does not exist");
					}
				break;
				default:
					trigger_error("Invalid Arguments");
			}
		}
		public function jsonSerialize(){
			return array(
				'id'=> $this->id,
				'name'=> $this->name,
				'email'=> $this->email,
				'date_registered'=> $this->date_registered,
				'date_modified'=> $this->date_modified
			);
		}
		public function __toString(){
			return $this->path;
		}
		public function __set($name,$value){
			switch($name){
				case 'name':case 'email':
					Bugs::$sql->query("
						UPDATE users
						SET {$name} = ?
						WHERE id = ?
					",'si',$value,$this->id)->execute();
					$this->cache[$name] = $value;
				break;
				case 'active':
					$value = $value?1:0;
					Bugs::$sql->query("
						UPDATE users
						SET active = ?
						WHERE id = ?
					",'is',$value,$this->id)->execute();
					$this->cache['active'] = $value;
				break;
				default:
					if(isset($this->cache[$name])){
						$this->cache[$name] = $value;
					}
			}
		}
		public function __get($name){
			switch($name){
				case 'active':
					return $this->cache['active']==1;
				break;
				case 'date_registered':case 'date_modified':
					return strtotime($this->cache[$name]);
				break;
				case 'activation_code':
					return hash_hmac('sha512',$this->name.$this->email.$this->date_registered,md5($this->name.$this->email));
				break;
				case 'login_key':
					return hash_hmac('sha512',date('c'),md5($this->date_registered));
				break;
				case 'sessions':
					return Bugs::$sql->query("
						SELECT  id,
								ip,
								info
						FROM sessions
						where u_id = ?
					",'i',$this->id)->assoc_results;
				break;
				case 'permissions':
					$perms = array();
					$res = Bugs::$sql->query("
						SELECT p.name
						FROM r_permission_user r
						JOIN permissions p
							ON p.id = r.per_id
						WHERE r.u_id = ?
					",'i',$this->id)->assoc_results;
					foreach($res as $row){
						array_push($perms,$row['name']);
					}
					return $perms;
				break;
				default:
					if(isset($this->cache)){
						return $this->cache[$name];
					}
			}
		}
		public function email($subject,$body){
			Bugs::$sql->query("
				INSERT INTO emails (u_id,subject,body)
				VALUES(?,?,?)
			",'iss',$this->id,$subject,$body)->execute();
		}
		public function hash($str){
			return hash_hmac('sha512',$str,$this->salt);
		}
		public function permission($permission){
			return Bugs::$sql->query("
				SELECT count(1) count
				FROM r_permission_user r
				JOIN permissions p
					ON p.id = r.per_id
				WHERE u_id = ?
				AND p.name IN (?,'*')
			",'is',$this->id,$permission)->assoc_result['count']!==0;
		}
	}
?>