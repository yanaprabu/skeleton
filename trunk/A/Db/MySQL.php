<?php
/**
 * Database connection class using the mysql_ library
 *
 * @package A_Db
 * 
 * config array contain:
 * 'hostspec'
 * 'username'
 * 'password'
 * 'database'
 */
class A_Db_MySQL extends A_Db_Base {
	protected $_sequence_ext = '_seq';
	protected $_sequence_start = 1;
	protected $_recordset_class = 'A_Db_Recordset_MySQL';
	protected $_result_class = 'A_Db_Result';
	
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
		$this->_error = mysql_errno($link);
		$this->_errorMsg = mysql_error($link);
		return $link;
	}
		
	public function selectDb($database='') {
		$link = $this->connectBySql('SELECT');
		if ($link) {
			if (! $database) {
				$database = $this->dsn['database'];
			}
			$result = mysql_select_db($database, $link);
			$this->_error = mysql_errno($link);
			$this->_errorMsg = mysql_error($link);
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
			$this->_error = mysql_errno($link);
			$this->_errorMsg = mysql_error($link);
			if (in_array(strtoupper(substr($sql, 0, 5)), array('SELEC','SHOW ','DESCR'))) {
				$this->_numRows = mysql_num_rows($result);
				$obj = new $this->_recordset_class($this->_numRows, $this->_error, $this->_errorMsg);
				// call RecordSet specific setters
				$obj->setResult($result);
			} else {
				$this->_numRows = mysql_affected_rows($link);
				$obj = new $this->_result_class($this->_numRows, $this->_error, $this->_errorMsg);
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
