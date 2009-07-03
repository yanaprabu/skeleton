<?php
/**
 * Adapt PDO to basic database connection functionality
 * 
 * @package A_Db 
 */

class A_Db_Pdo extends PDO {

	protected $dsn = null;
	protected $connected = false;
	protected $sequenceext = '_seq';
	protected $sequencestart = 1;
	
	public function __construct($config, $username='', $password='', $attr=array()) {
		if (is_array($config)) {
			// config element compatablity
			if (isset($config['database'])) {
				$config['dbname'] = $config['database'];
			}
			if (isset($config['hostspec'])) {
				$config['host'] = $config['hostspec'];
			}
			if (! $username && isset($config['username'])) {
				$username = $config['username'];
			}
			if (! $password && isset($config['password'])) {
				$password = $config['password'];
			}
			if (isset($config['persistent'])) {
				$attr[PDO::ATTR_PERSISTENT] = $config['persistent'];
			}
			$dsn = "mysql:host=" . $config['host'] . ";" . "dbname=" . $config['dbname'] . (isset($config['port']) ? ";port={$config['port']}" : '');
		} else {
			$dsn = $config;
		}
		
		$this->config = $config;
		if ($dsn && $username && $password) {
#			$attr[PDO::ATTR_STATEMENT_CLASS] = array('A_Db_Pdo_Recordset', array());
#			$dsn = "mysql:dbname={$config['dbname']}" . (isset($config['host']) ? ";host={$config['host']}" : '');
			parent::__construct($dsn, $config['username'], $config['password'], $attr);
		}
		// have query() return A_Db_Pdo_Recordset
		$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('A_Db_Pdo_Recordset', array()));
	}
		
	public function connect($config=null) {
		return true;
	}
		
	public function close() {
	}
		
	public function selectDb($database) {
		$this->query("USE $database");
	}
		
	/*
	 * public function query() implemented in PDO
	 */
	public function query($sql, $bind=array(), $arg3=null, $arg4=null) {
		if (is_object($sql)) {
			// convert object to string by executing SQL builder object
			$sql = $sql->render($this);   // pass $this to provide db specific escape() method
		}
		if ($bind && is_array($bind)) {
			include_once 'A/Sql/Prepare.php';
			$prepare = new A_Sql_Prepare($sql, $bind);
			$prepare->setDb($this->db);
			$sql = $prepare->render();
			$bind = null;
		}
		return parent::query($sql, $bind, $arg3, $arg4);
	}
	
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
	
	protected function __construct() {
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

	public function fetchRow() {
		return $this->fetch(PDO::FETCH_ASSOC);
	}
		
	/*
	 * public function fetchObject() implemented in PDOStatement
	 */
		
	/*
	 * public function fetchAll() implemented in PDOStatement
	 */
		
	public function numRows() {
		return $this->rowCount();
	}
		
	public function numCols() {
		return $this->columnCount();
	}
	
}
