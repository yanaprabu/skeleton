<?php
/**
 * A_Delimited_Writer
 *
 * Write arrays to a delimited text file
 *
 * @author Christopher Thompson
 * @package A_Delimited
 * @version @package_version@
 */

class A_Delimited_Writer extends A_Delimited_Base {
	protected $filemode = 'wb';
	
	/**
	 * @param $value - array of data to be written to line in file
	 * @return mixed - false for error or length of string written
	 */
	public function setWriteAllEnclosed($value) {
		$this->_config['write_all_enclosed'] = $value;
		return $this;
	}
	
	/**
	 * @param row - array of data to be written to line in file
	 * @return mixed - false for error or length of string written
	 */
	public function write($row) {
		if (! $this->handle) {
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
	 * @param rows - array of line arrays to be written to file
	 * @return mixed - false for error or number of lines written
	 */
	public function save($rows=null) {
		if (! $rows) {
			$rows = $this->rows;
		}
		if ($rows) {
			$this->nRows = 0;
			foreach ($rows as $row) {
				if ($this->write($row) !== false) {
					++$this->nRows;
				} else {
					return false;
				}
			}
			return $this->nRows;
		} else {
			$this->_errorHandler(1, "No rows to save. ");
		}
		return false;
	}
 
}
