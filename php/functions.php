<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	// TODO - create php functions for the api
	function retj($json,$title){
		$type=$_GET['type'];
		$id=$_GET['id'];
		$json['state'] = Array();
		$json['state']['data'] = $_GET;
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