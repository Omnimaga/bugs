<?php
	require_once('arguments.class.php');
	class DyPath implements JsonSerializable{
		public $path;
		public function __construct($path){
			$this->path = $path;
		}
		public function jsonSerialize(){
			return array(
				'path'=>$this->path,
				'regex'=>$this->regex,
				'arguments'=>$this->arguments
			);
		}
		public function __toString(){
			return $this->path;
		}
		public function __get($name){
			switch($name){
				case 'arguments':
					preg_match_all('/\{([^#\/][^}\n]+?)\}/',$this->path,$m,PREG_SET_ORDER);
					return $m;
				break;
				case 'regex':
					return '/^'.preg_replace('/\\\{[^#\/][^}\n]+?\\\}/','([^\/]*)',preg_quote($this->path,'/')).'$/';
				break;
			}
		}
		public function matches($url){
			return preg_match($this->regex,$url);
		}
		public function args($url){
			$ret = array();
			preg_match_all($this->regex,$url,$m,PREG_SET_ORDER);
			foreach($this->arguments as $k => $arg){
				$ret[$arg[1]] = $m[0][$k+1];
			}
			return new Arguments($ret);
		}
	}
?>