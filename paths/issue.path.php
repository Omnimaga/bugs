<?php
	Bugs::actions(
		'issue.create',
		'issue.update',
		'issue.delete'
	);
	Router::paths(array(
		'/!{issue}'=>function($res,$args){
			Bugs::authorized('issue.read');
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
			Bugs::authorized('issue.update');
			if(!empty($_POST['name'])&&!empty($_POST['description'])){
				$issue = Bugs::issue($args->issue);
				$issue->name = $_POST['name'];
				$issue->description = $_POST['description'];
				$res->json(array(
					'id'=>$issue->id
				));
				Bugs::activity('issue.update',$issue);
			}else{
				$res->json(array(
					'error'=>'You must specify a name and description.'
				));
			}
		},
		'/create/issue'=>function($res,$args){
			Bugs::authorized('issue.create');
			$res->write(Bugs::template('issue'));
		},
		'/create/issue/complete'=>function($res,$args){
			error_handle_type('json');
			Bugs::authorized('issue.create');
			if(!empty($_POST['name'])&&!empty($_POST['description'])){
				$issue = Bugs::issue($_POST['name'],$_POST['description']);
				$res->json(array(
					'id'=>$issue->id
				));
				Bugs::activity('issue.create',$issue);
			}else{
				$res->json(array(
					'error'=>'You must specify a name and description.'
				));
			}
		}
	));
?>