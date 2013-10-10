<?php
	// MYSQL default bugs:bugs
	ini_set('memory_limit','5120M');
	set_time_limit(0);
	function remove_comments(&$output){
		$lines = explode("\n",$output);
		$output = "";
		// try to keep mem. use down
		$linecount = count($lines);
		$in_comment = false;
		for($i = 0; $i < $linecount; $i++){
			if(preg_match("/^\/\*/",preg_quote($lines[$i]))){
				$in_comment = true;
			}
			if(!$in_comment){
				$output .= $lines[$i] . "\n";
			}
			if(preg_match("/\*\/$/",preg_quote($lines[$i]))){
				$in_comment = false;
			}
		}
		unset($lines);
		return $output;
	}
	function remove_remarks($sql){
		$lines = explode("\n", $sql);
		// try to keep mem. use down
		$sql = "";
		$linecount = count($lines);
		$output = "";
		for ($i = 0; $i < $linecount; $i++){
			if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)){
				if (isset($lines[$i][0]) && $lines[$i][0] != "#"){
					$output .= $lines[$i] . "\n";
				}else{
					$output .= "\n";
				}
				// Trading a bit of speed for lower mem. use here.
				$lines[$i] = "";
			}
		}
		return $output;
	}
	function split_sql_file($sql, $delimiter){
		// Split up our string into "possible" SQL statements.
		$tokens = explode($delimiter, $sql);
		// try to save mem.
		$sql = "";
		$output = array();
		// we don't actually care about the matches preg gives us.
		$matches = array();
		// this is faster than calling count($oktens) every time thru the loop.
		$token_count = count($tokens);
		for ($i = 0; $i < $token_count; $i++){
			// Don't wanna add an empty string as the last thing in the array.
			if(($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))){
				// This is the total number of single quotes in the token.
				$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
				// Counts single quotes that are preceded by an odd number of backslashes,
				// which means they're escaped quotes.
				$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
				$unescaped_quotes = $total_quotes - $escaped_quotes;
				// If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
				if(($unescaped_quotes % 2) == 0){
					// It's a complete sql statement.
					$output[] = $tokens[$i];
					// save memory.
					$tokens[$i] = "";
				}else{
					// incomplete sql statement. keep adding tokens until we have a complete one.
					// $temp will hold what we have so far.
					$temp = $tokens[$i] . $delimiter;
					// save memory..
					$tokens[$i] = "";
					// Do we have a complete statement yet?
					$complete_stmt = false;
					for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++){
						// This is the total number of single quotes in the token.
						$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
						// Counts single quotes that are preceded by an odd number of backslashes,
						// which means they're escaped quotes.
						$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
						$unescaped_quotes = $total_quotes - $escaped_quotes;
						if(($unescaped_quotes % 2) == 1){
							// odd number of unescaped quotes. In combination with the previous incomplete
							// statement(s), we now have a complete statement. (2 odds always make an even)
							$output[] = $temp . $tokens[$j];
							// save memory.
							$tokens[$j] = "";
							$temp = "";
							// exit the loop.
							$complete_stmt = true;
							// make sure the outer loop continues at the right point.
							$i = $j;
						}else{
							// even number of unescaped quotes. We still don't have a complete statement.
							// (1 odd and 1 even always make an odd)
							$temp .= $tokens[$j] . $delimiter;
							// save memory.
							$tokens[$j] = "";
						}
					} // for..
				} // else
			}
		}
		return $output;
	}
	// TODO - Add API handling.
	$method = $_SERVER['REQUEST_METHOD'];
	if(isset($_GET['type'])){
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			switch($_GET['type']){
				case 'install':
					if($id == "run"){
						if(isset($_GET['dbuser'])&&isset($_GET['dbpass'])&&isset($_GET['dbname'])&&isset($_GET['dbhost'])&&isset($_GET['dbtemplate'])){
							$dbuser = $_GET['dbuser'];
							$dbpass = $_GET['dbpass'];
							$dbname = $_GET['dbname'];
							$dbhost = $_GET['dbhost'];
							$dbms_schema = $_GET['dbtemplate'].'.sql';
							$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema)) or die("Can't access template: ".$_GET['dbtemplate'].".sql");
							$sql_query = remove_comments($sql_query);
							$sql_query = remove_remarks($sql_query);
							$sql_query = split_sql_file($sql_query, ';');
							mysql_connect($dbhost,$dbuser,$dbpass) or die("Can't connect to ".$dbhost);
							mysql_select_db($dbname) or die('error database selection');
							foreach($sql_query as $sql){
								mysql_query($sql) or die('error in query');
							}
							file_put_contents('../config.json',"{\"host\":\"{$dbhost}\",\"user\":\"{$dbuser}\",\"password\":\"{$dbpass}\",\"database\":\"{$dbname}\"}");
							echo 'pass';
						}else{
							echo "Please don't leave any fields blank";
						}
					}elseif($id=='config'){
						echo file_get_contents('index.template.html');
					}elseif($id='cleanup'){
						$files = scandir(realpath(dirname(__FILE__)));
						foreach($files as $file){
							if($file != '.' && $file != '..'){
								@unlink(realpath(dirname(__FILE__)).'/'.$file);
							}
						}
						@rmdir(realpath(dirname(__FILE__)));
					}else{
						die('Invalid id');
					}
				break;
				default:
					require_once('../api.php');
			}
		}else{
			die("id missing");
		}
	}else{
		die("type missing");
	}
?>