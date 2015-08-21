<?php
	global $_RAWDATA, $_DATA;
	if($_SERVER['REQUEST_METHOD']!='GET'){
		$_RAWDATA = file_get_contents( 'php://input','r');
		$_DATA = json_decode($_RAWDATA,true);
	}else{
		$_RAWDATA = '';
		$_DATA = array();
	}
	require_once('config.php');
	require_once('lib/bugs.class.php');
	require_once('lib/router.class.php');
	require_once('lib/errorhandler.php');
	Bugs::connect(DB_HOST,DB_USER,DB_PASS,DB);
	foreach(glob("paths/*.php") as $filename){
		if(basename($filename)!='index.php'){
			require_once($filename);
		}
	}
	Router::base(URL_BASE);
	if(empty($_SERVER['REDIRECT_URL'])){
		$_SERVER['REDIRECT_URL'] = 'http://'.URL_HOST.URL_BASE;
	}
	Router::handle($_SERVER['REDIRECT_URL'],null,function($res,$url){
		trigger_error("Not implemented");
	});
?>