<?php
	// MYSQL default bugs:bugs
	// TODO - Add API handling.
	$method = $_SERVER['REQUEST_METHOD'];
	if(isset($_GET['type'])){
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			switch($_GET['type']){
				case 'install':
					
				break;
				default:
					require_once('../api.php');
			}
		}else{
			die("id missing");
		}
	}else{
		die("type missing");
	}
?>