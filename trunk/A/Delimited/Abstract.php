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

#include_once 'A/Delimited/Config.php';

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
	 */
	public function __construct($filename=null, $config=null) {
		if ($filename) {
			$this->setFilename($filename);
		}
		if ($config) {
			$this->config = $config;
		} elseif (!$this->config) {
			$this->config = new A_Delimited_Config;
		}
	}
 
	/**
	 * @param filename - full path to file to be read
	 * @return this - for fluent interface
	 */
	public function setFilename($filename) {
		$this->filename = realpath($filename);
		return $this;
	}
	
	/**
	 * @param config - configuration object
	 * @return this - for fluent interface
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
	 * @return handle to file opened or false
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
	 * @return
	 */
	public function close() {
		if ($this->handle && fclose($this->handle)) {
			$this->handle = false;
			$this->reset();
		}
	}
   
	/**
	 * @return
	 */
	public function rewind() {
		if ($this->handle) {
			rewind($this->handle);
		}
#		$this->reset();
	}
   
	/**
	 * @return
	 */
	public function reset() {
		$rows = array();
		$this->nRows = 0;
		$this->fieldNames = array();
		$this->autoConfigured = false;
	}
 
	/**
	 * @param $item is a reference to the value to unescape
	 * @param $key not used
	 * @param $config is a config object
	 * @return
	 */
	protected function _unescape(&$item, $key, $config) {
		$item = str_replace($config->fieldEscape, '', $item);
	}
   
	/**
	 * @param $item is a reference to the value to escape
	 * @param $key not used
	 * @param $config is a config object
	 * @return
	 */
	protected function _escape(&$item, $key, $config) {
		$item = str_replace($config->fieldEnclosure, $config->fieldEscape.$config->fieldEnclosure, $item);
	}
   
}
