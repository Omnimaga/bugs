<?php
	require_once('dypath.class.php');
	class Path implements JsonSerializable{
		private $handles = array();
		public $path;
		public function __construct($path){
			$this->path = new DyPath($path);
		}
		public function __invoke($res,$args){
			$err = null;
			foreach($this->handles as $k => $fn){
				try{
					$fn($res,$args,$err);
				}catch(Exception $e){
					$err = $e;
				}
			}
		}
		public function __clone(){
			// No cloning for now
		}
		public function __destruct(){
			// Nothing to do here
		}
		public function jsonSerialize(){
			return array(
				'dypath'=>$this->path
			);
		}
		public function __toString(){
			return "[Path {$this->path}]";
		}
		public function __get($name){
			switch($name){
				
			}
		}
		public function __set($name,$value){
			switch($name){
				
			}
		}
		public function handle(Callable $fn){
			array_push($this->handles,$fn);
			return $this;
		}
		public function matches($path){
			return $this->path->matches($path);
		}
		public function args($path){
			return $this->path->args($path);
		}
	}
?>