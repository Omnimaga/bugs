<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	function messages($id,$type,$start=0,$amount=10){
		switch($type){
			case 'project':
				if($res = query("SELECT m.id, u.name, m.message, UNIX_TIMESTAMP(m.timestamp) as timestamp FROM `messages` m JOIN `users` u ON u.id = m.from_id WHERE m.p_id='%d' ORDER BY m.timestamp DESC LIMIT %d,%d",array($id,$start,$amount))){
					$arr = array();
					while($row = $res->fetch_assoc()){
						array_push($arr,$row);
					}
					return $arr;
				}
			break;
			case 'user':
				if($res = query("SELECT  m.id, uf.name as \"from\", ut.name as \"to\", m.message, UNIX_TIMESTAMP(m.timestamp) as timestamp, m.timestamp > ut.last_pm_check as is_new FROM `messages` m JOIN `users` uf ON uf.id = m.from_id JOIN `users` ut ON ut.id = m.to_id WHERE (m.from_id='%d' AND m.to_id IS NOT NULL) OR m.to_id='%d' ORDER BY m.timestamp DESC LIMIT %d,%d",array($id,$id,$start,$amount))){
					$arr = array();
					while($row = $res->fetch_assoc()){
						$msg = array(
							'id'=>$row['id'],
							'from'=>$row['from'],
							'to'=>$row['to'],
							'message'=>$row['message'],
							'timestamp'=>$row['timestamp']
						);
						if($row['is_new']){
							$msg['is_new'] = true;
						}
						array_push($arr,$msg);
					}
					return $arr;
				}
			break;
			case 'issue':
				if($res = query("SELECT m.id, u.name, m.message, UNIX_TIMESTAMP(m.timestamp) as timestamp FROM `messages` m JOIN `users` u ON u.id = m.from_id WHERE m.i_id='%d' ORDER BY m.timestamp DESC LIMIT %d,$d",array($id,$start,$amount))){
					$arr = array();
					while($row = $res->fetch_assoc()){
						array_push($arr,$row);
					}
					return $arr;
				}
			break;
		}
		return array();
	}
	function project_comment($project,$message){
		if(query("INSERT INTO `bugs`.`messages` (`id`,`timestamp`,`from_id`,`to_id`,`p_id`,`s_id`,`i_id`,`message`) VALUES(NULL,CURRENT_TIMESTAMP,'%d',NULL,'%d',NULL,NULL,'%s');",array(userId($_SESSION['username']),$project,$message))){
			return true;
		}
		return false;
	}
	function issue_comment($issue,$message){
		if(query("INSERT INTO `bugs`.`messages` (`id`,`timestamp`,`from_id`,`to_id`,`p_id`,`s_id`,`i_id`,`message`) VALUES(NULL,CURRENT_TIMESTAMP,'%d',NULL,NULL,NULL,'%d','%s');",array(userId($_SESSION['username']),$issue,$message))){
			return true;
		}
		return false;
	}
	function personal_message($toId,$message){
		if(query("INSERT INTO `bugs`.`messages` (`id`,`timestamp`,`from_id`,`to_id`,`p_id`,`s_id`,`i_id`,`message`) VALUES(NULL,CURRENT_TIMESTAMP,'%d','%d',NULL,NULL,NULL,'%s');",array(userId($_SESSION['username']),$toId,$message))){
			return true;
		}
		return false;
	}
?>