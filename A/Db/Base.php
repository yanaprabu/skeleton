<?php
/**
 * Base.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Db_Base
 * 
 * Abstract class for database wrappers.  Meant to be extended, and abstract methods implemented for database-specific behavior.
 */
abstract class A_Db_Base {

	protected $_connection;								// 

	protected $_config;									// 

	protected $_config_class = 'A_Db_Config_Single';	// 

	/**
	 * convert connnect keys based on this table
	 * @var array
	 */
	protected $_config_alias = array(
									'dbname' => 'database', 
									'hostname' => 'host',
									'hostspec' => 'host',
									);

	protected $_recordset_class;
	protected $_result_class;
	
	protected $_sql = array();
	
	protected $_transaction_level = 0;
	
	protected $_numRows = 0;
	
	protected $_exception = '';							// A_Db_Exception

	protected $_error = 0;
	protected $_errorMsg = '';

	/**
	 * Constructor.
	 *
	 * $config is an array of key/value pairs or an instance of A_Collection
	 * containing configuration options.  These options are common to most adapters:
	 *
	 * dbname		 => (string) The name of the database to user
	 * username	   => (string) Connect to the database as this username.
	 * password	   => (string) Password associated with the username.
	 * host		   => (string) What host to connect to, defaults to localhost
	 *
	 * @param  array|A_Config $config
	 * @throws A_Db_Exception
	 */
	public function __construct($config=array()) {
		if ($config) {
			$this->config($config); 
		}
	}

	/**
	 *
	 */
	public function config($config) {
		// config element compatablity
		foreach ($this->_config_alias as $alias => $name) {
			if (isset($config[$alias])) {
				$config[$name] = $config[$alias];
			}
		}
		if (isset($this->_config)) {
			$this->_config->config($config);
		} else {
			if (isset($config['exception'])) {
				$this->setException($config['exception']);				
			}
			$config_class = isset($config['config_class']) ? $config['config_class'] : $this->_config_class;
			$this->_config = new $config_class ($config);
		}
		return $this;
	}

	/**
	 *
	 */
	public function setConfigClass($class) {
		$this->_config_class = $class;
		return $this;
	}

	/**
	 *
	 */
	public function setConfigObject($config) {
		// check base type or interface here?
		$this->_config = $config;
		$this->_config_class = get_class($config);
		return $this;
	}

	/**
	 *
	 */
	public function setResultClasses($result_class, $recordset_class) {
		if ($result_class) {
			$this->_result_class = $result_class;
		}
		if ($recordset_class) {
			$this->_recordset_class = $recordset_class;
		}
		return $this;
	}

	/**
	 *
	 */
	public function getConfig($sql='') {
		if (isset($this->_config)) {
			return $this->_config->getConfigBySql($sql);
		}
	}

	public function setException($class) {
		if ($class === true) {
			$this->_exception = 'A_Db_Exception';
		} else {
			$this->_exception = $class;
		}
	}	

	public function getSql() {
		return $this->_sql;
	}

	/**
	 * Connect to database based on SQL. Tracks and uses existing connections. 
	 */
	public function connect($name='') {
		if (isset($this->_config)) {
			$config = $this->_config->getConfig($name);
			if (isset($config['name']) && $config['data']) {
				if (!isset($this->_connection[$config['name']])) {
					$this->_connection[$config['name']] = $this->_connect($config['data']);
				}
				return $this->_connection[$config['name']];
			} else {
				$this->_errorHandler(0, "No connection config data for '{$config['name']}'. ");
			}
		} else {
			$this->_errorHandler(0, "No config data. ");
		}
	}

	/**
	 * Supplied my child class - must connect as specified by $config
	 */
	abstract protected function _connect($config);

	/**
	 * Connect to database based on SQL. Tracks and uses existing connections. 
	 */
	public function connectBySql($sql='') {
		$name = $this->_config->getConfigName($sql);
		return $this->connect($name);
	}

	/*
	 * Closes all or named connection (if close supported by extension)
	 */
	public function close($name='') {
		if ($name) {
			if (is_string($name)) {
				$names = array($name);
			} elseif (is_array($name)) {
				$names = $name;
			} 
		} elseif (isset($this->_connection)) {			// connections and no name given
			$names = array_keys($this->_connection);	// close all
		} else {
			$names = array();
		}
		foreach ($names as $name) { 
			if (isset($this->_connection[$name])) {
				$this->_close($this->_connection[$name]);
				unset($this->_connection[$name]);
			}
		}
	}
		
	public function disconnect() {
		$this->close();
	}
		
	/**
	 * Supplied my child class - must close connection if supported by extension
	 * passes back what _connect() returns for connection
	 */
	abstract protected function _close($name='');
	
	/**
	 * Adds limit syntax to SQL statement
	 */
	abstract public function limit($sql, $count, $offset='');
	
	public function start($connection_name='') {
		if ($this->_transaction_level < 1) {
			$result = $this->query('START');
			$this->_transaction_level = 0;
		} else {
			$result = false;
		}
		++$this->_transaction_level;
		return $result;
	}

	public function savepoint($savepoint='', $connection_name='') {
		if ($savepoint) {
			return $this->query('SAVEPOINT ' . $savepoint);
		}
	}

	public function commit($connection_name='') {
		--$this->_transaction_level;
		if ($this->_transaction_level == 0) {
			$result = $this->query('COMMIT');
		} else {
			$result = false;
		}
		return $result;
	}

	public function rollback($savepoint='', $connection_name='') {
		--$this->_transaction_level;
		if ($this->_transaction_level == 0) {
			$result = $this->query('ROLLBACK' . ($savepoint ? ' TO SAVEPOINT ' . $savepoint : ''));
		} else {
			$result = false;
		}
		return $result;
	}

	public function getTransactionLevel() {
		return $this->_transaction_level;
	}
		
	public function escape($value) {}


	public function _errorHandler($errno, $errorMsg) {
		$this->_errorMsg .= $errorMsg;
		if ($this->_exception) {
			throw A_Exception::getInstance($this->_exception, $errorMsg);
		}
	}	

	public function isError() {
		return $this->_error;
	}
		
	public function getErrorMsg() {
		return $this->_errorMsg;
	}
		
}

