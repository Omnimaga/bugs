<?php
	if(!defined('DEFAULT_PRIORITY')){
		define('DEFAULT_PRIORITY',4);
	}
	class Issue implements JsonSerializable{
		public $id;
		public $cache = array(
			'p_id'=>null,
			'u_id'=>null,
			'pr_id'=>null,
			's_id'=>null,
			'name'=>null,
			'description'=>null,
			'date_created'=>null,
			'date_modified'=>null
		);
		public function __construct($id){
			//name,description[,priority[,user[,project]]]
			switch(func_num_args()){
				case 5:case 4:case 3:case 2:
					$name = func_get_arg(0);
					if(
						Bugs::$sql->query("
							INSERT INTO issues (name,description,pr_id,u_id,p_id,s_id)
							VALUES (?,?,?,?,?,1);
						",'ssiii',
							$name,
							func_get_arg(1),
							func_num_args()>=3&&!empty(func_get_arg(2))?func_get_arg(2):DEFAULT_PRIORITY,
							func_num_args()>=4&&!empty(func_get_arg(3))?func_get_arg(3)->id:Bugs::$user->id,
							func_num_args()==5&&!empty(func_get_arg(4))?func_get_arg(4)->id:null
						)->execute()
					){
						$id = Bugs::$sql->insert_id;
						if($id === 0){
							trigger_error("Failed to create issue with name {$name}.");
						}
					}else{
						trigger_error(Bugs::$sql->error);
					}
				case 1:
					$this->id = intval($id);
					$cache = Bugs::$sql->query("
						SELECT	p_id,
								u_id,
								pr_id,
								s_id,
								name,
								description,
								date_created,
								date_modified
						FROM issues
						WHERE id = ?;
					",'i',$this->id)->assoc_result;
					foreach($cache as $key => $value){
						$this->cache[$key] = $value;
					}
				break;
				default:
					trigger_error('Invalid Arguments');
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
						UPDATE issues
						SET {$name} = ?
						WHERE id = ?
					",'si',$value,$this->id)->execute();
					$this->cache[$name] = $value;
				break;
				case 'p_id':case 's_id':case 'u_id':case 'pr_id':
					Bugs::$sql->query("
						UPDATE issues
						SET {$name} = ?
						WHERE id = ?
					",'ii',$value,$this->id)->execute();
				break;
				case 'project':case 'parent':
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
				case 'date_created':case 'date_modified':
					return strtotime($this->cache[$name]);
				break;
				case 'user_ids':
					return array_column(
						Bugs::$sql->query("
							SELECT	distinct r.u_id
							FROM r_issue_user r
							RIGHT JOIN issue_roles ir
								ON ir.id = r.r_id
							WHERE r.i_id = ?
						",'i',$this->id)->assoc_results,
						'u_id'
					);
				break;
				case 'users':
					$users = Bugs::$sql->query("
						SELECT	r.u_id,
								pr.name
						FROM r_issue_user r
						RIGHT JOIN issue_roles ir
							ON ir.id = r.r_id
						WHERE r.i_id = ?
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
				case 'roles':
					return $this->roles(Bugs::$user);
				break;
				case 'user':
					return Bugs::user($this->u_id);
				break;
				case 'project':
					return $this->p_id?Bugs::project($this->p_id):false;
				break;
				case 'status':
					return Bugs::status($this->s_id);
				break;
				case 'priority':
					return Bugs::priority($this->pr_id);
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
				$user->permission('issue_'.$permission) &&
				in_array($user->id, $this->user_ids)
			);
		}
		public function roles($user){
			return array_column(
				Bugs::$sql->query("
					SELECT	distinct pr.name
					FROM r_issue_user r
					RIGHT JOIN issue_roles ir
						ON ir.id = r.r_id
					WHERE r.i_id = ?
					AND r.u_id = ?
				",'ii',$this->id,$user->id)->assoc_results,
				'name'
			);
		}
		public function role($role,$user=null){
			return Bugs::$sql->query("
				SELECT	count(1) count
				FROM r_issue_user r
				RIGHT JOIN issue_roles ir
					ON ir.id = r.r_id
					AND ir.name = ?
				WHERE r.i_id = ?
				AND r.u_id = ?
			",'sii',$role,$this->id,$user?$user->id:Bugs::$user->id)->assoc_result['count']!==0;
		}
	}
?>