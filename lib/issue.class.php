<?php
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
				default:
					$this->cache[$name] = $value;
			}
		}
		public function __get($name){
			switch($name){
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