<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	function get_sql(){
		static $sql;
		if(is_null($sql)){
			$sql = new mysqli(get('host'),get('user'),get('password'),get('database'));
			if($sql && $sql->connect_errno){
				die("Failed to connect to MySQL: ".$sql->connect_error);
			}
			if(!$sql->autocommit(true)){
				die("Failed to connect to MySQL: ".$sql->connect_error);
			}
		}
		return $sql;
	}
	function query($query,$args=Array()){
		$sql = get_sql();
		for ($i=0;$i<count($args);$i++){
			if(is_string($args[$i])){
				$args[$i] = get_sql()->real_escape_string($args[$i]);
			}elseif(!is_numeric($args[$i])){
				return false;
			}
		}
		return get_sql()->query(vsprintf($query,$args));
	}
	function fetch_all($result,$type=MYSQLI_NUM){
		if(method_exists('mysqli_result', 'fetch_all')){
			$res = $result->fetch_all($type);
		}else{
			for($res = array(); $tmp = $result->fetch_array($type);){
				$res[] = $tmp;
			}
		}
		return $res;
	}
?>