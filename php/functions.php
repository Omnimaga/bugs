<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	// TODO - create php functions for the api
	function retj($json,$title){
		$type=$_GET['type'];
		$id=$_GET['id'];
		if(!isset($json['state'])){
			$json['state'] = Array();
		}
		if(!isset($json['state']['data'])){
			$json['state']['data'] = $_GET;
		}else{
			foreach($_GET as $key => $val){
				if(!isset($json['state']['data'][$key])){
					$json['state']['data'][$key] = $val;
				}
			}
		}
		if(isset($json['key'])){
			$json['state']['key'] = $json['key'];
		}
		$json['state']['title'] = $title;
		switch($type){
			case 'user':$url='~'.$id;break;
			case 'group':$url='+'.$id;break;
			case 'issue':$url='!'.$id;break;
			case 'template':$url='page-'.$id;break;
			default:$url=$type.'-'.$id;
		}
		$json['state']['url'] = $url;
		die(json_encode($json));
	}
?>