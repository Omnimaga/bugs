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
		if(file_exists(PATH_DATA.'topbars/'.$id.'.options')){
			$options = objectToarray(json_decode(file_get_contents(PATH_DATA.'topbars/'.$id.'.options'),true));
			foreach($options as $key => $option){
				$context[$key] = $option;
			}
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
		// Sidebar
		if(isset($json['sidebar'])){
			$sidebar = $json['sidebar'];
		}else{
			$sidebar = 'default';
		}
		$json['sidebar'] = array(
			'template'=>$sidebar,
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
				'url'=>'page-index'
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
	function vnsprintf($format,array $data){
		preg_match_all('/ (?<!%) % ( (?: [[:alpha:]_-][[:alnum:]_-]* | ([-+])? [0-9]+ (?(2) (?:\.[0-9]+)? | \.[0-9]+ ) ) ) \$ [-+]? \'? .? -? [0-9]* (\.[0-9]+)? \w/x', $format, $match, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
		$offset = 0;
		$keys = array_keys($data);
		foreach( $match as &$value ){
			if(($key = array_search( $value[1][0], $keys, TRUE) ) !== FALSE || ( is_numeric( $value[1][0] ) && ( $key = array_search( (int)$value[1][0], $keys, TRUE) ) !== FALSE) ){
			$len = strlen( $value[1][0]);
			$format = substr_replace( $format, 1 + $key, $offset + $value[1][1], $len);
			$offset -= $len - strlen( 1 + $key);
			}
		}
		return vsprintf( $format, $data);
	}
?>