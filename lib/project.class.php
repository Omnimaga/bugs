<?php
	require_once('issue.class.php');
	class Project implements JsonSerializable{
		public $id;
		public $cache = array(
			'p_id'=>null,
			's_id'=>null,
			'u_id'=>null,
			'name'=>null,
			'description'=>null,
			'date_created'=>null,
			'date_modified'=>null
		);
		public function __construct($id){
			switch(func_num_args()){
				// name, description, user
				case 3:
					$user = func_get_arg(2)?func_get_arg(2):Bugs::$user;
					$name= func_get_arg(0);
					Bugs::$sql->query("
						INSERT INTO projects (name,description,u_id,s_id)
						VALUES (?,?,?,getsetting('project.default.status'))
					",'ssi',
						func_get_arg(0),
						func_get_arg(1),
						$user->id
					)->execute();
					$id = Bugs::$sql->insert_id;
					if($id === 0){
						trigger_error("Failed to create project with name {$name}.");
					}
				// id
				case 1:
					$this->id = intval($id);
					$cache = Bugs::$sql->query("
						SELECT	p_id,
								s_id,
								u_id,
								name,
								description,
								date_created,
								date_modified
						FROM projects
						WHERE id = ?;
					",'i',$this->id)->assoc_result;
					if($cache){
						foreach($cache as $key => $value){
							$this->cache[$key] = $value;
						}
					}else{
						trigger_error("Project with id {$id} does not exist");
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
				'description'=> $this->description,
				'date_created'=> $this->date_created,
				'date_modified'=> $this->date_modified
			);
		}
		public function __toString(){
			return $this->path;
		}
		public function __set($name,$value){
			switch($name){
				case 'name':case 'description':
					Bugs::$sql->query("
						UPDATE projects
						SET {$name} = ?
						WHERE id = ?
					",'si',$value,$this->id)->execute();
					$this->cache[$name] = $value;
				break;
				case 'p_id':case 's_id':case 'u_id':
					Bugs::$sql->query("
						UPDATE projects
						SET {$name} = ?
						WHERE id = ?
					",'ii',$value,$this->id)->execute();
					$this->cache[$name] = $value;
				break;
				case 'parent':
					if($value instanceof Project){
						$this->p_id = $value->id;
					}
				break;
				case 'user':
					if($value instanceof User){
						$this->u_id = $value->id;
					}
				break;
				default:
					if(isset($this->cache[$name])){
						$this->cache[$name] = $value;
					}
			}
		}
		public function __get($name){
			switch($name){
				case 'parent':
					if(isset($this->p_id)){
						return Bugs::Project($this->p_id);
					}
				break;
				case 'date_created':case 'date_modified':
					return strtotime($this->cache[$name]);
				break;
				case 'user':
					return Bugs::user($this->u_id);
				break;
				case 'users':
					$users = Bugs::$sql->query("
						SELECT	r.u_id,
								pr.name
						FROM r_project_user r
						RIGHT JOIN project_roles pr
							ON pr.id = r.r_id
						WHERE r.p_id = ?
					",'i',$this->id)->assoc_results;
					$ret = array();
					foreach($users as $user){
						if(!isset($ret[$user['name']])){
							$ret[$user['name']] = array();
						}
						if(!is_null($user['u_id'])){
							array_push($ret[$user['name']],Bugs::user($user['u_id']));
						}
					}
					return $ret;
				break;
				case 'user_ids':
					return array_column(
						Bugs::$sql->query("
							SELECT	distinct r.u_id
							FROM r_project_user r
							RIGHT JOIN project_roles pr
								ON pr.id = r.r_id
							WHERE r.p_id = ?
						",'i',$this->id)->assoc_results,
						'u_id'
					);
				break;
				case 'roles':
					return $this->roles(Bugs::$user);
				break;
				case 'issue_ids':
					return array_column(
						Bugs::$sql->query("
							SELECT id
							FROM issues
							WHERE p_id = ?
						",'i',$this->id)->assoc_results,
						'id'
					);
				break;
				case 'issues':
					$issues = array();
					foreach($this->issue_ids as $id){
						array_push($issues,Bugs::issue($id));
					}
					return $issues;
				break;
				case 'status':
					return Bugs::status($this->s_id);
				break;
				default:
					if(isset($this->cache)){
						return $this->cache[$name];
					}
			}
		}
		public function permission($permission,$user=null){
			$user = is_null($user)?Bugs::$user:$user;
			return $user->admin || (
				$user->permission('project_'.$permission) &&
				in_array($user->id, $this->user_ids)
			);
		}
		public function roles($user){
			return array_column(
				Bugs::$sql->query("
					SELECT	distinct pr.name
					FROM r_project_user r
					RIGHT JOIN project_roles pr
						ON pr.id = r.r_id
					WHERE r.p_id = ?
					AND r.u_id = ?
				",'ii',$this->id,$user->id)->assoc_results,
				'name'
			);
		}
		public function role($role,$user=null){
			return Bugs::$sql->query("
				SELECT	count(1) count
				FROM r_project_user r
				RIGHT JOIN project_roles pr
					ON pr.id = r.r_id
					AND pr.name = ?
				WHERE r.p_id = ?
				AND r.u_id = ?
			",'sii',$role,$this->id,$user?$user->id:Bugs::$user->id)->assoc_result['count']!==0;
		}
		public function issue($name,$description,$priority=null,$user=null){
			return Bugs::issue($name,$description,$priority,$user,$this);
		}
	}
?>