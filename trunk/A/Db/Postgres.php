<?php
/**
 * Postgres.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Db_Postgres
 * 
 * Database connection class using Postgres.  Configuration array can contain the following indices: type, hostspec, username, password, database.
 */
class A_Db_Postgres extends A_Db_Adapter
{
	protected $sequenceext = '_seq';
	protected $sequencestart = 1;
	protected $_recordset_class = 'A_Db_Recordset_Postgres';
	protected $_result_class = 'A_Db_Result';
	
	public function connect()
	{
		if ($this->_config && !$this->_connection) {
			$connstr = '';
			foreach ($this->_config as $param => $value) {
				if ($value) {
					switch ($param) {
						case 'hostspec':
						case 'host':
							$connstr .= "host={$value} ";
							break;
						case 'dbname':
						case 'database':
							$connstr .= "dbname={$value} ";
							break;
						case 'port':
							$connstr .= "port={$value} ";
							break;
						case 'username':
						case 'user':
							$connstr .= "user={$value} ";
							break;
						case 'password':
							$connstr .= "password={$value} ";
							break;
					}
				}
			}
			if (isset($this->_config['persistent'])) {
				$this->_connection = pg_pconnect($connstr);
			} else {
				$this->_connection = pg_connect($connstr);
			}
			if (pg_connection_status($this->_connection) !== PGSQL_CONNECTION_OK) {
				$this->_errorHandler(1, "Cconnection failed. ");
			}    
		}
		return $this;
	}
	
	protected function _close()
	{
		pg_disconnect($this->_connection);
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
			$result = pg_query($this->_connection, $sql);
			$this->_sql[] = $sql;			// save history
			if ($result) {
				$this->_errorMsg = pg_result_error($result);
			} else {
				$this->_errorMsg = pg_last_error($this->_connection);
			}
			$this->_error = $this->_errorMsg != '';
			$this->_errorHandler($this->_error, $this->_errorMsg);
			if ($result && in_array(strtoupper(substr($sql, 0, 5)), array('SELEC','SHOW ','DESCR'))) {
				$this->_numRows = pg_num_rows($result);
				$obj = new $this->_recordset_class($this->_numRows, $this->_error, $this->_errorMsg);
				// call RecordSet specific setters
				$obj->setResult($result);
			} else {
				$this->_numRows = pg_affected_rows($this->_connection);
				$obj = new $this->_result_class($this->_numRows, $this->_error, $this->_errorMsg);
			}
			return $obj;
		} else {
			$this->_errorHandler(3, 'No connection. ');
		}
	}
	
	public function limit($sql, $count, $offset='')
	{
		if ($offset) {
			$count = "$count OFFSET $offset";
		} 
		return "$sql LIMIT $count";
	}
	
	public function nextId($sequence)
	{
	    if ($sequence) {
		    $result = pg_query($this->_connection, "SELECT nextval('$sequence')");
	    	if ($result) {
		        $row = pg_fetch_array($this->_connection);
				return $row[0];
			} else {		// table does not exist
				if ($this->createSequence($sequence)) {
					return $this->sequencestart;
				}
			}
	    }
	    return 0;
	}
	
	public function createSequence($sequence)
	{
	    $result = 0;
	    if ($sequence) {
		    $result = pg_query($this->_connection, "CREATE SEQUENCE $sequence START {$this->sequencestart}");
	    }
	    return $result;
	}
	
	public function escape($value)
	{
		return pg_escape_string($value);
	}
	
	public function isError()
	{
		return pg_last_error($this->_connection) != '';
	}
	
	public function getErrorMsg()
	{
		return pg_last_error($this->_connection);
	}
	
	/**
	 * Alias for getErrorMsg()
	 * 
	 * @deprecated
	 * @see getErrorMsg
	 */
	public function getMessage()
	{
		return $this->getErrorMsg();
	}

}
