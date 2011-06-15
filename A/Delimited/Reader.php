<?php
/**
 * Reader.php
 *
 * @package  A_Delimited
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Christopher Thompson
 */

/**
 * A_Delimited_Reader
 * 
 * Read delimited text file into array of line arrays
 */
class A_Delimited_Reader extends A_Delimited_Base
{

	protected $filemode = 'rb';
	protected $autoConfigure = false;
	protected $isAutoConfigured = false;
	
	/**
	 * @param bool $auto True to check file before reading to determine delimiter
	 * @return $this
	 */
	public function setAutoConfigure($auto=true)
	{
		$this->autoConfigure = $auto;
		return $this;
	}
	
	/**
	 * @return array Lines read
	 */
	public function read()
	{
		if (!$this->handle) {
			$this->open();
		}
		if ($this->handle) {
			if ($this->autoConfigure && !$this->isAutoConfigured) {
				$this->autoConfig();
			}
			if ($this->_config['field_names_in_first_row'] && !$this->fieldNames) {
				$this->fieldNames = fgetcsv($this->handle, $this->maxLineLength, $this->_config['field_delimiter'], $this->_config['field_enclosure']);
			}
			$row = fgetcsv($this->handle, $this->maxLineLength, $this->_config['field_delimiter'], $this->_config['field_enclosure']);
			if ($row && is_array($row)) {
				// not a blank line
				if ($row[0]) {
					// strip escaping
					if ($this->_config['field_escape']) {
						array_walk($row, array($this, '_escape'), $this->_config);
					}
					return $row;
				}
			}
		}
		return array();
	}
	
	public function load($lines=0)
	{
		if (!$this->handle) {
			$this->open();
		}
		$this->nRows = 0;
		$rows = array();
		if ($this->handle) {
			while (($row = $this->read())) {
				$rows[$this->nRows++] = $row;
				if (($lines > 0) && ($lines <= $this->nRows)) {
					break;
				}
			}
		}
		return $rows;
	}
	
	public function autoConfig()
	{
		if (!$this->handle) {
			$this->open();
		}
		$line = fgets($this->handle, $this->maxLineLength);
		foreach (array("\t", ',', ';') as $delimiter) {
			if (substr_count($line, $delimiter)) {
				$this->_config['field_delimiter'] = $delimiter;
				break;
			}
		}
		$this->isAutoConfigured = true;
		rewind($this->handle);
	}

}
