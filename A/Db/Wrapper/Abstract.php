<?php
/**
 * Class for connecting to SQL databases and performing common database operations.
 *
 * @package A_Db
 */
class A_Db_Wrapper_Abstract
{

	/**
	 * User-provided configuration
	 * @var array
	 */
	protected $config = array();

	/**
	 * Database connection
	 * @var object|resource|null
	 */
	protected $db = null;
	
	/**
	 * error number/flag
	 * @var integer 0==no errror
	 */
	protected $error = 0;

	/**
	 * last error message
	 * @var string
	 */
	protected $errorMsg = '';

	/**
	 * throw exceptions on errors
	 * @var boolean true|false
	 */
	protected $exceptions;

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
	public function __construct($config, $exceptions=false) {
		$this->exceptions = $exceptions;
		$this->setConfig($config); 
	}

	/**
	 * Check for config options that are mandatory, throw exceptions if any are missing.
	 *
	 * @param array $config
	 * @throws A_Db_Exception
	 */
	protected function setConfig(array $config) {
		if (!is_array($config)) {
			if (method_exists($config,'toArray')) {
				$config = $config->toArray();
			} else {
				$this->error('Adapter parameters must be in an array or a object with toArray() method');
			}
		}
		if (isset($config['exceptions'])) {
			$this->exceptions = $config['exceptions'];
		}
		if (! isset($config['dbname'])) {
			$this->error("Configuration array must have a key for 'dbname' that names the database instance");
		}

		if (! isset($config['password'])) {
		   $this->error("Configuration array must have a key for 'password' for login credentials");
		}

		if (! isset($config['username'])) {
			$this->error("Configuration array must have a key for 'username' for login credentials");
		}
	}
	
	/**
	 * Sets error and errorMsg, throws exceptions if exceptions are enabled
	 *
	 * @param array $config
	 * @throws A_Db_Exception
	 */
	protected function error($message) {
		$this->error = 1;
		$this->errorMsg = $message;
		if ($this->exceptions) {
			#require_once 'A/Db/Exception.php';
			throw new A_Db_Exception($message);
		}
	}
	
	/**
	 * Returns the underlying database connection object or resource.
	 * If not presently connected, this initiates the connection.
	 *
	 * @return object|resource|null
	 */
	public function setDb($db) {
		return $this->db = $db;
	}

	/**
	 * Returns the underlying database connection object or resource.
	 * If not presently connected, this initiates the connection.
	 *
	 * @return object|resource|null
	 */
	public function getDb() {
		return $this->db;
	}

	/**
	 * Returns the configuration variables in this adapter.
	 *
	 * @return array
	 */
	public function getConfig(){
		return $this->config;
	}

	/**
	 * Prepares and executes an SQL statement with bound data.
	 *
	 * @param  mixed  $sql The SQL statement 
	 * @return A_Sql_Statement
	 */
    public function query($sql, $bind=array()) {
        if ($bind) {
             $prepare = new A_Sql_Prepare($sql, $bind);
             $prepare->setDb($this->db);
             $sql = $prepare->render();
        }
		$this->result = $this->db->query($sql);
	}

	/**
	 * Inserts a table row with specified data.
	 *
	 * @param mixed $table The table to insert data into.
	 * @param array $bind Column-value pairs.
	 * @return int The number of affected rows.
	 */
	public function insert($table, array $bind) {
		if (! $this->insert) {
			$this->insert = new A_Sql_Insert($table,$bind);
			$this->insert->setDb($this->db);
		}
		return $this->insert;
	}

	/**
	 * Updates table rows with specified data based on a WHERE clause.
	 *
	 * @param  mixed		$table The table to update.
	 * @param  array		$bind  Column-value pairs.
	 * @param  mixed		$where UPDATE WHERE clause(s).
	 * @return int		  The number of affected rows.
	 */
	public function update($table, array $bind, $where = array()) {
		if (! $this->update) {
			$this->update = new A_Sql_Update($table,$bind,$where);
			$this->update->setDb($this->db);
		}
		return $this->db->query($update->render());
	}

	/**
	 * Deletes table rows based on a WHERE clause.
	 *
	 * @param  mixed		$table The table to update.
	 * @param  mixed		$where DELETE WHERE clause(s).
	 * @return int		  The number of affected rows.
	 */
	public function delete($table, $where = '') {
		if (! $this->delete) {
			$this->delete = new A_Sql_Delete($table,$where);
			$this->delete->setDb($this->db);
		}
		return $this->db->query($this->delete->render());
	}

	/**
	 * Creates and returns a new A_Sql_Select object for this adapter.
	 *
	 * @return A_Sql_Select
	 */
	public function select() {
		if (! $this->select) {
			$this->select = new A_Sql_Select($this);
			$this->select->setDb($this->db);
		}
		return $this->select;
	}

	/**
	 * Safely escape a value for an SQL statement.
	 *
	 * If an array is passed as the value, the array values are escaped
	 * and then returned as a comma-separated string.
	 *
	 * @return mixed An SQL-safe escaped value (or string of separated values).
	 */
	public function escape($value) {
		if ($value instanceof A_Sql_Expression) {
			return $value->__toString();
		}

		if (is_array($value)) {
			return implode(', ', $value);
		}

		return $this->db->escape($value);
	}
	
	/**
	 * Returns the column descriptions for a table.
	 *
	 * @param string $tableName
	 * @param string $schemaName (optional)
	 * @return array
	 */
	public function describe($tableName, $schemaName=null) {
		return $this->db->describe($tableName, $schemaName);
	}

	/**
	 * Creates a connection to the database.
	 * @return void
	 */
	public function connect() {
		$this->db->connect();
	}

	/**
	 * Force the connection to close.
	 * @return void
	 */
	public function close() {
		$this->db->close();
	}

	/**
	 * Gets the last ID generated automatically by an IDENTITY/AUTOINCREMENT column.
	 * @return string
	 */
	public function lastId() {
		$this->db->lastIId();
	}
	
	/**
	 * Adds an adapter-specific LIMIT clause to the SELECT statement.
	 *
	 * @param mixed $sql
	 * @param integer $count
	 * @param integer $offset
	 * @return string
	 */
	public function limit($sql, $count, $offset = 0) {
		$this->db->limit($sql, $count, $offset);
	}
}