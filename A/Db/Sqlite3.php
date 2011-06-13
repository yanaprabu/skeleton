<?php
/**
 * Sqlite3.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Db_Sqlite3
 * 
 * Database connection class using SQLite.  Configuration array can contain the following indices: filename, mode.
 */
class A_Db_Sqlite3 extends A_Db_Adapter {	protected $dsn = null;	protected $link = null;	protected $_sequence_ext = '_seq';
	protected $_sequence_start = 1;
	protected $_recordset_class = 'A_Db_Recordset_Sqlite3';
	protected $_result_class = 'A_Db_Result';
	
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
			$prepare = new A_Sql_Prepare($sql, $bind);
			$prepare->setDb($this->db);
			$sql = $prepare->render();
		}
		$result = sqlite3_query($this->link, $sql);
		$this->_sql[] = $sql;			// save history
		$obj->error = sqlite3_last_error($this->link);
		$obj->errorMsg = sqlite3_error_string($obj->error);
		if (in_array(strtoupper(substr($sql, 0, 5)), array('SELEC','SHOW ','DESCR'))) {
			$this->_numRows = sqlite3_num_rows($result);
			$obj = new $this->_recordset_class($this->_numRows, $this->_error, $this->_errorMsg);
			// call RecordSet specific setters
			$obj->setResult($result);
		} else {
			$this->_numRows = sqlite3_affected_rows($link);
			$obj = new $this->_result_class($this->_numRows, $this->_error, $this->_errorMsg);
		}
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
