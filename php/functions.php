<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	// TODO - create php functions for the api
	function retj($json,$title=null){
		global $LOGGEDIN;
		$type=$_GET['type'];
		$id=$_GET['id'];
		if(!isset($_GET['no_state'])){
			// State
			if(!isset($json['state'])){
				$json['state'] = array();
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
		}
		// Title
		if(is_null($title)){
			if(isset($json['title'])){
				$title = $json['title'];
			}elseif(isset($json['state']['title'])&&!isset($_GET['no_state'])){
				$title = $json['state']['title'];
			}else{
				$title = $_GET['id'];
			}
		}
		if(!isset($_GET['no_state'])){
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
		}
		// Tobar
		if($LOGGEDIN){
			$context = array(
				'user'=>userObj($_SESSION['username']),
				'key'=>true
			);
		}else{
			$context = array();
		}
		$context['title'] = $title;
		if(!isset($_GET['no_state'])){
			$context['url'] = $url;
		}
		if(isset($json['topbar'])){
			$topbar = $json['topbar'];
		}else{
			$topbar = 'default';
		}
		$json['topbar'] = array(
			'template'=>$topbar,
			'context'=>$context
		);
		header('Content-type: application/json');
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
		retj(array(
			'state'=>array(
				'url'=>isset($_GET['back'])?$_GET['back']:'page-index'
			)
		));
	}
	function stateObj($type,$id){
		$json = array(
			'data'=>$_GET
		);
		$title = ucwords($type.' - '.$id);
		switch($type){
			case 'user':$url='~'.$id;break;
			case 'group':$url='+'.$id;break;
			case 'issue':
				$url = '!'.$id;
				$title = issueObj($id);
				$title = 'Issue #'.$title['title'];
			break;
			case 'page':$url='page-'.$id;break;
			case 'project':
				$url = $type.'-'.$id;
				$title = projectObj($id);
				$title = 'Project - '.$title['title'];
			break;
			default:$url=$type.'-'.$id;
		}
		$json['url'] = $url;
		$json['title'] = $title;
		return $json;
	}
?>