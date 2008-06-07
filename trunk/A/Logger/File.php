<?php

class A_Logger_File {
protected $errmsg = '';

	public function __construct($filename) {
		$this->filename = $filename;
	}
	
	public function write($buffer='') {
		if ($this->filename) {
			$fp = fopen($this->filename, 'a');
			if ($fp) {
				flock($fp, LOCK_EX);
				if (fwrite($fp, $buffer) === false) {
					$this->errmsg .= "Error writing to {$this->filename}. ";
				}
				flock($fp, LOCK_UN);
				fclose($fp);
			} else {
				$this->errmsg .= "Error opening {$this->filename}. ";
			}
		}
	}
}