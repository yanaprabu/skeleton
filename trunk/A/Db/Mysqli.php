<?php
/**
 * Mysqli.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Db_Mysqli
 * 
 * Database connection class using the mysqli library.  Configuration array can contain the following indices: type, hostspec, username, password, database.
 */
class A_Db_Mysqli extends A_Db_Adapter
{
	protected $_sequence_ext = '_seq';
	protected $_sequence_start = 1;
	protected $_recordset_class = 'A_Db_Recordset_Mysqli';
	protected $_result_class = 'A_Db_Result';
	protected $mysqli = null;
	
	public function _connect($dsn=null) {
		$result = false;
		if ($dsn) {
			$this->dsn = $dsn;
		}
		$this->mysqli = new MySQLi($this->dsn['hostspec'], $this->dsn['username'], $this->dsn['password']);
		if (isset($this->dsn['database'])) {
			$result = $this->mysqli->select_db($this->dsn['database'], $this->link);
		} else {
			$result = true;
		}
		return $this->mysqli;
	}
		
	public function selectDb($database='') {
		$link = $this->connectBySql('SELECT');
		if ($link) {
			if (! $database) {
				$database = $this->dsn['database'];
			}
			$result = $this->mysqli->select_db($this->dsn['database']);
			$this->_errorMsg = $this->mysqli->error;
			$this->_error = $this->_errorMsg != '';
		}
	}
		
	protected function _close($name='') {
		if (isset($this->_connection[$name])) {
			$this->_connection[$name]->close();
		}
	}
	
	public function query($sql, $bind=array()) {
		if (is_object($sql)) {
			// convert object to string by executing SQL builder object
			$sql = $sql->render($this);   // pass $this to provide db specific escape() method
		}
		if ($bind) {
			$prepare = new A_Sql_Prepare($sql, $bind);
			$prepare->setDb($this);
			$sql = $prepare->render();
		}
		$link = $this->connectBySql($sql);
		if ($link) {
			$result = $link->query($sql, $link);
			$this->_sql[] = $sql;			// save history
			$this->_errorMsg = $this->mysqli->error;
			$this->_error = $this->_errorMsg != '';
			$this->_numRows = $link->affected_rows;
			if (in_array(strtoupper(substr($sql, 0, 5)), array('SELEC','SHOW ','DESCR'))) {
				$obj = new $this->_recordset_class($this->_numRows, $this->_error, $this->_errorMsg);
				// call RecordSet specific setters
				$obj->setResult($result);
			} else {
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
		if (isset($this->_connection[$name])) {
			return $this->_connection[$name]->insert_id();
		}
	}
		
	public function nextId ($sequence) {
		$link = $this->connectBySql('UPDATE');
		if ($link && $sequence) {
			$result = $link->query("UPDATE $sequence{$this->sequenceext} SET id=LAST_INSERT_ID(id+1)");
			if ($result) {
				$id = $link->insert_id();
				if ($id > 0) {
					return $id;
				} else {
					$result = $link->query("INSERT $sequence{$this->sequenceext} SET id=1");
					$id = $link->insert_id();
					if ($id > 0) {
						return $id;
					}
				}
			} elseif ($this->_error() == 1146) {		// table does not exist
				if ($this->createSequence($sequence)) {
					return $this->sequencestart;
				}
			}
		}
		return 0;
	}
		
	public function createSequence ($sequence) {
		$link = $this->connectBySql('UPDATE');
		$result = 0;
		if ($sequence) {
			$result = $link->query($this->link, "CREATE TABLE $sequence{$this->sequenceext} (id int(10) unsigned NOT NULL auto_increment, PRIMARY KEY(id)) TYPE=MyISAM AUTO_INCREMENT={$this->sequencestart}");
		}
		return($result);
	}
		
	public function escape($value, $name='') {
		if (isset($this->_connection[$name])) {
			return $this->_connection[$name]->escape_string($value);
		}
	}
	
	/**
	 * __call
	 * 
	 * Magic function __call, redirects to instance of MySQLi
	 * 
	 * @param string $function Function to call
	 * @param array $args Arguments to pass to $function
	 */
	function __call($function, $args)
	{
		return call_user_func_array(array($this->mysqli, $function), $args);
	}
}


class A_Db_Mysqli_Recordset extends A_Db_Mysqli_Result {

public function __construct($result=null) {
	$this->result = $result;
}
	
public function fetchRow ($class=null) {
	if ($this->result) {
		return $this->result->fetch_assoc($this->result);
	}
}
	
public function numRows() {
	if ($this->result) {
		return $this->result->num_rows;
	} else {
		return 0;
	}
}
	
public function numCols() {
	if ($this->result) {
		return $this->result->field_count;
	} else {
		return 0;
	}
}
	
public function __call($name, $args) {
	return call_user_func(array($this->result, $name), $args);
}
	
}
