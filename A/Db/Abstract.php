<?php
/**
 * Class for connecting to SQL databases and performing common database operations.
 *
 */
abstract class A_Db_Abstract
{

    /**
     * User-provided configuration
     * @var array
     */
    protected $_config = array();

    /**
     * Database connection
     * @var object|resource|null
     */
    protected $_connection = null;

    /**
     * Constructor.
     *
     * $config is an array of key/value pairs or an instance of A_DataContainer
     * containing configuration options.  These options are common to most adapters:
     *
     * dbname         => (string) The name of the database to user
     * username       => (string) Connect to the database as this username.
     * password       => (string) Password associated with the username.
     * host           => (string) What host to connect to, defaults to localhost
     *
     * @param  array|A_Config $config
     * @throws A_Db_Exception
     */
    public function __construct($config) {
        if (!is_array($config)) {
            if ($config instanceof A_DataContainer) {
                $config = $config->toArray();
            } else {
                require_once 'A/Db/Exception.php';
                throw new A_Db_Exception('Adapter parameters must be in an array or a A_Config object');
            }
        }

        $this->_checkRequiredOptions($config);
        $this->_config  = $config; 
    }

    /**
     * Check for config options that are mandatory, throw exceptions if any are missing.
     *
     * @param array $config
     * @throws A_Db_Exception
     */
    protected function _checkRequiredOptions(array $config) {
        if (! array_key_exists('dbname', $config)) {
            require_once 'A/Db/Exception.php';
            throw new A_Db_Exception("Configuration array must have a key for 'dbname' that names the database instance");
        }

        if (! array_key_exists('password', $config)) {
           require_once 'A/Db/Exception.php';
           throw new A_Db_Exception("Configuration array must have a key for 'password' for login credentials");
        }

        if (! array_key_exists('username', $config)) {
            require_once 'A/Db/Exception.php';
            throw new A_Db_Exception("Configuration array must have a key for 'username' for login credentials");
        }
    }

    /**
     * Returns the underlying database connection object or resource.
     * If not presently connected, this initiates the connection.
     *
     * @return object|resource|null
     */
    public function getConnection() {
        $this->_connect();
        return $this->_connection;
    }

    /**
     * Returns the configuration variables in this adapter.
     *
     * @return array
     */
    public function getConfig(){
        return $this->_config;
    }

    /**
     * Prepares and executes an SQL statement with bound data.
     *
     * @param  mixed  $sql The SQL statement 
     * @return A_Sql_Statement
     */
    public function query($sql) {
        // connect to the database if needed
        $this->_connect();
		$this -> _query($sql);
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param mixed $table The table to insert data into.
     * @param array $bind Column-value pairs.
     * @return int The number of affected rows.
     */
    public function insert($table, array $bind) {
        $insert = new A_Sql_Insert($table,$bind);
        return $this -> query($insert -> render());
    }

    /**
     * Updates table rows with specified data based on a WHERE clause.
     *
     * @param  mixed        $table The table to update.
     * @param  array        $bind  Column-value pairs.
     * @param  mixed        $where UPDATE WHERE clause(s).
     * @return int          The number of affected rows.
     */
    public function update($table, array $bind, $where = array()) {
        $update = new A_Sql_Update($table,$bind,$where);
        return $this -> query($update -> render());
    }

    /**
     * Deletes table rows based on a WHERE clause.
     *
     * @param  mixed        $table The table to update.
     * @param  mixed        $where DELETE WHERE clause(s).
     * @return int          The number of affected rows.
     */
    public function delete($table, $where = '') {
        $delete = new A_Sql_Delete($table,$where);
        return $this -> query($delete -> render());
    }

    /**
     * Creates and returns a new A_Sql_Select object for this adapter.
     *
     * @return A_Sql_Select
     */
    public function select() {
        return new A_Sql_Select($this);
    }

    /**
     * Escape a raw string.
     *
     * @param string $value     Raw string
     * @return string           Quoted string
     */
    protected function _escape($value) {
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        return addcslashes($value, "\000\n\r\\'\"\032");
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
            foreach ($value as &$val) {
                $val = $this->escape($val);
            }
            return implode(', ', $value);
        }

        return $this->_escape($value);
    }
    
    /**
     * Abstract Methods
     */

    /**
     * Returns the column descriptions for a table.
     *
     * @param string $tableName
     * @param string $schemaName (optional)
     * @return array
     */
    abstract public function describeTable($tableName, $schemaName = null);

    /**
     * Creates a connection to the database.
     * @return void
     */
    abstract protected function _connect();

    /**
     * Force the connection to close.
     * @return void
     */
    abstract public function closeConnection();

    /**
     * Execute an SQL statement
     *
     * @param mixed $sql The SQL statement
     */
    abstract public function _query($sql);

    /**
     * Gets the last ID generated automatically by an IDENTITY/AUTOINCREMENT column.
     *
     * @param string $tableName   OPTIONAL Name of table.
     * @param string $primaryKey  OPTIONAL Name of primary key column.
     * @return string
     */
    abstract public function lastInsertId($tableName = null, $primaryKey = null);
    
    /**
     * Adds an adapter-specific LIMIT clause to the SELECT statement.
     *
     * @param mixed $sql
     * @param integer $count
     * @param integer $offset
     * @return string
     */
    abstract public function limit($sql, $count, $offset = 0);
}