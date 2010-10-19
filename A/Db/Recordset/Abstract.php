<?php
/**
 * Database recordset set (abstract class)
 * 
 * This class extends A_Collection to create a set of results from a sql
 * query.  Specific databases must have result classes that extend this
 * one, creating the methods defined here.
 * 
 * @package A_Db_Recordset
 * @author Jonah Dahlquist <jonah@nucleussystems.com>
 */
abstract class A_Db_Recordset_Abstract extends A_Collection {
	protected $numRows;
	protected $error;
	protected $errorMsg;
	
	public function __construct($numRows, $error, $errorMsg) {
		$this->numRows = $numRows;
		$this->error = $error;
		$this->errorMsg = $errorMsg;
	}
		
	public function numRows() {
		return $this->numRows;
	}
		
	public function isError() {
		return $this->error;
	}
		
	public function getErrorMsg() {
		return $this->errorMsg;
	}
	
	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
	public function fetchAll() {
		$this->_data = array();
		while ($row = $this->fetchRow()) {
			$this->_data[] = $row;
		}
		return $this;
	}
		
}