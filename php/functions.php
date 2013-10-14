<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	// TODO - create php functions for the api
	function retj($json,$title=null){
		if(is_null($title)){
			$title = $_GET['id'];
		}
		$type=$_GET['type'];
		$id=$_GET['id'];
		if(!isset($json['state'])){
			$json['state'] = Array();
		}
		unset($_GET['password']);
		if(!isset($json['state']['data'])){
			$json['state']['data'] = $_GET;
		}else{
			foreach($_GET as $key => $val){
				if(!isset($json['state']['data'][$key])&&$key!='password'){
					$json['state']['data'][$key] = $val;
				}
			}
		}
		$json['state']['title'] = $title;
		switch($type){
			case 'user':$url='~'.$id;break;
			case 'group':$url='+'.$id;break;
			case 'issue':$url='!'.$id;break;
			case 'template':$url='page-'.$id;break;
			case 'action':$url='';break;
			default:$url=$type.'-'.$id;
		}
		$json['state']['url'] = $url;
		die(json_encode($json));
	}
?>