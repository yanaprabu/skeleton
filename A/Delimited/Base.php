<?php
/**
 * Base.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Christopher Thompson
 */

/**
 * A_Delimited_Base
 * 
 * Abstract base class with common read/write functionality for delimited text files
 * 
 * @package A_Delimited
 */
class A_Delimited_Base
{

	protected $maxLineLength = 1000;
	protected $filename = '';
	protected $filemode = 'r';
	protected $handle = null;
	protected $rows = array();
	protected $nRows = 0;
	protected $fieldNames = array();
	protected $_config;
	protected $_errorMsg = '';
	protected $_exception = '';
	
	/**
	 * @param string $filename Full path to file to be read
	 * @param string $config Configuration object
	 */
	public function __construct($filename=null, $config=null)
	{
		if ($filename) {
			$this->setFilename($filename);
		}
		$this->_config = array(
		    'line_delimiter' => "\r\n",
		    'field_delimiter' => "\t",
		    'field_enclosure' => "\"",
		    'field_escape' => "\\",
		    'field_names_in_first_row' => false,
		    'write_all_enclosed' => false,
		);
		if ($config) {
			$this->config($config);
		}
	}
 	
	/**
	 * @param string $filename Full path to file to be read
	 * @return $this
	 */
	public function setFilename($filename)
	{
#		$this->filename = realpath($filename);
		$this->filename = $filename;
#		if (!$this->filename && $filename) {
#			$this->_errorHandler(1, "Error accessing file '$filename'. ");
#		}
		return $this;
	}
	
	/**
	 * @param array $config
	 * @return $this
	 */
	public function config($config)
	{
		if (is_array($config)) {
			foreach ($config as $key => $value) {
		    	$this->_config[$key] = $value;
			}
		}
		return $this;
	}
	
	public function setConfig($config)
	{
		return $this->config($config);
	}
	
	public function setLineDelimiter($value)
	{
		return $this->_config['line_delimiter'] = $value;;
	}
	
	public function setFieldDelimiter($value)
	{
		return $this->_config['field_delimiter'] = $value;;
	}
	
	public function setFieldEnclosure($value)
	{
		return $this->_config['field_enclosure'] = $value;;
	}
	
	public function setFieldEscape($value)
	{
		return $this->_config['field_escape'] = $value;;
	}
	
	public function setFieldNamesInFirstRow($value)
	{
		return $this->_config['field_names_in_first_row'] = $value;;
	}
	
	/**
	 * @param string $filename Full path to file to be read
	 * @return resource
	 */
	protected function open($filename='')
	{
		$this->close();
		if ($filename) {
			$this->filename = $filename;
		}
		if ($this->filename) {
			if (file_exists($this->filename)) {
				$this->handle = fopen($this->filename, $this->filemode);
				if (!$this->handle) {
					$this->_errorHandler(1, "fopen({$this->filename}, '{$this->filemode}') failed. ");
				}
			} else {
				$this->_errorHandler(1, "File '{$this->filename}' does not exist. ");
			}
		} else {
			$this->_errorHandler(1, "No filename. ");
		}
		return $this->handle;
	}
	
	public function close()
	{
		if ($this->handle && fclose($this->handle)) {
			$this->handle = false;
			$this->reset();
		}
	}
	
	public function rewind()
	{
		if ($this->handle) {
			rewind($this->handle);
		}
	}
	
	public function reset()
	{
		$rows = array();
		$this->nRows = 0;
		$this->fieldNames = array();
		$this->autoConfigured = false;
	}
	
	public function isError()
	{
		return $this->_errorMsg != '';
	}
	
	public function getErrorMsg()
	{
		return $this->_errorMsg;
	}
	
	public function _errorHandler($errno, $errorMsg)
	{
		$this->_errorMsg .= $errorMsg;
		if ($this->_exception) {
			throw A_Exception::getInstance($this->_exception, $errorMsg);
		}
	}
	
	/**
	 * @param $item A reference to the value to unescape
	 * @param $key not used
	 * @param $config is a config object
	 */
	protected function _unescape(&$item, $key, $config)
	{
		$item = str_replace($config['field_escape'], '', $item);
	}
	
	/**
	 * @param $item is a reference to the value to escape
	 * @param $key not used
	 * @param $config is a config object
	 */
	protected function _escape(&$item, $key, $config)
	{
		$item = str_replace($config['field_enclosure'], $config['field_escape'].$config['field_enclosure'], $item);
	}

}
