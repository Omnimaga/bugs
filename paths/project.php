<?php
	Bugs::actions(
		'project_create',
		'project_update',
		'project_delete'
	);
	Router::paths(array(
		'/project/{project}'=>function($res,$args){
			$res->write(
				Bugs::template('project')
					->run(Bugs::project(is_numeric($args->project)?intval($args->project):$args->project)
				)
			);
		},
		'/project/{project}/issue/{issue}'=>function($res,$args){
			Router::redirect(Router::url(Router::$base."/!{$args->issue}"));
		},
		'/project/{project}/!{issue}'=>function($res,$args){
			Router::redirect(Router::url(Router::$base."!{$args->issue}"));
		},
		'/project/{project}/update'=>function($res,$args){
			if(!empty($_POST['name'])&&!empty($_POST['description'])){
				$project = Bugs::project(is_numeric($args->project)?intval($args->project):$args->project);
				$project->name = $_POST['name'];
				$project->description = $_POST['description'];
				$res->json(array(
					'name'=>$project->name
				));
			}else{
				$res->json(array(
					'error'=>'You must specify a name and description.'
				));
			}
		},
		'/project/{project}/create/issue'=>function($res,$args){
			$res->write(Bugs::template('issue'));
		},
		'/project/{project}/create/issue/complete'=>function($res,$args){
			if(!empty($_POST['name'])&&!empty($_POST['description'])){
				$issue = Bugs::project(is_numeric($args->project)?intval($args->project):$args->project)
					->issue($_POST['name'],$_POST['description']);
				$res->json(array(
					'id'=>$issue->id
				));
			}else{
				$res->json(array(
					'error'=>'You must specify a name and description.'
				));
			}
		},
		'/create/project'=>function($res,$args){
			$res->write(Bugs::template('project'));
		},
		'/create/project/complete'=>function($res,$args){
			if(!empty($_POST['name'])){
				if(!Bugs::project_id($_POST['name'])){
					$project = Bugs::project($_POST['name'],$_POST['description']);
					$res->json(array(
						'name'=>$project->name
					));
				}else{
					$res->json(array(
						'error'=>"A project with the name {$_POST['name']} already exists."
					));
				}
			}else{
				$res->json(array(
					'error'=>'No name specified'
				));
			}
		}
	));
?>