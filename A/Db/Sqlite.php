<?php
/**
 * Sqlite3.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Db_Sqlite3
 * 
 * Database connection class using SQLite.  Configuration array can contain the following indices: filename, mode.
 * 
 * @package A_Db
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
	
	protected function _query($sql)
	{
		$result = sqlite_query($this->_connection, $sql);
		$error = sqlite_last_error($this->_connection);
		$this->_errorHandler($error, $error != 0 ? sqlite_error_string($error) : '');
		if ($result && $this->queryHasResultSet($sql)) {
			$this->_numRows = sqlite_num_rows($result);
			$resultObject = $this->createRecordsetObject();
			$resultObject->setResult($result);
		} else {
			$this->_numRows = sqlite_changes($this->_connection);
			$resultObject = $this->createResultObject();
		}
		return $resultObject;
	}
	
	protected function _close()
	{
		sqlite_close($this->_connection);
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
