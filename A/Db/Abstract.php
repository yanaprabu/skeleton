<?php
/**
 * Class for connecting to SQL databases and performing common database operations.
 *
 * @package A_Db
 */
abstract class A_Db_Abstract {

	protected $_connection;								// 

	protected $_config;									// 

	protected $_config_class = 'A_Db_Config_Single';	// 

	/**
	 * convert connnect keys based on this table
	 * @var array
	 */
	protected $_config_aliases = array(
									'database'=>'dbname', 
									'hostname'=>'host',
									);

	protected $_exception = '';							// A_Db_Exception

	protected $_recordset_class;
	protected $_result_class;
	
	protected $error = 0;
	protected $errmsg = '';

	protected $_sql = array();
	
	protected $_transaction_level = 0;
	
	/**
	 * Constructor.
	 *
	 * $config is an array of key/value pairs or an instance of A_DataContainer
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
		if (isset($this->_config)) {
			$this->_config->config($config);
		} else {
			if (isset($config['exception'])) {
				$this->setException($config['exception']);				
			}
			$config_class = isset($config['config_class']) ? $config['config_class'] : $this->_config_class;
#if (isset($config['config_class'])) echo "Setting config class from config data - A_Db_Abstract::config() config_class=$config_class<br/>";
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

	public function _errorHandler($errno, $errmsg) {
		$this->_errmsg .= $errmsg;
		if ($this->_exception) {
			throw A_Exception::getInstance($this->_exception, $errmsg);
		}
	}	

	public function _getErrorMsg() {
		return $this->_errmsg;
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
			$connection = $this->getConnection($connection_name);
			$result = $connection->query('START');
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

	public function isError() {
		return $this->error;
	}
		
	public function getErrorMsg() {
		return $this->errmsg;
	}
		
}


/**
 * This is the default connnection configuration class. 
 * This class can be replaceable with a class that provides other connection functionality, like master/slave support. 
 * See A_Db_Config_* for other options. 
 */
class A_Db_Config_Single {
	/**
	 * User-provided configuration data
	 * @var array
	 */
	protected $_config = array();
	
	/**
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
	 * Set config array directly. Containers are converted to an array.
	 */
	public function config($config) {
		if (is_object($config) && method_exists($config, 'toArray')) {
			$config = $config->toArray();
		}
		if (is_array($config)) {
			$this->_config  = $config; 
		}			
	}

	/**
	 * Method called by connect() to get config data
	 */
	public function getConfig($sql='') {
		if (isset($this->_config)) {
			return array('name'=>'', 'data'=>$this->_config);
		} else {
			return array('name'=>'', 'data'=>array());
		}
	}

	/**
	 * Method called by query(), etc. to get config data
	 */
	public function getConfigName($sql='') {
		return '';
	}

}
