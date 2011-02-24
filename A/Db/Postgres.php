<?php
/**
 * Database connection using the postgres library
 *
 * @package A_Db
 * 
 * DSN array contain:
 * 'host' or 'hostspec'
 * 'user' or 'username'
 * 'password'
 * 'dbname' or 'database'
 * 'port'
 *  */

class A_Db_Postgres extends A_Db_Base {
	protected $config = null;
	protected $link = null;
	protected $sequenceext = '_seq';
	protected $sequencestart = 1;
	
	public function __construct($config=null) {
		$this->config = $config;
	}
		
	public function connect ($config=null) {
		$result = false;
		if ($config) {
			$this->config = $config;
		}
		if ($this->link == null) {
			$connstr = '';
			foreach ($this->config as $param => $value) {
				if ($value) {
					switch ($param) {
					case 'hostspec':
					case 'host':
						$connstr .= "host={$value} ";
						break;
					case 'dbname':
					case 'database':
						$connstr .= "dbname={$value} ";
						break;
					case 'port':
						$connstr .= "port={$value} ";
						break;
					case 'username':
					case 'user':
						$connstr .= "user={$value} ";
						break;
					case 'password':
						$connstr .= "password={$value} ";
						break;
					}
				}
			}
			if (isset($this->config['persistent'])) {
				$this->link = pg_pconnect($connstr);
			} else {
				$this->link = pg_connect($connstr);
			}
		}
		return $this->link;
	}
		
	public function close() {
		if ($this->link) {
			pg_disconnect($this->link);
		} 
	}
		
	public function disconnect() {
		$this->close();
	}
		
	public function query($sql, $bind=array()) {
		if (is_object($sql)) {
			// convert object to string by executing SQL builder object
			$sql = $sql->render($this);   // pass $this to provide db specific escape() method
		}
		if ($bind) {
			#include_once 'A/Sql/Prepare.php';
			$prepare = new A_Sql_Prepare($sql, $bind);
			$prepare->setDb($this->db);
			$sql = $prepare->render();
		}
		if (strpos(strtolower($sql), 'select') === 0) {
			$obj = new A_Db_Postgres_Recordset(pg_query($sql));
		} else {
			$obj = new A_Db_Postgres_Result(pg_query($sql));
		}
		$obj->errorMsg = pg_last_error($this->link);
		$obj->error = $obj->errorMsg != '';
		return $obj;
	}
		
	public function limit($sql, $count, $offset='') {
		if ($offset) {
			$count = "$count OFFSET $offset";
		} 
		return "$sql LIMIT $count";
	}
		
	public function nextId ($sequence) {
	    if ($sequence) {
		    $result = pg_query($this->link, "SELECT nextval('$sequence')");
	    	if ($result) {
		        $row = pg_fetch_array($this->link);
				return $row[0];
			} else {		// table does not exist
				if ($this->createSequence($sequence)) {
					return $this->sequencestart;
				}
			}
	    }
	    return 0;
	}
		
	public function createSequence ($sequence) {
	    $result = 0;
	    if ($sequence) {
		    $result = pg_query($this->link, "CREATE SEQUENCE $sequence START {$this->sequencestart}");
	    }
	    return($result);
	}
		
	public function start() {
		return mysql_query('BEGIN');
	}

	public function savepoint($savepoint='') {
		if ($savepoint) {
			return mysql_query('SAVEPOINT ' . $savepoint);
		}
	}

	public function commit() {
		return mysql_query('COMMIT');
	}

	public function rollback($savepoint='') {
		return mysql_query('ROLLBACK' . ($savepoint ? ' TO SAVEPOINT ' . $savepoint : ''));
	}

	public function escape($value) {
		return pg_escape_string($value);
	}
	
	public function isError() {
		return pg_last_error($this->link) != '';
	}
		
	public function getErrorMsg() {
		return pg_last_error($this->link);
	}
	
	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
}


class A_Db_Postgres_Result {
	protected $result;
	public $error;
	public $errorMsg;
	
	public function __construct($result=null) {
		$this->result = $result;
	}
		
	public function numRows() {
		if ($this->result) {
			return(pg_affected_rows($this->result));
		} else {
			return 0;
		}
	}
		
	public function isError() {
		return $this->_error;
	}
		
	public function getErrorMsg() {
		return $this->_errorMsg;
	}
	
	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
}


class A_Db_Postgres_Recordset extends A_Db_Postgres_Result {

	public function __construct($result=null) {
		$this->result = $result;
	}
		
	public function fetchRow ($mode=null) {
		if ($this->result) {
			return pg_fetch_assoc($this->result);
		}
	}
		
	public function fetchObject ($class=null) {
		if ($this->result) {
			return pg_fetch_object($this->result, null, $class);
		}
	}
		
	public function fetchAll ($mode=null) {
		if ($this->result) {
			return pg_fetch_all($this->result);
		}
	}
		
	public function numRows() {
		if ($this->result) {
			return pg_num_rows($this->result);
		} else {
			return 0;
		}
	}
		
	public function numCols() {
		if ($this->result) {
			return pg_num_cols($this->result);
		} else {
			return 0;
		}
	}
	
}
