<?php
	class User implements JsonSerializable{
		public $sql;
		public $id;
		public function __construct($id,$sql){
			$this->id = $id;
			$this->sql = $sql;
		}
		public function jsonSerialize(){
			return array(
				'id'=> $this->id
			);
		}
		public function __toString(){
			return $this->path;
		}
		public function __get($name){
			switch($name){
				
			}
		}
	}
?>