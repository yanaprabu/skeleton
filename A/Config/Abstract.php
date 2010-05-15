<?php
#require_once 'A/DataContainer.php';
/**
 * Abstract base class for configuration
 *
 * @package A_Config
 */

abstract class A_Config_Abstract {
	protected $_filename;
	protected $_section;
	protected $_collectionClass = 'A_Config_Collection';
	protected $_exception;
	protected $_error = 0;
	protected $_errorMsg = '';
	
	/**
	 *
	 */
	public function __construct($filename, $section='', $exception=false) {
		$this->_filename = $filename;
		$this->_section = $section;
		$this->_exception = $exception;
	}
	
	/**
	 *
	 */
	public function setCollectionClass($collection_class) {
		$this->_collectionClass = $collection_class;
		return $this;
	}

	/**
	 *
	 */
	public function loadFile() {
		set_error_handler(array($this, 'errorHandler'));
		$data = $this->_loadFile();
		restore_error_handler();
	  
		//if there was a problem loading the file
		if (($this->_error || !count($data))
		//if the requested section does not exist
		|| ($this->_section && !isset($data[$this->_section]))) {
			return false;
		}
	
		return new $this->_collectionClass ($this->_section ? $data[$this->_section] : $data);
	}
	
	/**
	 *
	 */
	public function errorHandler($errno, $errstr, $errfile, $errline) {
		$this->_error = $errno;
		$this->_errorMsg = $errstr;
		if ($this->_exception) {
			throw A_Exception::getInstance($this->_exception, $errstr);
		}
	}
	
	/**
	 *
	 */
	public function isError() {
		return $this->_error;
	}
	
	/**
	 *
	 */
	public function getErrorMsg() {
		return $this->_errorMsg;
	}
}
