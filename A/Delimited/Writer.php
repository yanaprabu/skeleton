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

#include_once 'A/Delimited/Abstract.php';

class A_Delimited_Writer extends A_Delimited_Abstract {
	protected $filemode = 'w';
	
	/**
	 * @param row - array of data to be written to line in file
	 * @return mixed - false for error or length of string written
	 */
	public function write($row) {
		if (! $this->handle) {
			$this->open();
		}
		if ($this->handle && $row) {
			if ($this->config->fieldEscape) {
				array_walk($row, array($this, '_unescape'), $this->config);
			}
			return fputcsv($this->handle, $row);
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
		}
		return false;
	}
 
}
