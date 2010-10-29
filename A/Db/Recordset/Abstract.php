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
	
	protected $result = null;
	protected $numRows = 0;
	protected $error = 0;
	protected $errorMsg = '';

	protected $_data = array();
	protected $nextRowNum = 0;
	protected $currentRow = null;
	protected $gatherMode = false;
	
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
	 * Sets the database resource object, and loads first row into memory
	 * @param mixed $result Resource object
	 */
	public function setResult ($result) {
		$this->result = $result;
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
	 * Gets the number of rows got in query
	 */
	public function numRows()
	{
		return $this->numRows;
	}
	
	public function fetchRow($className=null)
	{
		if (isset($this->_data[$this->nextRowNum])) {
			$row = $this->_data[$this->nextRowNum];
			++$this->nextRowNum;
		} else {
			$row = $this->_fetch();
			if ($this->gatherMode) {
				$this->_data[$this->nextRowNum] = $row;
			} else {
				$this->currentRow = $row;		// save for current()
			}
			++$this->nextRowNum;
		}
		return $row;
	}
	
	public function fetchAll() {
		$this->_data = array();
		while ($row = $this->fetchRow()) {
			$this->_data[] = $row;
		}
		return $this;
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
		// this is called first by foreach
		$this->nextRowNum = 0;
	}
	
	/**
	 * Iterator function, gets the current database row
	 */
	public function current()
	{
		// fetch a record on the first call
		if ($this->nextRowNum == 0) {
			$this->fetchRow();
		}
		// in gather mode get data from internal array, otherwise use row buffer
		return $this->gatherMode ? $this->_data[$this->nextRowNum-1] : $this->currentRow;
	}
	
	/**
	 * Iterator function, gets the current key
	 */
	public function key()
	{
		// if rows the return pos else null
		return $this->nextRowNum > 0 ? $this->nextRowNum - 1 : null;
	}
	
	/**
	 * Iterator function, moves the index forward
	 */
	public function next()
	{
		$this->fetchRow();
	}
	
	/**
	 * Iterator function, returns true if there are more elements
	 */
	public function valid()
	{
		return $this->nextRowNum <= $this->numRows;
	}
	
	/**
	 * returns internal data array
	 */
	public function toArray()
	{
		return $this->_data;
	}
	
/*
	**
	 * Gets and returns a row from the database
	 * @param string $className The class of the object to create (optional)
	 *
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
	
	**
	 * Fetches all rows from the database in the class defined by $className
	 * @param string $className The class of the object to create (optional)
	 *
	public function fetchAll($className = null)
	{
		$result = array();
		while ($row = $this->fetchRow($className)) {
			$result[] = $row;
		}
		return $result;
	}
	
	**
	 * Gets the number of rows got in query
	 *
	public function numRows()
	{
		return $this->numRows;
	}
	
	**
	 * Returns if there was an error or not during query
	 *
	public function isError()
	{
		return (bool) $this->error;
	}
	
	**
	 * Returns the error message produced by database
	 *
	public function getErrorMsg()
	{
		return $this->errorMsg;
	}
	
	**
	 * Iterator function, resets the index
	 *
	public function rewind()
	{
		// nothing to rewind
	}
	
	**
	 * Iterator function, gets the current database row
	 *
	public function current()
	{
		return $this->currentRow;
	}
	
	**
	 * Iterator function, gets the current key
	 *
	public function key()
	{
		return $this->nextRowNum;
	}
	
	**
	 * Iterator function, moves the index forward
	 *
	public function next()
	{
		$this->loadNextRow();
	}
	
	**
	 * Iterator function, returns true if there are more elements
	 *
	public function valid()
	{
		return !empty($this->currentRow);
	}
	
	**
	 * Loads the next row into memory
	 *
	private function loadNextRow()
	{
		$this->currentRow = $this->_fetch();
		$this->nextRowNum++;
	}
	
	**
	 * Sets the database resource object, and loads first row into memory
	 * @param mixed $result Resource object
	 *
	public function setResult ($result) {
		$this->result = $result;
		$this->loadNextRow();
	}
*/
	
/*
	public function __construct($numRows, $error, $errorMsg) {
		$this->numRows = $numRows;
		$this->error = $error;
		$this->errorMsg = $errorMsg;
		$this->gatherMode = false;
		$this->nextRowNum = 0;
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
			if (isset($this->_data[$this->nextRowNum])) {
				$row = next($this->_data);
			} else {
				$row = $this->_fetch();
				if ($this->gatherMode == true) {
					$this->_data[$this->nextRowNum] = $row;
				}
				$this->nextRowNum++;
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
*/

}
