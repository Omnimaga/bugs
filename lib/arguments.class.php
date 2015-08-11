<?php
	class Arguments implements JsonSerializable, ArrayAccess{
		public $args;
		public function __construct($args){
			$this->args = $args;
		}
		public function jsonSerialize(){
			return $this->args;
		}
		public function __toString(){
			return $this->path;
		}
		public function __get($name){
			if(isset($this->args[$name])){
				return $this->args[$name];
			}
		}
		public function offsetGet($key){
			return $this->args[$key];
		}
		public function offsetExists($key){
			return isset($this->args[$key]);
		}
		public function offsetSet($key,$val){
			$this->args[$key] = $val;
		}
		public function offsetUnset($key){
			unset($this->args[$key]);
		}
	}
?>