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
		parent::__construct($config);
	}
	
	protected function _connect()
	{
		if (!isset($this->_config['mode'])) {
			$this->_config['mode'] = $this->mode;
		}
		if (!isset($this->_config['encryption_key'])) {
			$this->_config['encryption_key'] = null;
		}
		$this->_connection = new SQLite3($this->_config['filename'], $this->_config['mode'], $this->_config['encryption_key']);
		$errmsg = $this->_connection->lastErrorMsg();
		$this->_errorHandler($this->_connection->lastErrorCode(), $errmsg != 'not an error' ? $errmsg : '');
	}
	
	protected function _query($sql)
	{
		$result = $this->_connection->query($sql);
		$error = $this->_connection->lastErrorCode();
		$this->_errorHandler($error, $error != 0 ? $this->_connection->lastErrorMsg() : '');
		if ($result && $this->queryHasResultSet($sql)) {
			$this->_numRows = -1;
			$resultObject = $this->createRecordsetObject();
			$resultObject->setResult($result);
		} else {
			$this->_numRows = $this->_connection->changes();
			$resultObject = $this->createResultObject();
		}
		return $resultObject;
	}
	
	protected function _close()
	{
		$this->_connection->close();
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
