<?php
/**
 * Logger.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Log
 *
 * Log to file or provided writer object
 * 
 * @package A
 */
class A_Log
{
    const OFF = 0;
    const EMERGENCY = 1;
    const ALERT = 2;
    const CRITICAL = 3;
    const ERROR = 4;
    const WARNING = 5;
    const NOTICE = 6;
    const INFO = 7;
    const DEBUG = 8;

	protected static $staticData = array();
	protected $data = array();
	protected $levelNames = array(
		'off',
		'emergency',
		'alert',
		'critical',
		'error',
		'warning',
		'notice',
		'info',
		'debug',
	);
	
	/**
	 * @param array $writer Filename (will create A_Log_File($writer)) or array of writer objects
	 * @param int $level Level to log messages
	 */
	public function __construct($writers=array(), $level=0)
	{
		$this->data = self::_initData();
		if ($writers) {
			if (is_array($writers)) {
				foreach($writers as $writer) {
					self::addWriter($writer);
				}
			} else {
				self::addWriter($writers);
			}
		}
		$this->data['level'] = $level;
	}
	
	/**
	 * initialize property array to be assigned to self::staticData or $this->data
	 */
	protected function _initData()
	{
		return array(
			'messages' => array(),
			'levels' => array(),
			'nMessages' => 0,
			'dateFormat' => 'Y-m-d H:i:s',
			'writers' => array(),
			'level' => 0,		// logging level default to always write
			'autoWrite' => true,
			'written' => true,	// whether there are messages that have not been written
			'errorMsg' => '',
		);
	}
		
	/**
	 * @return array containing either self::staticData or $this->data depending on if called statically or as object
	 */
	public function & _getData()
	{
		if (isset($this)) {
			return $this->data;
		} else {
			if (! self::$staticData) {
				self::$staticData = self::_initData();
			}
			return self::$staticData;
		}
	}
	
	/**
	 * @param $writer - either string filename (will create A_Log_File($writer)) or array of writer objects
	 * @return $this
	 */
	public function addWriter($writer=null)
	{
		$data =& self::_getData();
		if (is_string($writer)) {
			$data['writers'][] = new A_Log_File($writer);
		} elseif (is_object($writer)) {
			$data['writers'][] = $writer;
		}
		return isset($this) ? $this : null;
	}
	
	/**
	 * @param $level  - maximum level at or below which messages will be written to log
	 * @return $this
	 */
	public function setLevel($level)
	{
		$data =& self::_getData();
		$data['level'] = $level;
		return isset($this) ? $this : null;
	}
	
	/**
	 * @param $autoWrite  - set whether unwritten log messages arewritten on destruct
	 * @return $this
	 */
	public function setAutoWrite($autoWrite)
	{
		$data =& self::_getData();
		$data['autoWrite'] = $autoWrite;
		return isset($this) ? $this : null;
	}
	
	/**
	 * Returns whether a given level is less than or equal to the current logging level
	 * 
	 * @param $level  - level to log messages
	 * @return boolean if $level <= level property
	 */
	public function isLoggable($level)
	{
		$data =& self::_getData();
		return $level <= $data['level'];
	}
	
	/**
	 * @param string $message String to write to log
	 * @param int $level Level to log messages
	 * @param string $label optional label for message
	 * @return $this
	 */
	public function log($level, $message='', $context=array())
	{
		$data =& self::_getData();

		// deal with ($message) or ($message, $context) parameters
		if (is_string($level)) {
			if (is_array($message)) {
				$context = $message;
			}
			$message = $level;
			$level = $data['level'];
		}
		
		$data['levels'][$data['nMessages']] = $level;
		if (isset($context['exception'])) {
			// deal with exceptions here
		}
		if (is_string($message)) {
			if ($context) {
				foreach ($context as $key => $value) {
					$message = str_replace("\{$key\}", $value);
				}
			}
		} else {
			$message = self::_format($message);
		}
		$data['messages'][$data['nMessages']] = array(date($data['dateFormat']), $message);
		++$data['nMessages'];
		$data['written'] = false;
		return isset($this) ? $this : null;
	}
	
	/**
	 * @param mixed $message variable to format
	 * @return $message
	 */
	public function __call($name, $args)
	{
		if (in_array($name, $this->levelNames)) {
			$level = array_search($name, $this->levelNames);
			$message = isset($args[0]) ? $args[0] : '';
			$context = isset($args[1]) ? $args[1] : array();
			$this->log($level, $message, $context);
		}
	}
	
	/**
	 * @param mixed $message variable to format
	 * @return $message
	 */
	public function _format($message)
	{
		switch (gettype($message)) {
		case 'boolean':
		case 'array':
		case 'object':
		case 'resource':
		case 'NULL':
		case 'unknown type':
			$message = print_r($message, 1);
			break;
		case 'integer':
		case 'double':
		case 'string':
			break;
		}
		return $message;
	}
	
	/**
	 * Remove previous messages from all logs
	 */
	public function clear()
	{
		$data =& self::_getData();
		foreach ($data['writers'] as $writer) {
			$writer->clear();
		}
	}
	
	/**
	 * @param $message - optional message to log
	 * @return $this
	 */
	public function write($level=null)
	{
		$data =& self::_getData();
		if ($level === null) {
			$level = $data['level'];
		}

		$log = array();
		foreach ($data['messages'] as $n => $msg) {
			if ($data['levels'][$n] >= $level) {
				$log[] = $msg;
			}
		}

		if (! $data['writers']) {
			self::addWriter(new A_Log_Echo());		// default writer
		}
		
		if ($data['writers']) {
			foreach ($data['writers'] as $writer) {	
				$writer->write($log);
				$data['errorMsg'] .= $writer->getErrorMsg();
			}
			$data['written'] = true;
		} else {
			$data['errorMsg'] .= "No log writer. ";
		}
	}
	
	/**
	 * Return current error message
	 * 
	 * @return string
	 */
	public function getErrorMsg()
	{
		$data =& self::_getData();
		return $data['errorMsg'];
	}
	
	/**
	 * write data on destruct if autoWrite ON
	 */
	public function __destruct()
	{
		$data =& self::_getData();
		if ($data['autoWrite'] && !$data['written']) {
			self::write();
		}
	}

}
