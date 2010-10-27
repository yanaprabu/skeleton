<?php
/**
 * Database recordset set (abstract class)
 * 
 * This class implements the Iterator interface to allow iterating over it with
 * foreach.  Specific databases must have result classes that extend this one,
 * creating the methods defined here.
 * 
 * @package A_Db_Recordset
 * @author Jonah <jonah@nucleussystems.com>, Christopher <christopherxthompson@gmail.com>
 */
abstract class A_Db_Recordset_Abstract implements Iterator
{
	
	protected $numRows = 0;
	protected $error = 0;
	protected $errorMsg = '';
	protected $fetchCount = 0;
	protected $result = null;
	protected $currentRow = null;
	
	const OBJECT = 'stdClass';
	
	/**
	 * Constructor, receives the number of rows, error number, and error message
	 * from creator
	 * @param int $numRows Number of rows returned from query
	 * @param int $error Error number from database
	 * @param string $errorMsg The error message from database
	 */
	public function __construct($numRows, $error, $errorMsg)
	{
		$this->numRows = $numRows;
		$this->error = $error;
		$this->errorMsg = $errorMsg;
	}
	
	/**
	 * Gets and returns a row from the database
	 */
	public function fetchRow($className = null)
	{
		if ($this->valid()) {
			if ($className == self::OBJECT) {
				$row = (object) $row;
			} elseif (!empty($className)) {
				$row = new $className($this->currentRow);
			} else {
				$row = $this->currentRow;
			}
			$this->loadNextRow();
			return $row;
		}
	}
	
	/**
	 * Gets the number of rows got in query
	 */
	public function numRows()
	{
		return $this->numRows;
	}
	
	/**
	 * Returns if there was an error or not during query
	 */
	public function isError()
	{
		return (bool) $this->error;
	}
	
	/**
	 * Returns the error message produced by database
	 */
	public function getErrorMsg()
	{
		return $this->errorMsg;
	}
	
	/**
	 * Iterator function, resets the index
	 */
	public function rewind()
	{
		// nothing to rewind
	}
	
	/**
	 * Iterator function, gets the current database row
	 */
	public function current()
	{
		return $this->currentRow;
	}
	
	/**
	 * Iterator function, gets the current key
	 */
	public function key()
	{
		return $this->fetchCount;
	}
	
	/**
	 * Iterator function, moves the index forward
	 */
	public function next()
	{
		$this->loadNextRow();
	}
	
	/**
	 * Iterator function, returns true if there are more elements
	 */
	public function valid()
	{
		return !empty($this->currentRow);
	}
	
	/**
	 * Loads the next row into memory
	 */
	private function loadNextRow()
	{
		$this->currentRow = $this->_fetch();
		$this->fetchCount++;
	}
	
	/**
	 * Sets the database resource object, and loads first row into memory
	 * @param mixed $result Resource object
	 */
	public function setResult ($result) {
		$this->result = $result;
		$this->loadNextRow();
	}
	
}
	
	/*public function __construct($numRows, $error, $errorMsg) {
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
	 *
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
	
	/**
	 * Turn on Lazy Gather mode
	 * @param boolean $enable True to enable, false to disable.  Optional, true by default
	 *
	public function enableGather($enable = true)
	{
		$this->gatherMode = (boolean) $enable;
		return $this;
	}
	
	public function next() {
		$this->getRow();
	}
	
	public function current() {
		return $this->getCurrentRow(null);
	}
	
	public function valid()
	{
		return !empty($this->currentRow);
	}
	
	protected function getRow()
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
			$this->currentRow = $row;
			return;
		}
		$this->currentRow = $this->_fetch();
	}
	
	/**
	 * Takes care of Lazy Gather (if enabled) and calls _fetch if necessary
	 * @param string $className The name of the object to return.  Array returned if null.
	 * @return mixed The row as the object specified (or as array)
	 *
	public function fetchRow($className = null)
	{
		if (!$this->valid()) {
		$this->getRow();
		$row = $this->getCurrentRow($className);
		print_r($row);
		return $row;
	}
	
	protected function getCurrentRow($className)
	{
		if ($className == self::OBJECT) {
			$row = (object) $this->currentRow;
		} elseif (!empty($className)) {
			$row = new $className($this->currentRow);
		} else {
			$row = $this->currentRow;
		}
		$this->currentRow = null;
		return $row;
	}
}*/