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
class A_Db_Sqlite extends A_Db_Adapter
{
	protected $mode;
	protected $_sequence_ext = '_seq';
	protected $_sequence_start = 1;
	protected $_recordset_class = 'A_Db_Recordset_Sqlite';
	protected $_result_class = 'A_Db_Result';
	
	public function __construct($config=null)
	{
		if (is_string($config)) {
			$config = array('filename' => $config);
		}
		$this->mode = 0666;
		$this->config($config);
		if ($this->_config && isset($this->_config['autoconnect'])) {
			$this->connect();
		}
	}
	
	protected function _connect()
	{
		if (!isset($this->_config['mode'])) {
			$this->_config['mode'] = $this->mode;
		}
		if (!isset($this->_config['encryption_key'])) {
			$this->_config['encryption_key'] = null;
		}
		$this->_connection = sqlite_open($this->_config['filename'], $this->_config['mode'], $errormsg);
		if ($errormsg) {
			$this->_errorHandler(2, $errormsg);
		}
	}
	
	protected function _close()
	{
		sqlite_close($this->_connection);
	}
	
	protected function _query($sql)
	{
		$result = sqlite_query($this->_connection, $sql);
		$this->_sql[] = $sql;			// save history
		$error = sqlite_last_error($this->_connection);
		$errmsg = sqlite_error_string($error);
		// sqlite returns 'not an error' which we convert to ''
		$this->_errorHandler($error, $errmsg != 'not an error' ? $errmsg : '');
		if (in_array(strtoupper(substr($sql, 0, 5)), array('SELEC','SHOW ','DESCR'))) {
			$this->_numRows = -1;	// $result->num_rows($result);
			$obj = new $this->_recordset_class($this->_numRows, $this->_error, $this->_errorMsg);
			// call RecordSet specific setters
			$obj->setResult($result);
		} else {
			$this->_numRows = sqlite_num_rows($result);
			$obj = new $this->_result_class($this->_numRows, $this->_error, $this->_errorMsg);
		}
		return $obj;
	}
	
	public function limit($sql, $count, $offset=null)
	{
		return "$sql LIMIT " . ($offset > 0 ? "$offset, $count" : $count);
	}
	
	protected function _lastId()
	{
		return sqlite_last_insert_row_id($this->_connection);
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
		return sqlite_escape_string($value);
	}
	
	protected function _selectDb($database)
	{
		// Should this do a close() and then connect($database) (changing the config ?
		$this->_errorHandler(0, 'Selecting a different database is not supported by SQLite');
	}

}
