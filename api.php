<?php
	// MYSQL default bugs:bugs
	// TODO - Add API handling.
	$method = $_SERVER['REQUEST_METHOD'];
	if(isset($_GET['type'])){
		if(isset($_GET['id']){
			$id = $_GET['id'];
			switch($_GET['type']){
				case 'user':
					// TODO - handle user requests
				break;
				case 'group':
					// TODO - handle group requests
				break;
				case 'issue':
					// TODO - handle issue requests
				break;
				case 'scrum':
					// TODO - handle scrum requests
				break;
				case 'admin':
					// TODO - handle admin requests
				break;
				default:
					// TODO - handle type invalid
			}
		}else{
			// TODO - handle id missing
		}
	}else{
		// TODO - handle type missing
	}
?>