<?php
/**
 * A_Delimited_Abstract
 *
 * Abstract base class with common read/write functionality for delimited text files
 *
 * @author Christopher Thompson
 * @package A_Delimited
 * @version @package_version@
 */

include_once 'A/Delimited/Config.php';

class A_Delimited_Abstract {
	protected $maxLineLength = 1000;
	protected $filename = null;
	protected $filemode = 'r';
	protected $handle = null;
	protected $rows = array();
	protected $nRows = 0;
	protected $fieldNames = array();
	protected $config;
   
	/**
	 * @param filename - full path to file to be read
	 * @param config - optional configuration object
	 * @type
	 */
	public function __construct($filename=null, $config=null) {
		if ($filename) {
			$this->setFilename($filename);
		}
		if ($config) {
			$this->config = $config;
		} elseif (!$this->config) {
			$this->config = new A_Delimited_config;
		}
	}
 
	/**
	 * @param filename - full path to file to be read
	 * @type this - for fluent interface
	 */
	public function setFilename($filename) {
		$this->filename = realpath($filename);
		return $this;
	}
	
	/**
	 * @param config - configuration object
	 * @type this - for fluent interface
	 */
	public function setConfig($config) {
		if (is_array($config)) {
			foreach (get_class_methods('A_Delimited_Config') as $name) {
				$this->config->$name = $config[$name];
			}
		} else {
			$this->config = $config;
		}
		return $this;
	}
	
	/**
	 * @param filename - full path to file to be read
	 * @type handle to file opened or false
	 */
	protected function open($filename=null) {
		$this->close();
		if ($filename) {
			$this->filename = $filename;
		}
		if (file_exists($this->filename)) {
			$this->handle = fopen($this->filename, $this->filemode);
		}
		return $this->handle;
	}
	
	/**
	 * @param
	 * @type
	 */
	public function close() {
		if ($this->handle && fclose($this->handle)) {
			$this->handle = false;
			$this->reset();
		}
	}
   
	/**
	 * @param
	 * @type
	 */
	public function rewind() {
		if ($this->handle) {
			rewind($this->handle);
		}
#		$this->reset();
	}
   
	/**
	 * @param
	 * @type
	 */
	public function reset() {
		$rows = array();
		$this->nRows = 0;
		$this->fieldNames = array();
		$this->autoConfigured = false;
	}
 
	/**
	 * @param
	 * @type
	 */
	protected function _unescape(&$item, $key, $config) {
		$item = str_replace($config->fieldEscape, '', $item);
	}
   
	/**
	 * @param
	 * @type
	 */
	protected function _escape(&$item, $key, $config) {
		$item = str_replace($config->fieldEnclosure, $config->fieldEscape.$config->fieldEnclosure, $item);
	}
   
}
