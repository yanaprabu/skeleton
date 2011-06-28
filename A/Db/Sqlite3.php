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
class A_Db_Sqlite3 extends A_Db_Adapter
{
	protected $mode;
	protected $_sequence_ext = '_seq';
	protected $_sequence_start = 1;
	protected $_recordset_class = 'A_Db_Recordset_Sqlite3';
	protected $_result_class = 'A_Db_Result';
	
	public function __construct($config=null)
	{
		if (is_string($config)) {
			$config['filename'] = $config;
		}
		$this->mode = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE;
		$this->config($config);
		if ($config && isset($config['autoconnect'])) {
			$this->connect();
		}
	}
	
	public function connect ()
	{
		if (isset($this->_config['filename']) && !$this->_connection) {
			if (!isset($this->_config['mode'])) {
				$this->_config['mode'] = $this->mode;
			}
			if (!isset($this->_config['encryption_key'])) {
				$this->_config['encryption_key'] = null;
			}
			$this->_connection = new SQLite3($this->_config['filename'], $this->_config['mode'], $this->_config['encryption_key']);
			$errmsg = $this->_connection->lastErrorMsg();
			$this->_errorHandler($this->_connection->lastErrorCode(), $errmsg != 'not an error' ? $errmsg : '');
		} else {
			$this->_errorHandler(1, 'No filename. ');
		}
		return $this; 
	}
	
	protected function _close()
	{
		$this->_connection->close();
	}
	
	public function query($sql, $bind=array())
	{
		if (is_object($sql)) {
			// convert object to string by executing SQL builder object
			$sql = $sql->render($this);   // pass $this to provide db specific escape() method
		}
		if ($bind) {
			$prepare = new A_Sql_Prepare($sql, $bind);
			$prepare->setDb($this);
			$sql = $prepare->render();
		}
		if ($this->_connection) {
			$result = $this->_connection->query($sql);
			$this->_sql[] = $sql;			// save history
			$errmsg = $this->_connection->lastErrorMsg();
			$this->_errorHandler($this->_connection->lastErrorCode(), $errmsg != 'not an error' ? $errmsg : '');
			if (in_array(strtoupper(substr($sql, 0, 5)), array('SELEC','SHOW ','DESCR'))) {
				$this->_numRows = -1;	// $result->num_rows($result);
				$obj = new $this->_recordset_class($this->_numRows, $this->_error, $this->_errorMsg);
				// call RecordSet specific setters
				$obj->setResult($result);
			} else {
				$this->_numRows = $this->_connection->changes();
				$obj = new $this->_result_class($this->_numRows, $this->_error, $this->_errorMsg);
			}
			return $obj;
		} else {
			$this->_errorHandler(2, 'No connection. ');
		}
		return $obj;
	}
	
	public function limit($sql, $count, $offset=null)
	{
		return "$sql LIMIT " . ($offset > 0 ? "$offset, $count" : $count);
	}
	
	protected function _lastId()
	{
		return $this->_connection->lastInsertRowID();
	}
	
	public function nextId($sequence)
	{
	    return 0;
	}
	
	public function createSequence($sequence)
	{
	    return 0;
	}
	
	public function escape($value)
	{
		return $this->_connection->escapeString($value);
	}
	
	protected function _selectDb($database)
	{
		// Should this do a close() and then connect($database) (changing the config ?
		$this->_errorHandler(0, 'Selecting a different database is not supported by SQLite 3');
	}

}
