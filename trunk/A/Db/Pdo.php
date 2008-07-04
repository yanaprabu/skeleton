<?php

class A_Db_Pdo extends PDO {

	protected $dsn = null;
	protected $connected = false;
	protected $sequenceext = '_seq';
	protected $sequencestart = 1;
	
	public function __construct($config) {
		$this->config = $config;
		if (isset($config['dbname']) && isset($config['username']) && isset($config['password'])) {
			$dsn = "mysql:dbname={$config['dbname']}" . (isset($config['hostspec']) ? ";host={$config['hostspec']}" : '');
			parent::__construct($dsn, $config['username'], $config['password']);
		}
		// have query() return A_Db_Pdo_Recordset
		$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('A_Db_Pdo_Recordset', array()));
	}
		
	public function connect($config=null) {
	}
		
	public function close() {
	}
		
	public function selectDb($database) {
		$this->query("USE $database");
	}
		
	/*
	 * public function query() implemented in PDO
	 */
		
	public function limit($sql, $count, $offset = null) {
		$limit = (is_int($offset) && $offset > 0) ? ($offset . ', ' . $count) : $count; 
		return $sql . ' LIMIT ' . $limit;
	}
		
	public function lastId() {
		return $this->lastInsertId();
	}
		
	public function nextId ($sequence) {
	    if ($sequence) {
		    $result = $this->query("UPDATE $sequence{$this->sequenceext} SET id=LAST_INSERT_ID(id+1)");
	    	if ($result) {
		        $id = $this->insert_id();
		        if ($id > 0) {
		            return $id;
		        } else {
				    $result = $this->query("INSERT $sequence{$this->sequenceext} SET id=1");
			        $id = $this->insert_id();
			        if ($id > 0) {
			            return $id;
			        }
		        }
			} elseif ($this->errno() == 1146) {		// table does not exist
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
		    $result = $this->query($this->link, "CREATE TABLE $sequence{$this->sequenceext} (id int(10) unsigned NOT NULL auto_increment, PRIMARY KEY(id)) TYPE=MyISAM AUTO_INCREMENT={$this->sequencestart}");
	    }
	    return($result);
	}
		
	public function start() {
		return $this->query('START');
	}

	public function savepoint($savepoint='') {
		if ($savepoint) {
			return $this->query('SAVEPOINT ' . $savepoint);
		}
	}

	public function commit() {
		return $this->query('COMMIT');
	}

	public function rollback($savepoint='') {
		return $this->query('ROLLBACK' . ($savepoint ? ' TO SAVEPOINT ' . $savepoint : ''));
	}

	public function escape($value) {
		return trim($this->quote($value), "\"'");
	}
	
	public function isError() {
		return $this->errorCode();
	}
		
	public function getMessage() {
		// get error array
		$errorInfo = $this->errorInfo();
		// return the message only
		return $errorInfo[2];
	}
	
} // end DAO class


class A_Db_Pdo_Recordset extends PDOStatement {
	public $errno;
	public $errmsg;
	
	public function isError() {
		return $this->errorCode();
	}
		
	public function getMessage() {
		// get error array
		$errorInfo = $this->errorInfo();
		// return the message only
		return $errorInfo[2];
	}

	public function fetchRow() {
		return $this->fetch(PDO::FETCH_ASSOC);
	}
		
	/*
	 * public function fetchObject() implemented in PDOStatement
	 */
		
	public function numRows() {
		return $this->rowCount();
	}
		
	public function numCols() {
		return $this->columnCount();
	}
	
}
