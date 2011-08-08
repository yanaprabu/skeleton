<?php
/**
 * Mysql.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Db_Mysql
 * 
 * Database connection class using the mysql_ library.  Configuration array can contain the following indices: type, hostspec, username, password, database.
 * 
 * @package A_Db
 */
class A_Db_Mysql extends A_Db_Adapter
{

	protected $_sequence_ext = '_seq';
	protected $_sequence_start = 1;
	protected $_recordset_class = 'A_Db_Recordset_Mysql';
	protected $_result_class = 'A_Db_Result';
	
	protected function _connect()
	{
		$host = $this->_config['host'];
		// fix for problem connecting to server with localhost. Windows only?
		if (($host == 'localhost') && version_compare(PHP_VERSION, '5.3.0', '>=')) {
			$host = '127.0.0.1';
		}
		if (isset($this->_config['persistent'])) {
			$this->_connection = mysql_pconnect($host, $this->_config['username'], $this->_config['password']);
		} else {
			$this->_connection = mysql_connect($host, $this->_config['username'], $this->_config['password']);
		}
		$this->_errorHandler(mysql_errno($this->_connection), mysql_error($this->_connection));
		if ($this->_connection && isset($this->_config['database'])) {
			$this->selectDb($this->_config['database']);
		}
		if (!$this->_connection) {
			$this->_errorHandler(1, "Cconnection failed. ");
		}
	}
	
	protected function _query($sql)
	{
		$result = mysql_query($sql, $this->_connection);
		$this->_errorHandler(mysql_errno($this->_connection), mysql_error($this->_connection));
		if ($result && $this->queryHasResultSet($sql)) {
			$this->_numRows = mysql_num_rows($result);
			$resultObject = $this->createRecordsetObject();
			$resultObject->setResult($result);
		} else {
			$this->_numRows = mysql_affected_rows($this->_connection);
			$resultObject = $this->createResultObject();
		}
		return $resultObject;
	}
	
	public function limit($sql, $count, $offset='')
	{
		return "$sql LIMIT $count" . ($offset > 0 ? " OFFSET $offset" : '');
	}
	
	public function nextId($sequence)
	{
		if ($sequence) {
			$result = $this->query("UPDATE $sequence{$this->_sequence_ext} SET id=LAST_INSERT_ID(id+1)", $this->_connection);
			if ($result) {
				$id = $this->lastId();
				if ($id > 0) {
					return $id;
				} else {
					$result = $this->query("INSERT $sequence{$this->_sequence_ext} SET id=1", $this->_connection);
					$id = $this->lastId();
					if ($id > 0) {
						return $id;
					}
				}
			} elseif ($this->isError() == 1146) {		// table does not exist
				if ($this->createSequence($sequence)) {
					return $this->_sequence_start;
				}
			}
		}
		return 0;
	}
	
	public function createSequence($sequence)
	{
		$result = 0;
		if ($sequence) {
			$result = $this->query("CREATE TABLE $sequence{$this->_sequence_ext} (id int(10) unsigned NOT NULL auto_increment, PRIMARY KEY(id)) TYPE=MyISAM AUTO_INCREMENT={$this->_sequence_start}", $this->_connection);
		}
		return($result);
	}
	
	public function escape($value)
	{
		return mysql_real_escape_string($value, $this->_connection);
	}
	
	protected function _lastId()
	{
		return mysql_insert_id($this->_connection);
	}
	
	protected function _selectDb($database)
	{
		$success = mysql_select_db($database, $this->_connection);
		if (!$success) {
			$this->_errorHandler(mysql_errno($this->_connection), mysql_error($this->_connection));
		}
	}
	
	protected function _close()
	{
		mysql_close($this->_connection);
	}

}
