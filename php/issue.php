<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	require_once(PATH_PHP.'messages.php');
	require_once(PATH_PHP.'activity.php');
	function issueObj($id){
		if($res = query("SELECT i.id,i.title,i.description,i.s_id,u.name as user, p.name as priority, s.name as status,p.color FROM `issues` i JOIN `users` u ON u.id = i.u_id LEFT JOIN `priorities` p ON p.id = i.pr_id LEFT JOIN `statuses` s ON s.id = i.st_id WHERE i.id='%d'",array($id))){
			if($res->num_rows == 1){
				$issue = $res->fetch_assoc();
				$issue['user'] = userObj($issue['user']);
				$issue['comments'] = messages($issue['id'],'issue');
				return $issue;
			}
		}
		return false;
	}
	function newIssue($title,$description,$user=null,$sid=null){
		global $LOGGEDIN;
		if($LOGGEDIN){
			if(is_null($user)){
				$user = $_SESSION['username'];
			}
			$user = userId($user);
			if(false != $user){
				if($res = query("SELECT id FROM `issues` WHERE title = '%s' AND u_id = %d",array($title,$user,$sid))){
					if($res->num_rows){
						return false;
					}
				}
				if(is_null($sid)){
					$sid = 'null';
				}else{
					$sid = intval($sid);
				}
				if(query("INSERT INTO `issues` (title,description,u_id,s_id,st_id) VALUES ('%s','%s',%d,%s,1)",array($title,$description,$user,$sid))){
					$id = mysqli_insert_id(get_sql());
					project_comment($id,'Issue created');
					alog('i',$id,"Issue created");
					return true;
				}
			}
		}
		return false;
	}
?>