<?php
/**
 * Adapter.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Db_Adapter
 * 
 * Abstract class for database wrappers.  Meant to be extended, and abstract methods implemented for database-specific behavior.
 */
abstract class A_Db_Adapter
{

	protected $_connection = null;
	protected $_config = array();
	
	/**
	 * convert connnect keys based on this table
	 * 
	 * @var array
	 */
	protected $_config_alias = array(
		'dbname' => 'database', 
		'hostname' => 'host',
		'hostspec' => 'host',
		'user' => 'username'
	);
	protected $_recordset_class;
	protected $_result_class;
	protected $_sql = array();
	protected $_transaction_level = 0;
	protected $_numRows = 0;
	protected $_exception = '';
	protected $_error = 0;
	protected $_errorMsg = '';
	
	/**
	 * Constructor.
	 *
	 * $config is an array of key/value pairs or an instance of A_Collection containing configuration options.  These options are common to most adapters:
	 *
	 * dbname	=> (string) The name of the database to user
	 * username	=> (string) Connect to the database as this username.
	 * password	=> (string) Password associated with the username.
	 * host		=> (string) What host to connect to, defaults to localhost
	 * 
	 * @param  array|A_Config $config
	 * @throws A_Db_Exception
	 */
	public function __construct($config=array())
	{
		if ($config) {
			$this->config($config); 
		}
	}
	
	public function config($config)
	{
		// config element compatablity
		foreach ($this->_config_alias as $alias => $name) {
			if (isset($config[$alias])) {
				$config[$name] = $config[$alias];
			}
		}
		$this->_config = $config;
		if (isset($config['exception'])) {
			$this->setException($config['exception']);				
		}
		return $this;
	}
	
	public function setResultClasses($result_class, $recordset_class)
	{
		if ($result_class) {
			$this->_result_class = $result_class;
		}
		if ($recordset_class) {
			$this->_recordset_class = $recordset_class;
		}
		return $this;
	}
	
	public function getConfig($sql='')
	{
		return $this->_config;
	}
	
	public function setException($class)
	{
		if ($class === true) {
			$this->_exception = 'A_Db_Exception';
		} else {
			$this->_exception = $class;
		}
	}	
	
	public function getSql()
	{
		return $this->_sql;
	}
	
	public function queryHasResultSet($sql)
	{
		if (in_array(strtoupper(substr($sql, 0, 5)), array('SELEC','SHOW ','DESCR','EXPLA'))) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function createResultObject()
	{
		$resultObject = new $this->_result_class($this->_numRows, $this->_error, $this->_errorMsg);
	}
	
	protected function createRecordseObject()
	{
		$resultObject = new $this->_recordset_class($this->_numRows, $this->_error, $this->_errorMsg);
	}
	
	/**
	 * Open connection to database using settings specified in config.
	 * 
	 * @return $this
	 */
	public function connect()
	{
		if ($this->_config && !$this->_connection) {
			$this->_connect();    
		} else {
			$this->_errorHandler(1, "No config data. ");
		}
		return $this;
	}
	
	/**
	 * Supplied by child class - Open connection as specified by $config
	 * 
	 * @return $this
	 */
	abstract protected function _connect();
	
	public function query($sql, $bind=array())
	{
		if (is_object($sql)) {
			$sql = $sql->render($this);
		}
		if ($bind) {
			$prepare = new A_Sql_Prepare($sql, $bind);
			$prepare->setDb($this);
			$sql = $prepare->render();
		}
		if ($this->_connection) {
			$this->_sql[] = $sql;
			return $this->_query($sql);
		} else {
			$this->_errorHandler(3, 'No connection. ');
		}
	}
	
	abstract protected function _query($sql);
	
	/**
	 * Closes connection (if close is supported by extension)
	 * 
	 * @return $this
	 */
	public function close()
	{
		if ($this->_connection) {
			$this->_close();
			$this->_connection = null;
		}
		return $this;
	}
	
	abstract protected function _close();
	
	public function disconnect()
	{
		return $this->close();
	}
	
	/**
	 * Gets the ID of the most recently inserted row
	 * 
	 * @return string|bool Either the row ID, or false if there is no connection or no rows have been inserted.
	 */
	public function lastId()
	{
		if ($this->_connection) {
			return $this->_lastId();
		} else {
			return false;
		}
	}
	
	/**
	 * Adapter-specific method to get the last inserted row ID.
	 * 
	 * @return mixed
	 */
	abstract protected function _lastId();
	
	public function selectDb($database)
	{
		if ($this->_connection) {
			if (!$database) {
				$database = $this->_config['database'];
			}
			$this->_selectDb($database);
		}
		return $this;
	}

	/**
	 * Adapter-specific method to select database if adapter supports it.
	 * 
	 * @return mixed
	 */
	abstract protected function _selectDb($database);
	
	/**
	 * Adds limit syntax to SQL statement
	 * 
	 * @param string $sql
	 * @param int $count
	 * @param int $offset
	 */
	abstract public function limit($sql, $count, $offset='');
	
	public function start()
	{
		if ($this->_transaction_level < 1) {
			$result = $this->query('START');
			$this->_transaction_level = 0;
		} else {
			$result = false;
		}
		$this->_transaction_level++;
		return $result;
	}
	
	public function savepoint($savepoint='')
	{
		if ($savepoint) {
			return $this->query('SAVEPOINT ' . $savepoint);
		}
	}
	
	public function commit()
	{
		$this->_transaction_level--;
		if ($this->_transaction_level == 0) {
			$result = $this->query('COMMIT');
		} else {
			$result = false;
		}
		return $result;
	}
	
	public function rollback($savepoint='')
	{
		$this->_transaction_level--;
		if ($this->_transaction_level == 0) {
			$result = $this->query('ROLLBACK' . ($savepoint ? ' TO SAVEPOINT ' . $savepoint : ''));
		} else {
			$result = false;
		}
		return $result;
	}
	
	public function getTransactionLevel()
	{
		return $this->_transaction_level;
	}
	
	public function escape($value)
	{
		return addslashes($value);
	}
	
	public function isError()
	{
		return $this->_error;
	}
	
	public function getErrorMsg()
	{
		return $this->_errorMsg;
	}
	
	protected function _errorHandler($errno, $errorMsg)
	{
		$this->_error = $errno;
		$this->_errorMsg .= $errorMsg;
		if ($errno && $this->_exception) {
			throw A_Exception::getInstance($this->_exception, $errorMsg);
		}
	}

}
