<?php
/**
 * Writer.php
 *
 * @package  A_Delimited
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Christopher Thompson
 */

/**
 * A_Delimited_Writer
 *
 * Write arrays to a delimited text file.
 */
class A_Delimited_Writer extends A_Delimited_Base
{

	protected $filemode = 'wb';
	
	/**
	 * @param array $value Data to be written to line in file
	 * @return $this
	 */
	public function setWriteAllEnclosed($value)
	{
		$this->_config['write_all_enclosed'] = $value;
		return $this;
	}
	
	/**
	 * @param array $row Data to be written to line in file
	 * @return bool|int Length of data written or false on error
	 */
	public function write($row)
	{
		if (!$this->handle) {
			$this->open();
		}
		if ($this->handle && $row) {
			if ($this->_config['field_escape']) {
				array_walk($row, array($this, '_unescape'), $this->_config);
			}
			if ($this->_config['write_all_enclosed']) {
				$result = fputs($this->handle, $this->_config['field_enclosure'] . implode($this->_config['field_enclosure'].$this->_config['field_delimiter'].$this->_config['field_enclosure'], $row) . $this->_config['field_enclosure'] . $this->_config['line_delimiter']);
			} else {
				$result = fputcsv($this->handle, $row, $this->_config['field_delimiter'], $this->_config['field_enclosure']);
			}
			if ($result == false) {
				$this->errorMsg .= "Error writing row: " . $this->_config['field_enclosure'] . implode($this->_config['field_enclosure'].$this->_config['field_delimiter'].$this->_config['field_enclosure'], $row) . $this->_config['field_enclosure'] . '. ';
			}
			return $result;
		} else {
			$this->_errorHandler(1, "No row data to write. ");
		}
		return false;
	}
	
	/**
	 * @param array $rows Lines to be written to file
	 * @return bool|int Length of data written or false on error
	 */
	public function save($rows=null)
	{
		if (!$rows) {
			$rows = $this->rows;
		}
		if ($rows) {
			$this->nRows = 0;
			foreach ($rows as $row) {
				if ($this->write($row) !== false) {
					$this->nRows++;
				} else {
					return false;
				}
			}
			return $this->nRows;
		} else {
			$this->_errorHandler(1, 'No rows to save. ');
		}
		return false;
	}

}
