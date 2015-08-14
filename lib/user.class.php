<?php
	class User implements JsonSerializable{
		public $id;
		public $cache = array(
			'name'=>null,
			'email'=>null,
			'date_registered'=>null,
			'date_modified'=>null
		);
		public function __construct($id){
			$this->id = intval($id);
			$cache = Bugs::$sql->query("
				SELECT	name,
						email,
						date_registered,
						date_modified
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
				break;
				default:
					if(isset($this->cache[$name])){
						$this->cache[$name] = $value;
					}
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