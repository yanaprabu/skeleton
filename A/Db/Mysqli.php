<?php
/**
 * Mysqli.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Db_Mysqli
 * 
 * Database connection class using the mysqli library.  Configuration array can contain the following indices: type, hostspec, username, password, database.
 * 
 * @package A_Db
 */
class A_Db_Mysqli extends A_Db_Adapter
{

	protected $_sequence_ext = '_seq';
	protected $_sequence_start = 1;
	protected $_recordset_class = 'A_Db_Recordset_Mysqli';
	protected $_result_class = 'A_Db_Result';
	
	protected function _connect()
	{
		$this->_connection = new Mysqli($this->_config['host'], $this->_config['username'], $this->_config['password']);
		$this->_errorHandler($this->_connection->errno, $this->_connection->error);
		if (isset($this->_config['database'])) {
			$result = $this->_connection->select_db($this->_config['database']);
			$this->_errorHandler($this->_connection->errno, $this->_connection->error);
		}
	}
	
	protected function _query($sql)
	{
		$result = $this->_connection->query($sql);
		$this->_errorHandler($this->_connection->errno, $this->_connection->error);
		if ($result && $this->queryHasResultSet($sql)) {
			$this->_numRows = $result->num_rows;
			$resultObject = $this->createRecordsetObject();
			$resultObject->setResult($result);
		} else {
			$this->_numRows = $this->_connection->affected_rows;
			$resultObject = $this->createResultObject();
		}
		return $resultObject;
	}
	
	protected function _isConnection($connection)
	{
		return is_object($connection) && $connection instanceof MySQLi;
	}
	
	public function limit($sql, $count, $offset='')
	{
		return "$sql LIMIT $count" . ($offset > 0 ? " OFFSET $offset" : '');
	}
	
	public function nextId($sequence)
	{
		if ($this->_connection && $sequence) {
			$result = $this->_connection->query("UPDATE $sequence{$this->sequenceext} SET id=LAST_INSERT_ID(id+1)");
			if ($result) {
				$id = $this->_connection->insert_id();
				if ($id > 0) {
					return $id;
				} else {
					$result = $this->_connection->query("INSERT $sequence{$this->sequenceext} SET id=1");
					$id = $this->_connection->insert_id();
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
	
	public function createSequence($sequence)
	{
		if ($sequence) {
			$result = $this->_connection->query($this->link, "CREATE TABLE $sequence{$this->sequenceext} (id int(10) unsigned NOT NULL auto_increment, PRIMARY KEY(id)) TYPE=MyISAM AUTO_INCREMENT={$this->sequencestart}");
		}
		return $this;
	}
	
	/**
	 * Magic function __get, redirects to instance of Mysqli_Result
	 */
	public function __get($name)
	{
		return $this->_connection->$name;
	}
	
	/**
	 * Magic function __call, redirects to instance of Mysqli
	 * 
	 * @param string $function Function to call
	 * @param array $args Arguments to pass to $function
	 */
	public function __call($function, $args)
	{
		return call_user_func_array(array($this->_connection, $function), $args);
	}
	
	public function escape($value)
	{
		if (isset($this->_connection)) {
			return $this->_connection->escape_string($value);
		}
	}
	
	protected function _lastId()
	{
		return $this->_connection->insert_id();
	}
	
	protected function _selectDb($database)
	{
		$result = $this->_connection->select_db($database);
		if (!$success) {
			$this->_errorHandler($this->_connection->errno, $this->_connection->error);
		}
	}
	
	protected function _close()
	{
		$this->_connection->close();
	}

}
