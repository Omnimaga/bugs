<?php
	require_once('path.class.php');
	require_once('response.class.php');
	class Router {
		public static $paths = array();
		public static $base = '/';
		private static $responses = array();
		public function __construct(){
			// No Constructing
		}
		public function __clone(){
			// No cloning
		}
		public function __destruct(){
			$this->paths = array();
		}
		public function __toString(){
			return "[Router]";
		}
		public static function base($base){
			static::$base = $base;
		}
		public static function url($url){
			return preg_replace('/(\/+)/','/',$url);
		}
		// fn = function(response,args){}
		public static function path($path,$fn){
			$obj = false;
			foreach(static::$paths as $k => $p){
				if($p->path == $path){
					$obj = $p;
				}
			}
			if(!$obj){
				$obj = new Path($path);
				array_push(static::$paths,$obj);
			}
			return $obj->handle($fn);
		}
		public static function paths($paths){
			foreach($paths as $path => $fn){
				static::path($path,$fn);
			}
		}
		public static function clear(){
			static::$paths = array();
		}
		public static function handle($url,$res = null,$fn = null){
			if(strpos($url,static::$base) !== false){
				$url = rtrim(substr($url,strpos($url,static::$base)+strlen(static::$base)),'/');
				if(empty($url) || $url[0] != '/'){
					$url = '/'.$url;
				}
				if(is_null($res)){
					$res = new Response($url);
				}
				if(!in_array($res,static::$responses)){
					array_push(static::$responses,$res);
				}
				ob_start();
				$handled = false;
				foreach(static::$paths as $k => $p){
					if($p->matches($url)){
						$handled = true;
						$p($res,$p->args($url));
					}
				}
				if(!$handled && !is_null($fn)){
					$fn($res,$url);
				}
				$res->output = ob_get_contents();
				ob_end_clean();
			}
			return $res;
		}
		public static function shutdown(){
			foreach(static::$responses as $k => $res){
				if($res instanceof Response){
					echo $res->end()->body;
				}
			}
		}
		public static function write($chunk){
			if(!isset(static::$responses[0])){
				array_push(static::$responses,new Response($_SERVER['REQUEST_URI']));
			}
			static::$responses[0]->write($chunk);
		}
		public static function redirect($url){
			static::clear();
			static::$responses[0]->header('Location',$url);

		}
	}
	register_shutdown_function(function(){
		Router::shutdown();
	});
?>