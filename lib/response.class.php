<?php
	class Response {
		public $output = '';
		public $body = '';
		public $url;
		private $code = 200;
		public $headers = array();
		protected $open = true;
		public function __construct($url){
			$this->url = $url;
		}
		public function __toString(){
			return $this->body;
		}
		public function clear(){
			if($this->open){
				$this->body = '';
			}
			return this;
		}
		public function write($chunk){
			if($this->open){
				$this->body .= $chunk;
			}
			return $this;
		}
		public function json($json){
			$this->write(json_encode($json));
			return $this;
		}
		public function header($name,$value){
			if($this->open){
				array_push(
					$this->headers,
					array(
						$name,
						$value
					)
				);
			}
			return $this;
		}
		public function end($chunk=''){
			if($this->open){
				$this->write($chunk);
				$this->open = false;
				http_response_code($this->code);
				foreach($this->headers as $k => $header){
					header("{$header[0]}: $header[1]");
				}
				flush();
			}
			return $this;
		}
		public function code($code=null){
			if(is_null($code)){
				return $this->code;
			}
			$this->code = $code;
			return $this;
		}
	}
?>