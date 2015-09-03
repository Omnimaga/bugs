<?php
	Router::paths(array(
		'/!{issue}'=>function($res,$args){
			$res->write(
				Bugs::template('issue')
					->run(Bugs::issue($args->issue))
			);
		},
		'/issue/{issue}'=>function($res,$args){
			Router::redirect(Router::url(Router::$base.'/!'.$args->issue));
		},
		'/issue/{issue}/update'=>function($res,$args){
			error_handle_type('json');
			if(!empty($_POST['name'])&&!empty($_POST['description'])){
				$issue = Bugs::issue($args->issue);
				$issue->name = $_POST['name'];
				$issue->description = $_POST['description'];
				$res->json(array(
					'id'=>$issue->id
				));
			}else{
				$res->json(array(
					'error'=>'You must specify a name and description.'
				));
			}
		},
		'/create/issue'=>function($res,$args){
			$res->write(Bugs::template('issue'));
		},
		'/create/issue/complete'=>function($res,$args){
			error_handle_type('json');
			if(!empty($_POST['name'])&&!empty($_POST['description'])){
				$issue = Bugs::issue($_POST['name'],$_POST['description']);
				$res->json(array(
					'id'=>$issue->id
				));
			}else{
				$res->json(array(
					'error'=>'You must specify a name and description.'
				));
			}
		}
	));
?>