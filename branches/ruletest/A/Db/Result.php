<?php

class A_Db_Result {
	protected $numRows;
	protected $error;
	protected $errorMsg;
	
	public function __construct($numRows, $error, $errorMsg) {
		$this->numRows = $numRows;
		$this->error = $error;
		$this->errorMsg = $errorMsg;
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
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
}
