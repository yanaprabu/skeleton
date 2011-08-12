<?php
/**
 * Postgres.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Db_Postgres
 * 
 * Database connection class using Postgres.  Configuration array can contain the following indices: type, hostspec, username, password, database.
 * 
 * @package A_Db
 */
class A_Db_Postgres extends A_Db_Adapter
{

	protected $sequenceext = '_seq';
	protected $sequencestart = 1;
	protected $_recordset_class = 'A_Db_Recordset_Postgres';
	protected $_result_class = 'A_Db_Result';
	
	protected function _connect()
	{
		$connstr = '';
		foreach ($this->_config as $param => $value) {
			if ($value) {
				switch ($param) {
					case 'host':
						$connstr .= "host={$value} ";
						break;
					case 'database':
						$connstr .= "dbname={$value} ";
						break;
					case 'port':
						$connstr .= "port={$value} ";
						break;
					case 'username':
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
	
	protected function _query($sql)
	{
		$result = pg_query($this->_connection, $sql);
		$errorMsg = pg_last_error($this->_connection);
		$this->_errorHandler($errorMsg != '', $errorMsg);
		if ($result && $this->queryHasResultSet($sql)) {
			$this->_numRows = pg_num_rows($result);
			$resultObject = $this->createRecordsetObject();
			$resultObject->setResult($result);
		} else {
			$this->_numRows = $this->_connection->affected_rows;
			$resultObject = $this->createResultObject();
		}
		return $resultObject;
	}
	
	protected function _close()
	{
		pg_disconnect($this->_connection);
	}
	
	public function limit($sql, $count, $offset='')
	{
		return "$sql LIMIT $count" . ($offset > 0 ? " OFFSET $offset" : '');
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
	 * @see getErrorMsg()
	 */
	public function getMessage()
	{
		return $this->getErrorMsg();
	}
	
	protected function _lastId()
	{
		$row = pg_fetch_row(pg_query($this->_connection, 'SELECT lastval();'));
		return $row[0];
	}
	
	protected function _selectDb($database)
	{
		$this->_errorHandler(1, 'Selecting a different database is not supported by PostgreSQL');
	}

}
