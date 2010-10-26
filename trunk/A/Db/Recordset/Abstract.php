<?php
/**
 * Database recordset set (abstract class)
 * 
 * This class extends A_Collection to create a set of results from a sql
 * query.  Specific databases must have result classes that extend this
 * one, creating the methods defined here.
 * 
 * @package A_Db_Recordset
 * @author Jonah <jonah@nucleussystems.com>, Christopher <christopherxthompson@gmail.com>
 */
abstract class A_Db_Recordset_Abstract extends A_Collection {
	protected $numRows;
	protected $error;
	protected $errorMsg;
	protected $fetchCount;
	protected $gatherMode;
	protected $result;
	
	public function __construct($numRows, $error, $errorMsg) {
		$this->numRows = $numRows;
		$this->error = $error;
		$this->errorMsg = $errorMsg;
		$this->gatherMode = false;
		$this->fetchCount = 0;
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
	
	public function next() {
		return $this->fetchRow();
	}
	
	/**
	 * Turn on Lazy Gather mode
	 * @param boolean $enable True to enable, false to disable.  Optional, true by default
	 */
	public function enableGather($enable = true)
	{
		$this->gatherMode = (boolean) $enable;
		return $this;
	}
	
	/**
	 * Takes care of Lazy Gather (if enabled) and calls _fetch if necessary
	 * @param string $className The name of the object to return.  Array returned if null.
	 * @return mixed The row as the object specified (or as array)
	 */
	public function fetchRow($className = null)
	{
		if ($this->result) {
			if (isset($this->_data[$this->fetchCount])) {
				$row = next($this->_data);
			} else {
				$row = $this->_fetch();
				if ($this->gatherMode == true) {
					$this->_data[$this->fetchCount] = $row;
				}
				$this->fetchCount++;
			}
			if ($className == 'object') {
				$row = (object) $row;
			} elseif (!empty($className)) {
				$row = new $className($row);
			}
			return $row;
		}
	}
}