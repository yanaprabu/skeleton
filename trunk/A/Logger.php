<?php
/**
 * Log to file or provided writer object
 * 
 * @package A_Logger 
 */

class A_Logger {
	protected $buffer = '';
	protected $writers = array();
	protected $errorMsg = '';
	
	public function __construct($writers=array()) {
		if ($writers) {
			if (is_array($writers)) {
				foreach($writers as $writer) {
					$this->addWriter($writer);
				}
			} else {
				$this->addWriter($writers);
			}
		}
	}
	
	public function getErrorMsg() {
		return $this->errorMsg;
	}
	
	public function addWriter($writer) {
		if (is_string($writer)) {
			$this->writers[] = new A_Logger_File($writer);
		} elseif (is_object($writer)) {
			$this->writers[] = $writer;
		}
	}
	
	public function log($message) {
		$this->buffer .= date('Y-m-d H:i:s') . "\n$message\n";
	}
	
	public function write($message='') {
		if ($this->writers) {
			if ($message) {
				$this->buffer .= "$message\n";
			}
			foreach ($this->writers as $writer) {	
				$writer->write($this->buffer);
				$this->errorMsg .= $writer->getErrorMsg();
			}
		} else {
			$this->errorMsg .= "No log writer. ";
		}
	}
}

