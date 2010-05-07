<?php
/**
 * Database connectin class using the mysql_ library
 *
 * @package A_Db
 * 
 * config array contain:
 * 'hostspec'
 * 'username'
 * 'password'
 * 'database'
 */
class A_Db_MySQL extends A_Db_Abstract {
	protected $_sequence_ext = '_seq';
	protected $_sequence_start = 1;
	protected $_recordset_class = 'A_Db_MySQL_Recordset';
	protected $_result_class = 'A_Db_MySQL_Result';
	
	protected function _connect ($config) {
		$result = false;
		$host = isset($config['host']) ? $config['host'] : $config['hostspec'];
		// fix for problem connecting to server with localhost. Windows only?
		if (($host == 'localhost') && version_compare(PHP_VERSION, '5.3.0', '>=')) {
			$host = '127.0.0.1';
		}
		if (isset($config['persistent'])) {
			$link = mysql_pconnect($host, $config['username'], $config['password']);
		} else {
			$link = mysql_connect($host, $config['username'], $config['password']);
		}
		if ($link && isset($config['database'])) {
			mysql_select_db($config['database'], $link);
		}
		$this->error = mysql_errno($link);
		$this->errorMsg = mysql_error($link);
		return $link;
	}
		
	public function selectDb($database='') {
		$link = $this->connectBySql('SELECT');
		if ($link) {
			if (! $database) {
				$database = $this->dsn['database'];
			}
			$result = mysql_select_db($database, $link);
			$this->error = mysql_errno($link);
			$this->errorMsg = mysql_error($link);
		}
	}
		
	protected function _close($name='') {
		if (isset($this->_connection[$name])) {
			mysql_close($this->_connection[$name]);
		}
	}
	
	public function query($sql, $bind=array()) {
		if (is_object($sql)) {
			// convert object to string by executing SQL builder object
			$sql = $sql->render($this);   // pass $this to provide db specific escape() method
		}
		if ($bind) {
			#include_once 'A/Sql/Prepare.php';
			$prepare = new A_Sql_Prepare($sql, $bind);
			$prepare->setDb($this);
			$sql = $prepare->render();
		}
		$link = $this->connectBySql($sql);
		if ($link) {
			$result = mysql_query($sql, $link);
			$this->_sql[] = $sql;			// save history
			$this->error = mysql_errno($link);
			$this->errorMsg = mysql_error($link);
			if (in_array(strtoupper(substr($sql, 0, 5)), array('SELEC','SHOW ','DESCR'))) {
				$obj = new $this->_recordset_class($result, $link, $this->error, $this->errorMsg);
			} else {
				$obj = new $this->_result_class($result, $link, $this->error, $this->errorMsg);
			}
			return $obj;
		} else {
			$this->_errorHandler(0, 'No connection. ');
		}
	}
		
	public function limit($sql, $count, $offset='') {
		if ($offset) {
			$count = "$count OFFSET $offset";
		} 
		return "$sql LIMIT $count";
	}
		
	public function lastId() {
		$link = $this->connectBySql('INSERT');
		if ($link) {
			return(mysql_insert_id($link));
		} else {
			return 0;
		}
	}
		
	public function nextId ($sequence) {
		if ($sequence) {
			$link = $this->connectBySql('UPDATE');
			$result = $this->query("UPDATE $sequence{$this->_sequence_ext} SET id=LAST_INSERT_ID(id+1)", $link);
			if ($result) {
				$id = $this->lastId($link);
				if ($id > 0) {
					return $id;
				} else {
					$result = $this->query("INSERT $sequence{$this->_sequence_ext} SET id=1", $link);
					$id = $this->lastId($link);
					if ($id > 0) {
						return $id;
					}
				}
			} elseif ($this->isError() == 1146) {		// table does not exist
				if ($this->createSequence($sequence)) {
					return $this->_sequence_start;
				}
			}
		}
		return 0;
	}
		
	public function createSequence ($sequence) {
		$result = 0;
		if ($sequence) {
			$result = $this->query("CREATE TABLE $sequence{$this->_sequence_ext} (id int(10) unsigned NOT NULL auto_increment, PRIMARY KEY(id)) TYPE=MyISAM AUTO_INCREMENT={$this->_sequence_start}", $link);
		}
		return($result);
	}
		
	public function escape($value) {
		$link = $this->connectBySql('SELECT');
		return mysql_real_escape_string($value, $link);
	}

	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
}
	
	
class A_Db_MySQL_Result {
	protected $result;
	protected $link;
	protected $error;
	protected $errorMsg;
	
	public function __construct($result, $link, $error, $errorMsg) {
		$this->result = $result;
		$this->link = $link;
		$this->error = $error;
		$this->errorMsg = $errorMsg;
	}
		
	public function numRows() {
		if ($this->link) {
			return(mysql_affected_rows($this->link));
		} else {
			return 0;
		}
	}
		
	public function isError() {
		return $this->error;
	}
		
	public function getErrorMsg() {
		return $this->errorMsg;
	}
	
	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
}
	
	
class A_Db_MySQL_Recordset extends A_Db_MySQL_Result {
	
	public function fetchRow ($mode=null) {
		if ($this->result) {
			return mysql_fetch_assoc($this->result);
		}
	}
		
	public function fetchObject ($class=null) {
		if ($this->result) {
			return mysql_fetch_object($this->result, $class);
		}
	}
		
	public function fetchAll ($class=null) {
		$rows = array();
		if ($this->result) {
			while ($row = mysql_fetch_assoc($this->result)) {
				$rows[] = $row;
			}
		}
		return $rows;
	}
		
	public function numRows() {
		if ($this->result) {
			return mysql_num_rows($this->result);
		} else {
			return 0;
		}
	}
		
	public function numCols() {
		if ($this->result) {
			return mysql_num_cols($this->result);
		} else {
			return 0;
		}
	}
	
}
