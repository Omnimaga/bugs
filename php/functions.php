<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	// TODO - create php functions for the api
	function retj($json,$title=null){
		global $LOGGEDIN;
		$type=$_GET['type'];
		$id=$_GET['id'];
		// State
		if(!isset($json['state'])){
			$json['state'] = Array();
		}
		unset($_GET['password']);
		unset($_GET['password1']);
		if(!isset($json['state']['data'])){
			$json['state']['data'] = $_GET;
		}else{
			foreach($_GET as $key => $val){
				if(!isset($json['state']['data'][$key])&&$key!='password'){
					$json['state']['data'][$key] = $val;
				}
			}
		}
		// Title
		if(is_null($title)){
			if(!isset($context['title'])){
				$title = $_GET['id'];
			}else{
				$title = $context['title'];
			}
		}
		$json['state']['title'] = $title;
		if(!isset($json['state']['url'])){
			// URL
			switch($type){
				case 'user':$url='~'.$id;break;
				case 'group':$url='+'.$id;break;
				case 'issue':$url='!'.$id;break;
				case 'action':$url='';break;
				default:$url=$type.'-'.$id;
			}
			$json['state']['url'] = $url;
		}else{
			$url = $json['state']['url'];
		}
		// Tobar
		if($LOGGEDIN){
			$context = Array(
				'user'=>userObj($_SESSION['username']),
				'key'=>true
			);
		}else{
			$context = Array();
		}
		$context['title'] = $title;
		$context['url'] = $url;
		if(file_exists(PATH_DATA.'topbars/'.$type.'-'.$id)){
			$topbar = file_get_contents(PATH_DATA.'topbars/'.$type.'-'.$id.'.template');
		}else{
			$topbar = file_get_contents(PATH_DATA.'topbars/default.template');
		}
		$json['topbar'] = Array(
			'template'=>$topbar,
			'context'=>$context
		);
		die(json_encode($json));
	}
	function is_valid($col,$v=null){
		if($v == null){
			$v = $_GET;
		}
		if(isset($v[$col]) && !empty($v[$col])){
			$v = $v[$col];
			switch($col){
				case 'email':
					return filter_var($v,FILTER_VALIDATE_EMAIL) != false;
				case 'username':
					return strip_tags($v) == $v;
				default:
					return true;
			}
		}else{
			return false;
		}
	}
	function back($ifNotLoggedIn=false){
		global $LOGGEDIN;
		if($ifNotLoggedIn && $LOGGEDIN){
			return false;
		}
		retj(Array(
			'state'=>Array(
				'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
			)
		));
	}
?>