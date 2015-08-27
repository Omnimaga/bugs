<?php
	/**
	* SQL class. Used for handling SQL connections
	*
	* @module sql
	* @class SQL
	* @constructor
	*/
	class SQL {
		/**
		* This is the mysqli connection beneath everything
		* 
		* @property sql
		* @type {mysqli}
		* @private
		* @required
		*/
		private $sql;
		public $insert_id;
		public function __construct($server,$user,$pass,$db){
			$this->sql = new mysqli($server,$user,$pass,$db) or die('Unable to connect to mysql');
		}
		public function __invoke(){
			return $this->sql;
		}
		public function __get($name){
			switch($name){
				case 'error':
					return $this->sql->error;
				break;
			}
		}
		/**
		* Returns a Query object based on inputs
		*
		* @method query
		* @param {String} sql The sql expression to run
		* @param {String=null} [types] A string containing all the types of arguments being passed
		* @param {Mixed} [bindings]* The bindings to use in the sql statement
		* @return {Query} Returns the query object
		*/
		public function query(){
			$reflect = new ReflectionClass('Query');
			$args = array_merge(array($this),func_get_args());
			return $reflect->newInstanceArgs($args);
		}
		public function escape($s){
			return $this->sql->escape_string($s);
		}
		public function charset($charset){
			return $this->sql->set_charset($charset);
		}
	}
	/**
	* Query class. Returned by SQL::query()
	*
	* @class Query
	* @constructor
	*/
	class Query {
		private $query;
		private $sql;
		private $parent;
		public function __construct($sql,$source,$types=null){
			$args = func_get_args();
			$args = array_splice($args,2);
			$this->parent = $sql;
			$this->sql = $sql();
			$this->query = $sql()->prepare($source);
			if(!is_null($types)){
				call_user_func_array(array($this->query, 'bind_param'),make_referenced($args)) or trigger_error($sql()->error);
			}
		}
		public function __invoke(){
			return $this->query;
		}
		public function execute(){
			if($this->query){
				$r = $this->query->execute();
				$this->parent->insert_id = $this->sql->insert_id;
				$this->sql->commit();
				return $r;
			}else{
				return false;
			}
		}
		public function __get($name){
			switch($name){
				/**
				* Returns the mysqli::results object for the
				* query
				* 
				* @property results
				* @type {mysqli::results}
				* @public
				*/
				case 'results':
					if($this->query){
						$this->execute();
						$result = $this->query->get_result();
						$this->query->close();
						return $result;
					}else{
						return false;
					}
				break;
				/**
				* Returns an associative array of the query resulsts
				* 
				* @property assoc_results
				* @type {Array}
				* @public
				*/
				/**
				* Returns an associative array of the query resulsts
				* 
				* @property resulsts_assoc
				* @type {Array}
				* @public
				*/
				case 'assoc_results':case 'results_assoc':
					if($this->query){
						$a = array();
						$r = $this->results;
						while($row = $r->fetch_assoc()){
							array_push($a,$row);
						}
						return $a;
					}else{
						return false;
					}
				break;
				/**
				* Returns a numbered array of the query results
				* 
				* @property num_results
				* @type {Array}
				* @public
				*/
				/**
				* Returns a numbered array of the query results
				* 
				* @property resulsts_num
				* @type {Array}
				* @public
				*/
				case 'num_results':case 'results_num':
					if($this->query){
						$a = array();
						$r = $this->results;
						while($row = $r->fetch_num()){
							array_push($a,$row);
						}
						return $a;
					}else{
						return false;
					}
				break;
				case 'assoc_result':case 'result_assoc':
					if($this->query){
						$r = $this->results;
						return $r?$r->fetch_assoc():false;
					}else{
						return false;
					}
				break;
				case 'num_result':case 'result_num':
					if($this->query){
						$r = $this->results;
						return $r?$r->fetch_num():false;
					}else{
						return false;
					}
				break;
				case 'insert_id':
					return $this->parent->insert_id;
				break;
			}
		}
	}
	function make_referenced(&$arr){
		$refs = array();
		foreach($arr as $key => $value){
			$refs[$key] = &$arr[$key];
		}
		return $refs;
	}
?>