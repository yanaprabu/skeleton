<?php
/**
 * Database connection class using the SQLite library
 *
 * @package A_Db
 * 
 * DSN array contain:
 * 'filename'
 * 'mode'
 */

class A_Db_Sqlite3 extends A_Db_Abstract {	protected $dsn = null;	protected $link = null;	protected $sequenceext = '_seq';	protected $sequencestart = 1;
	
	public function __construct($dsn=null) {
		if ($dsn && isset($dsn['autoconnect'])) {
			$this->connect($dsn);
		}
	}
		
	public function connect ($dsn=null, $options=null) {
		if ($this->link == null) {
			$this->link = sqlite3_open($dsn['filename'], $dsn['mode']);
		} 
	}
		
	public function disconnect() {
		if ($db->link) {
			sqlite3_close($db->link);
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
			$prepare->setDb($this->db);
			$sql = $prepare->render();
		}
		if (strpos(strtolower($sql), 'select') === 0) {
			$obj = new A_Db_sqlite3_Recordset(sqlite3_query($this->link, $sql));
		} else {
			$obj = new A_Db_sqlite3_Result($this->link, sqlite3_query($this->link, $sql));
		}
		$obj->error = sqlite3_last_error($this->link);
		$obj->errorMsg = sqlite3_error_string($obj->error);
		return $obj;
	}
		
	public function limit($sql, $count, $offset = null) {
		$limit = (is_int($offset) && $offset > 0) ? ($offset . ', ' . $count) : $count; 
		return $sql . ' LIMIT ' . $limit;
	}
	
	public function lastId() {
		if ($this->link) {
			return(sqlite3_last_insert_rowid($this->link));
		} else {
			return 0;
		}
	}
		
	public function nextId ($sequence) {
	    return 0;
	}
		
	public function createSequence ($sequence) {
	    return 0;
	}
		
	public function escape($value) {
		return sqlite3_escape_string($value);
	}
	
	public function isError() {
		return sqlite3_last_error($this->link);
	}
		
	public function getErrorMsg() {
		return sqlite3_error_string(sqlite3_last_error($this->link));
	}
	
	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
}


class A_Db_Sqlite3_Result {	protected $result;	public $error;	public $errorMsg;
	
	public function __construct($result=null) {
		$this->result = $result;
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




class A_Db_Sqlite3_Recordset extends A_Db_Sqlite3_Result {

	public function __construct($result=null) {
		$this->result = $result;
	}
	
	public function fetchRow() {
		if ($this->result) {
			return(sqlite3_fetch_array($this->result, sqlite3_ASSOC));
		}
	}
		
	public function fetchObject ($class=null) {
		if ($this->result) {
			return(sqlite3_fetch_object($this->result, $class));
		}
	}
		
	public function fetchAll() {
		if ($this->result) {
			return(sqlite3_fetch_array($this->result, sqlite3_ASSOC));
		}
	}
		
	public function numRows() {
		if ($this->result) {
			return(sqlite3_num_rows($this->result));
		} else {
			return 0;
		}
	}
		
	public function numCols() {
		if ($this->result) {
			return(sqlite3_num_cols($this->result));
		} else {
			return 0;
		}
	}
	
}
