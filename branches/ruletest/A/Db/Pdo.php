<?php
/**
 * Pdo.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/*
    * PDO::__construct � Creates a PDO instance representing a connection to a database
    * PDO::getAttribute � Retrieve a database connection attribute
    * PDO::getAvailableDrivers � Return an array of available PDO drivers
    * PDO::setAttribute � Set an attribute
    * PDO::prepare � Prepares a statement for execution and returns a statement object
    * PDO::exec � Execute an SQL statement and return the number of affected rows
    * PDO::query � Executes an SQL statement, returning a result set as a PDOStatement object
    * PDO::quote � Quotes a string for use in a query.
    * PDO::lastInsertId � Returns the ID of the last inserted row or sequence value
    * PDO::beginTransaction  � Initiates a transaction
    * PDO::commit � Commits a transaction
    * PDO::rollBack � Rolls back a transaction
    * PDO::errorCode � Fetch the SQLSTATE associated with the last operation on the database handle
    * PDO::errorInfo � Fetch extended error information associated with the last operation on the database handle
*/

/**
 * A_Db_Pdo
 * 
 * Adapt PDO to basic database connection functionality.  Configuration array can contain the following indices: type, hostspec, username, password, database.
 */
class A_Db_Pdo extends A_Db_Base {

	protected $dsn = null;
	protected $_connection = null;
	protected $connected = false;
	
	public function __construct($config, $username='', $password='', $attr=array()) {
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

	public function _connect($config) {
		if (is_array($config)) {
			if (!isset($config['phptype'])) {
				$this->_error = 1;
				$this->_errorMsg = "config['phptype'] not set. ";
				return;
			}
			// init attributes array in not in config
			if (!isset($config['attr'])) {
				$config['attr'] = array();
			}
			if (isset($config['persistent'])) {
				$config['attr'][PDO::ATTR_PERSISTENT] = $config['persistent'];
			}
			$dsn = $config['phptype'] . ":host=" . $config['host'] . ";" . "dbname=" . $config['database'] . (isset($config['port']) ? ";port={$config['port']}" : '');
		} else {
			$dsn = $config;
		}
		
		$connection = null;
		if ($dsn && $config['username'] && $config['password']) {
			$connection = new PDO($dsn, $config['username'], $config['password'], $config['attr']);
			// have query() return A_Db_Pdo_Recordset
			$connection->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('A_Db_Pdo_Recordset', array()));
		} else {
			$this->_errorHandler(0, "No DSN, username or password to create PDO object. ");
		}
		return $connection;
	}
		
	public function _close($name='') {
		if (isset($this->_connection[$name])) {
			unset($this->_connection[$name]);
		}
	}
		
	public function selectDb($database) {
		$this->query("USE $database");
		return $this;
	}
		
	/*
	 * public function query() implemented in PDO
	 */
	public function query($sql, $bind=array(), $arg3=null, $arg4=null) {
		if (is_object($sql)) {
			// convert object to string by executing SQL builder object
			$sql = $sql->render($this);   // pass $this to provide db specific escape() method
		}
		$connection = $this->connectBySql($sql);
		if ($connection) {
			if (! $bind) {
				$result = $connection->query($sql);
			} elseif (is_array($bind)) {
				$result = $connection->prepare($sql);
			} else {
				$result = $connection->query($sql, $bind, $arg3, $arg4);
			}
			$this->_sql[] = $sql;			// save history
			$this->_setError($connection);
			if (!$result) {
				$result = new A_Db_Pdo_Result($this->_error, $this->_errorMsg);
			}
			return $result;
		} else {
			$this->_errorHandler(0, 'No connection for query. ');
		}
	}
	
	public function limit($sql, $count, $offset='') {
		if ($offset) {
			$count = "$count OFFSET $offset";
		} 
		return "$sql LIMIT $count";
	}
		
	public function lastId() {
		$connection = $this->connectBySql('INSERT');
		return $connection->lastInsertId();
	}
		
	public function escape($value) {
		$connection = $this->connectBySql();
		return trim($connection->quote($value), "\"'");
	}
	
	protected function _setError($connection) {
		// get error array
		$errorInfo = $connection->errorInfo();
		$this->_error = ($errorInfo[0] == '00000') ? 0 : $errorInfo[0];		// PDO success value
		if(isset($errorInfo[2])){
			$this->errmsg = $errorInfo[2];			
		}
	}
	
	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
	/**
	 * compatablility methods
	 */
	public function getAttribute($attribute, $connection_name='') {
		$connection = $this->connect($connection_name);
		return $connection->getAttribute($attribute);
	}
	
	public function getAvailableDrivers($connection_name='') {
		$connection = $this->connect($connection_name);
		return $connection->getAvailableDrivers();
	}
	
	public function setAttribute($attribute, $value, $connection_name='') {
		$connection = $this->connect($connection_name);
		return $connection->setAttribute($attribute, $value);
	}
	
	public function prepare($sql, $driver_options=array(), $connection_name='') {
		$connection = $this->connect($connection_name);
		return $connection->prepare($sql, $driver_options);
	}
	
	public function exec($sql, $connection_name='') {
		$connection = $this->connect($connection_name);
		return $connection->getAttribute($sql);
	}
	
	public function quote($value, $connection_name='') {
		$connection = $this->connect($connection_name);
		return trim($connection->quote($value), "\"'");
	}
	
	public function lastInsertId($connection_name='') {
		$connection = $this->connect($connection_name);
		return $connection->lastInsertId();
	}
	
	public function beginTransaction($connection_name='') {
		return parent::start($connection_name);
	}
	
	public function commit($connection_name='') {
		return parent::commit($connection_name);
	}
	
	public function errorCode($connection_name='') {
		$connection = $this->connect($connection_name);
		return $connection->errorCode();
	}
	
	public function errorInfo($connection_name='') {
		$connection = $this->connect($connection_name);
		return $connection->errorInfo();
	}
	
	public function __sleep() {
		$connection = $this->connect($connection_name);
		return $connection->__sleep();
	}
	
	public function __wakeup() {
		$connection = $this->connect($connection_name);
		return $connection->__wakeup();
	}
	
}


class A_Db_Pdo_Recordset extends PDOStatement {
	
	protected function __construct() {
	}
		
	public function isError() {
		$code = $this->errorCode();
		return $code == '00000' ? 0 : $code;		// PDO success value
	}
		
	public function getErrorMsg() {
		// get error array
		$errorInfo = $this->errorInfo();
		// return the message only
		return $errorInfo[2];
	}

	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
	public function fetchRow() {
		return $this->fetch(PDO::FETCH_ASSOC);
	}
		
	/*
	 * public function fetchObject() implemented in PDOStatement
	 */
		
	/*
	 * public function fetchAll() implemented in PDOStatement
	 */
		
	public function numRows() {
		return $this->rowCount();
	}
		
	public function numCols() {
		return $this->columnCount();
	}
	
}


class A_Db_Pdo_Result {
	protected $_error;
	protected $_errorMsg;
	
	public function __construct($error, $errorMsg) {
		$this->_error = $error;
		$this->_errorMsg = $errorMsg;
	}
		
	public function isError() {
		return $this->_error;
	}
		
	public function getErrorMsg() {
		return $this->_errorMsg;
	}

	public function fetchRow() {
		return array();
	}
		
	public function fetchObject() {
		return array();
	}
		
	public function fetchAll() {
		return array();
	}
		
	public function numRows() {
		return 0;
	}
		
	public function numCols() {
		return 0;
	}
	
}