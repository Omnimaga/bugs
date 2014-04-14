<?php
	require_once(realpath(dirname(__FILE__)).'/config.php');
	require_once(PATH_PHP.'database.php');
	function alog($type,$id,$action){
		$aid = create_action($action);
		if(query("INSERT INTO `activity` (%s_id,a_id) VALUES (%d,%d)",array($type,$id,$aid))){
			return true;
		}
		return false;
	}
	function create_action($action){
		if($aid = get_action_id($action)){
			return $aid;
		}elseif(query("INSERT INTO `actions` (name) VALUES ('%s')",array($action))){
			return mysqli_insert_id(get_sql());
		}
		return false;
	}
	function get_action_id($action){
		if($res = query("SELECT a.id,a.name FROM `actions` AS a WHERE a.name = '%s'",array($action))){
			if($res->num_rows == 1){
				$action = $res->fetch_assoc();
				return $action['id'];
			}
		}
		return false;
	}
	function activityObj($id){
		if($res = query("SELECT UNIX_TIMESTAMP(a.date) as timestamp,
								a.id,
								ac.name AS action,
								CASE
									WHEN a.u_id IS NOT NULL THEN 'u'
									WHEN a.p_id IS NOT NULL THEN 'p'
									WHEN a.i_id IS NOT NULL THEN 'i'
									WHEN a.m_id IS NOT NULL THEN 'm'
									ELSE 'other'
								END AS type,
								a.u_id,
								a.p_id,
								a.i_id,
								a.m_id
			FROM `activity` AS a
			JOIN `actions` AS ac ON ac.id = a.a_id
			WHERE a.id = %d
		",array($id))){
			if($res->num_rows == 1){
				$activity = $res->fetch_assoc();
				$ret = array(
					'id'=>$activity['id'],
					'timestamp'=>$activity['timestamp'],
					'action'=>$activity['action'],
					'type'=>$activity['type']
				);
				switch($activity['type']){
					case 'u':
						$ret['rel'] = userObj($activity['u_id']);
						$ret['url'] = '#~'.$ret['rel']['name'];
					break;
					case 'p':
						$ret['rel'] = projectObj($activity['p_id']);
						$ret['url'] = '#project-'.$ret['rel']['id'];
						$ret['rel']['title'] = 'Project - '.$ret['rel']['title'];
					break;
					case 'i':
						$ret['rel'] = issueObj($activity['i_id']);
						$ret['url'] = '#!'.$ret['rel']['id'];
						$ret['rel']['title'] = 'Issue - '.$ret['rel']['title'];
					break;
					case 'm':
						$ret['rel'] = messageObj($activity['i_id']);
						$ret['url'] = '#message-'.$ret['rel']['id'];
					break;
					default:
						$ret['rel'] = array();
				}
				return $ret;
			}
		}
		return false;
	}
?>