<?php
/**
 * Logger.php
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Logger
 *
 * Log to file or provided writer object
 */
class A_Logger
{

	protected $buffer = '';
	protected $template = "{datetime} - {message}\n";
	protected $writers = array();
	protected $level = 0;		// logging level default to always write
	protected $autoWrite = true;
	protected $written = true;	// whether there are messages that have not been written
	protected $errorMsg = '';
	
	/**
	 * @param array $writer Filename (will create A_Logger_File($writer)) or array of writer objects
	 * @param int $level Level to log messages
	 */
	public function __construct($writers=array(), $level=0)
	{
		if ($writers) {
			if (is_array($writers)) {
				foreach($writers as $writer) {
					$this->addWriter($writer);
				}
			} else {
				$this->addWriter($writers);
			}
		}
		$this->level = $level;
	}
	
	/**
	 * @param $writer - filename (will create A_Logger_File($writer)) or array of writer objects
	 * @return $this
	 */
	public function addWriter($writer)
	{
		if (is_string($writer)) {
			$this->writers[] = new A_Logger_File($writer);
		} elseif (is_object($writer)) {
			$this->writers[] = $writer;
		}
		return $this;
	}
	
	/**
	 * @param $template - string containing the {datetime} and {message} tags for replacement
	 * @return $this
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
		return $this;
	}
	
	/**
	 * @param $level  - maximum level at or below which messages will be written to log
	 * @return $this
	 */
	public function setLevel($level)
	{
		$this->level = $level;
		return $this;
	}
	
	/**
	 * @param $autoWrite  - set whether unwritten log messages arewritten on destruct
	 * @return $this
	 */
	public function setAutoWrite($autoWrite)
	{
		$this->autoWrite = $autoWrite;
		return $this;
	}
	
	/**
	 * Returns whether a given level is less than or equal to the current logging level
	 * 
	 * @param $level  - level to log messages
	 */
	public function isLoggable($level)
	{
		return $level <= $this->level;
	}
	
	/**
	 * @param string $message String to write to log
	 * @param int $level Level to log messages
	 * @return $this
	 */
	public function log($message, $level=0)
	{
		$this->levels[$this->nMessages] = $level;
		$this->messages[$this->nMessages] = str_replace(array('{datetime}', '{message}') , array(date('Y-m-d H:i:s'), $message), $this->template);
		$this->nMessages++;
		$this->written = false;
		return $this;
	}
	
	/**
	 * Remove previous messages from all logs
	 */
	public function clear()
	{
		foreach ($this->writers as $writer) {
			$writer->clear();
		}
	}
	
	/**
	 * @param $message - optional message to log
	 * @return $this
	 */
	public function write($level=null)
	{
		if ($this->writers) {
			if ($level !== null) {
				$this->level = $level;
			}
			$buffer = '';
			foreach ($this->messages as $n => $msg) {
				if ($this->isLoggable($this->levels[$n])) {
					$buffer .= $msg;
				}
			}
			foreach ($this->writers as $writer) {	
				$writer->write($buffer);
				$this->errorMsg .= $writer->getErrorMsg();
			}
			$this->written = true;
		} else {
			$this->errorMsg .= "No log writer. ";
		}
		return $this;
	}
	
	/**
	 * Return current error message
	 * 
	 * @return string
	 */
	public function getErrorMsg()
	{
		return $this->errorMsg;
	}
	
	/**
	 * Return current error message
	 */
	public function __destruct()
	{
		if ($this->autoWrite && !$this->written) {
			$this->write();
		}
	}

}
