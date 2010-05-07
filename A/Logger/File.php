<?php
/**
 * File writer for the A_Logger class 
 * 
 * @package A_Logger 
 */

class A_Logger_File {
	protected $filename = '';
	protected $errorMsg = '';

	public function __construct($filename) {
		$this->filename = $filename;
	}
	
	public function setFilename($filename) {
		return $this->filename = $filename;
	}
	
	public function getFilename() {
		return $this->filename;
	}
	
	public function getErrorMsg() {
		return $this->errorMsg;
	}
	
	public function write($buffer='') {
		if ($this->filename) {
			$fp = fopen($this->filename, 'a');
			if ($fp) {
				flock($fp, LOCK_EX);
				if (fwrite($fp, $buffer) === false) {
					$this->errorMsg .= "Error writing to {$this->filename}. ";
				}
				flock($fp, LOCK_UN);
				fclose($fp);
			} else {
				$this->errorMsg .= "Error opening {$this->filename}. ";
			}
		}
	}
}