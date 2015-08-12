<?php
	class Template {
		public $name;
		public function __construct($name){
			$this->name = $name;
		}
		public function __get($name){
			switch($name){
				case 'path':
					return realpath(dirname(__FILE__)).'/../templates/'.$this->name.'.php';
				break;
			}
		}
		public function __invoke($context){
			ob_start();
			$GLOBALS['context'] = $context;
			if(file_exists($this->path) && is_file($this->path)){
				include($this->path);
			}
			$ret = ob_get_contents();
			ob_end_clean();
			return $ret;
		}
		public function __toString(){
			return $this(array());
		}
		public function run($context){
			return $this($context);
		}
	}
?>