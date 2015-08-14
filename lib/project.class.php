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
			$this->id = intval($id);
			$cache = Bugs::$sql->query("
				SELECT	p_id,
						s_id,
						u_id,
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
						UPDATE users
						SET {$name} = ?
						WHERE id = ?
					",'si',$value,$this->id)->execute();
				break;
				case 'p_id':case 's_id':case 'u_id':
					Bugs::$sql->query("
						UPDATE users
						SET {$name} = ?
						WHERE id = ?
					",'ii',$value,$this->id)->execute();
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
				case 'date_registered':case 'date_modified':
					return strtotime($this->cache[$name]);
				break;
				default:
					if(isset($this->cache)){
						return $this->cache[$name];
					}
			}
		}
	}
?>