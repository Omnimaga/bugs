<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'security.php');
	require_once(PATH_PHP.'user.php');
	function sendMail($template,$subject,$to=null,$from=null,$context=array()){
		if(is_null($to)){
			$to = get('email');
		}
		if(is_null($from)){
			$from = get('email');
		}
		if(file_exists(PATH_DATA."/emails/{$template}.template")){
			$message = vnsprintf(file_get_contents(PATH_DATA."/emails/{$template}.template"),$context);
			return @mail($to,$subject,$message,"From: {$from}\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\n");
		}else{
			return false;
		}
	}
	function sendMailAll($template,$subject,$context=array()){
		if($res = query("SELECT id FROM `users`")){
			while($user = $res->fetch_assoc()){
				$userobj = userObj((int)$user['id']);
				foreach($userobj as $k => $val){
					$context['user'.$k] = $val;
				}
				query("INSERT INTO `emails` (to_id,subject,template,context) VALUES (%d,'%s','%s','%s')",array($user['id'],$subject,$template,json_encode($context)));
			}
		}
	}
	function sendFromQueue($limit = 5){
		if($res = query("SELECT e.id,u.email,e.subject,e.template,e.context FROM `emails` e JOIN `users` u ON u.id = e.to_id LIMIT %d",array($limit))){
			while($email = $res->fetch_assoc()){
				if(sendMail($email['template'],$email['subject'],$email['email'],get('email'),json_decode($email['context'],true))){
					query("DELETE FROM `emails` WHERE id = %d",array($email['id']));
				}else{
					query("UPDATE `emails` SET failed=1 WHERE id = %d",array($email['id']));
				}
			}
		}
	}
?>