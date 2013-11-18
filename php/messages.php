<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	function messages($id,$type){
		switch($type){
			case 'project':
				if($res = query("SELECT m.id, u.name, m.message, UNIX_TIMESTAMP(m.timestamp) as timestamp FROM `messages` m JOIN `users` u ON u.id = m.from_id WHERE m.p_id='%d'",Array($id))){
					return $res->fetch_all(MYSQLI_ASSOC);
				}
			break;
			case 'user':
				if($res = query("SELECT  m.id, uf.name as \"from\", ut.name as \"to\", m.message, UNIX_TIMESTAMP(m.timestamp) as timestamp FROM `messages` m JOIN `users` uf ON uf.id = m.from_id JOIN `users` ut ON ut.id = m.to_id WHERE (m.from_id='%d' AND m.to_id IS NOT NULL) OR m.to_id='%d'",Array($id,$id))){
					return $res->fetch_all(MYSQLI_ASSOC);
				}
			break;
		}
		return Array();
	}
?>