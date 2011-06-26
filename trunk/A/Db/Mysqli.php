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
	
	public function connect()
	{
		if ($this->_config && ! $this->_connection) {
			$this->_connection = new Mysqli($this->_config['host'], $this->_config['username'], $this->_config['password']);
			$this->_errorHandler($this->_connection->errno, $this->_connection->error);
			if (isset($this->_config['database'])) {
				$result = $this->_connection->select_db($this->_config['database']);
				$this->_errorHandler($this->_connection->errno, $this->_connection->error);
			}
		} else {
			$this->_errorHandler(1, "No config data. ");
		}
		return $this;
	}
	
	public function selectDb($database='')
	{
		if ($this->_connection) {
			if (!$database) {
				$database = $this->_config['database'];
			}
			$result = $this->_connection->select_db($this->_config['database']);
			$this->_errorHandler($this->_connection->errno, $this->_connection->error);
		}
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
			$this->_errorHandler($this->_connection->errno, $this->_connection->error);
			$this->_numRows = $this->_connection->affected_rows;
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
	
	public function limit($sql, $count, $offset='')
	{
		if ($offset) {
			$count = "$count OFFSET $offset";
		} 
		return "$sql LIMIT $count";
	}
	
	public function lastId()
	{
		if (isset($this->_connection[$name])) {
			return $this->_connection[$name]->insert_id();
		}
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
	
	public function escape($value)
	{
		if (isset($this->_connection)) {
			return $this->_connection->escape_string($value);
		}
	}
	
	/**
	 * __call
	 * 
	 * Magic function __call, redirects to instance of Mysqli_Result
	 * 
	 * @param string $function Property to access
	 */
	public function __get($name) {
		return $this->_connection->$name;
	}

	/**
	 * __call
	 * 
	 * Magic function __call, redirects to instance of Mysqli
	 * 
	 * @param string $function Function to call
	 * @param array $args Arguments to pass to $function
	 */
	function __call($function, $args)
	{
		return call_user_func_array(array($this->_connection, $function), $args);
	}
}
