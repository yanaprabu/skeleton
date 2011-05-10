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
abstract class A_Db_Recordset_Base implements Iterator
{
	// resource object from database adapter
	protected $result = null;
	// number of rows that match query
	protected $numRows = 0;
	// error number (0 = no error)
	protected $_error = 0;
	// error message
	protected $_errorMsg = '';

	// rows fetched from database if gatherMode is on
	protected $_data = array();
	// the position of the next row to fetch
	protected $nextRowNum = 0;
	// the next row to give to current() if gatherMode is off
	protected $currentRow = null;
	// should rows be aggregated as they are fetched?
	protected $gatherMode = false;
	// name of class to create when fetching row
	protected $className = null;
	
	/**
	 * Pass to setClassName() to receive a stdObject
	 * @var string
	 */
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
		$this->_error = $error;
		$this->_errorMsg = $errorMsg;
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
	 * Sets a class to create a row of
	 * @param string $className Name of class to set (optional, default null)
	 * @return this
	 */
	public function setClassName($className = null)
	{
		$this->className = $className;
		return $this;
	}
	
	/**
	 * Gets the number of rows got in query
	 */
	public function numRows()
	{
		return $this->numRows;
	}
	
	/**
	 * Fetches a row from the database, or from the cache if already fetched
	 */
	public function fetchRow()
	{
		if (isset($this->_data[$this->nextRowNum])) {
			$row = $this->_data[$this->nextRowNum];
			$this->currentRow =& $this->_data[$this->nextRowNum];
			++$this->nextRowNum;
		} else {
			if ($this->className == self::OBJECT) {
				$row = $this->_fetch();
				$row = $row ? (object) $row : $row;
			} elseif (!empty($this->className)) {
				$row = new $this->className($this->_fetch());
			} else {
				$row = $this->_fetch();
			}
			if ($this->gatherMode) {
				$this->_data[$this->nextRowNum] = $row;
			} else {
				$this->currentRow = $row;		// save for current()
			}
			++$this->nextRowNum;
		}
		return $row;
	}
	
	/**
	 * Fetches all rows from the database and loads them into memory
	 * @return this
	 */
	public function fetchAll() {
		$this->_data = array();
		while ($row = $this->fetchRow()) {
			echo (bool) $row . "\n";
			$this->_data[] = $row;
		}
		return $this;
	}
	
	/**
	 * Returns if there was an error or not during query
	 */
	public function isError()
	{
		return (bool) $this->_error;
	}
	
	/**
	 * Returns the error message produced by database
	 */
	public function getErrorMsg()
	{
		return $this->_errorMsg;
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

}
