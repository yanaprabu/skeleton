<?php
#require_once 'A/DataContainer.php';
/**
 * Abstract base class for configuration
 *
 * @package A_Config
 */

abstract class A_Config_Abstract extends A_Collection {
	protected $_filename;
	protected $_section;
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
		$this->_data = ($this->_section ? $data[$this->_section] : $data);
		return $this;
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
	/**
	 * Pass configuration data registered by class name to an object's config() method
	 */
	
	protected function configure($obj) {
		$class = get_class($obj);
		if (($class !== false) && $this->has($class) && method_exists($obj, 'config')) {
			$obj->config($this->get($class));
		}
	}
}
