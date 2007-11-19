<?php

class A_Logger {
	protected $buffer = '';
	protected $writer = null;
	protected $errmsg = '';
	
	public function __construct($writer) {
		if (is_string($writer)) {
			if (!class_exists('A_Logger_File')) include 'A/Logger/File.php';
			$this->writer = new A_Logger_File($writer);
		} elseif (is_object($writer)) {
			$this->writer = $writer;
		}
	}
	
	public function log($message) {
		$this->buffer .= date('Y-m-d H:i:s') . "\n$message\n";
	}
	
	public function write($message='') {
		if ($this->writer) {
			if ($message) {
				$this->buffer .= "$message\n";
			}
			$this->writer->write($this->buffer);
			$this->errmsg .= $this->writer->errmsg;
		} else {
			$this->errmsg .= "No log writer. ";
		}
	}
}

