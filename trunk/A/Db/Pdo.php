<?php
/**
 * Pdo.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/*
    * PDO::__construct — Creates a PDO instance representing a connection to a database
    * PDO::getAttribute — Retrieve a database connection attribute
    * PDO::getAvailableDrivers — Return an array of available PDO drivers
    * PDO::setAttribute — Set an attribute
    * PDO::prepare — Prepares a statement for execution and returns a statement object
    * PDO::exec — Execute an SQL statement and return the number of affected rows
    * PDO::query — Executes an SQL statement, returning a result set as a PDOStatement object
    * PDO::quote — Quotes a string for use in a query.
    * PDO::lastInsertId — Returns the ID of the last inserted row or sequence value
    * PDO::beginTransaction  — Initiates a transaction
    * PDO::commit — Commits a transaction
    * PDO::rollBack — Rolls back a transaction
    * PDO::errorCode — Fetch the SQLSTATE associated with the last operation on the database handle
    * PDO::errorInfo — Fetch extended error information associated with the last operation on the database handle
*/

/**
 * A_Db_Pdo
 * 
 * Adapt PDO to basic database connection functionality.  Configuration array can contain the following indices: type, hostspec, username, password, database.
 */
class A_Db_Pdo extends A_Db_Adapter
{

	protected $_sequence_ext = '_seq';
	protected $_sequence_start = 1;
	protected $_recordset_class = 'A_Db_Recordset_Pdo';
	protected $_result_class = 'A_Db_Result';
	protected $connected = false;
	
	public function __construct($config, $username='', $password='', $attr=array())
	{
		if ($username) {
			$config['username'] = $username;
		}
		if ($password) {
			$config['password'] = $password;
		}
		if ($attr) {
			$config['attr'] = $attr;
		}
		parent::__construct($config);
	}
	
	protected function _connect()
	{
		if (!isset($this->_config['username'])) {
			$this->_errorHandler(1, 'username not set in config');
			return $this;
		}
		if (!isset($this->_config['password'])) {
			$this->_errorHandler(1, 'password not set in config');
			return $this;
		}
		if (!isset($this->_config['phptype'])) {
			$this->_errorHandler(1, 'phptype not set in config');
			return $this;
		}
		if (!isset($this->_config['attr'])) {
			$this->_config['attr'] = array();
		}
		if (isset($this->_config['persistent'])) {
			$this->_config['attr'][PDO::ATTR_PERSISTENT] = $this->_config['persistent'];
		}
		$configString = $this->_config['phptype'] . ":host=" . $this->_config['host'] . ";" . "dbname=" . $this->_config['database'] . (isset($this->_config['port']) ? ";port={$this->_config['port']}" : '');
		
		$this->_connection = new PDO($configString, $this->_config['username'], $this->_config['password'], $this->_config['attr']);
	}
	
	protected function _query($sql)
	{
		$result = $this->_connection->query($sql);
		$this->_sql[] = $sql;			// save history
		$this->_setError();
		$this->_numRows = $result->rowCount();
		if (in_array(strtoupper(substr($sql, 0, 5)), array('SELEC','SHOW ','DESCR'))) {
			$obj = new $this->_recordset_class($this->_numRows, $this->_error, $this->_errorMsg);
			// call RecordSet specific setters
			$obj->setResult($result);
		} else {
			$obj = new $this->_result_class($this->_numRows, $this->_error, $this->_errorMsg);
		}
		return $obj;
	}
	
	public function limit($sql, $count, $offset='')
	{
		return "$sql LIMIT $count" . ($offset > 0 ? " OFFSET $offset" : '');
	}
	
	public function escape($value)
	{
		$this->_connection = $this->connectBySql();
		return trim($this->_connection->quote($value), "\"'");
	}
	
	protected function _setError()
	{
		// get error array
		$errorInfo = $this->_connection->errorInfo();
		$this->_error = ($errorInfo[0] == '00000') ? 0 : $errorInfo[0];		// PDO success value
		if(isset($errorInfo[2])){
			$this->_errorMsg = $errorInfo[2];			
			$this->_errorHandler($this->_error, $this->_errorMsg);
		}
	}
	
	public function beginTransaction()
	{
		return $this->_connection->start();
	}
	
	public function commit()
	{
		return $this->_connection->commit();
	}
	
	public function rollBack($savepoint='')
	{
		return $this->_connection->rollBack();
	}
	
	/**
	 * Magic function __get, redirects to instance of Mysqli_Result
	 * 
	 * @param string $function Property to access
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
	
	public function __sleep()
	{
		return $this->_connection->__sleep();
	}
	
	public function __wakeup()
	{
		return $this->_connection->__wakeup();
	}
	
	protected function _lastId()
	{
		return $this->_connection->lastInsertId();
	}
	
	protected function _close()
	{
		$this->_connection->close();
	}
	
	protected function _selectDb($database)
	{
		$this->query("USE $database");
		return $this;
	}

}
