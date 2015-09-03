<?php
	if(isset($_POST['db'])||isset($_POST['user'])||isset($_POST['password'])||isset($_POST['server'])||isset($_POST['email'])){
		if(!empty($_POST['db'])&&!empty($_POST['user'])&&!empty($_POST['password'])&&!empty($_POST['server'])){
			require_once('../lib/sql.class.php');
			function run_scripts($folder){
				static $pdo;
				if(!$pdo){
					$pdo = new PDO("mysql:dbname={$_POST['db']};host={$_POST['server']}",$_POST['user'],$_POST['password']) or trigger_error('Failed to connect to the database');
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}
				$steps = array(
					basename($folder)=>array()
				);
				foreach(scandir($folder) as $file){
					if(!in_array($file,array('.','..'))){
						$path = "{$folder}/{$file}";
						if(is_file($path) && substr($file,-3) == 'sql'){
							$source = file_get_contents($path);
							try{
								$stmt = $pdo->prepare($source);
								$stmt->execute();
								do{null;}while($stmt->nextRowset());
								$info = $stmt?$stmt->errorInfo()[2]:$pdo->errorInfo()[2];
								$pass = $stmt !== false;
							}catch(PDOException $e){
								$info = $e->getMessage();
								$pass = false;
							}
							$steps[basename($folder)][basename($file,'.sql')] = array($pass,$info);
						}elseif(is_dir($path)){
							$steps = array_merge($steps,run_scripts($path));
						}
					}
				}
				return $steps;
			}
			header('Content-type: application/json');
			if(empty($_POST['uninstall'])){
				$res = run_scripts('db_install');
				if(!empty($_POST['email'])){
					require_once('../lib/bugs.class.php');
					Bugs::connect($_POST['server'],$_POST['user'],$_POST['password'],$_POST['db']);
					if(Bugs::$sql->query("SELECT COUNT(*) count FROM users")->assoc_result['count'] === 0){
						$user = Bugs::user($_POST['user'],$_POST['email'],$_POST['password']);
						$pass = true;
						$info = '';
						if($user){
							$user->active = true;
							Bugs::$sql->query("
								INSERT INTO r_permission_user (per_id,u_id)
								VALUES (1,?)
							",'i',$user->id)->execute();
							if(!Bugs::login($user,$_POST['password'])){
								$pass = false;
								$info = 'Failed to automatically log in.';
							}
						}else{
							$pass = false;
							$info = 'Could not create user.';
						}
						$res['99_User'] = array(
							'Created default user and logged you in'=>array(
								$pass,
								$info
							)
						);
					}
				}
			}else{
				$res = run_scripts('db_uninstall');
			}
			die(json_encode($res));
		}else{
			header('Content-type: application/json');
			die(json_encode(false));
		}
	}
?>
<!doctype html>
	<head>
		<meta charset="utf8"/>
		<title>Install</title>
		<script src="../js/juju/core.js"></script>
		<script src="../js/juju/dom.js"></script>
		<script src="../js/juju/canvas.js"></script>
		<script src="../js/juju/fetch.js"></script>
		<script src="install.js"></script>
		<link rel="stylesheet" href="../css/main.css"></link>
	</head>
	<body>
		<ul class="hidden" id="log"></ul>
		<form class="hidden" method="POST">
			<div>
				<label for="server">
					Server:
				</label>
				<input name="server"/>
			</div>
			<div>
				<label for="db">
					DB:
				</label>
				<input name="db"/>
			</div>
			<div>
				<label for="user">
					User:
				</label>
				<input name="user"/>
			</div>
			<div>
				<label for="email">
					Email:
				</label>
				<input type="email" name="email"/>
			</div>
			<div>
				<label for="password">
					Password:
				</label>
				<input type="password" name="password"/>
			</div>
			<div>
				<input type="submit" value="Install"/>
				<button id="uninstall" value="Uninstall">Uninstall</button>
				<input type="hidden" name="uninstall" value=""/>
			</div>
		</form>
	</body>
</html>