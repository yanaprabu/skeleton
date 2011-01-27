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
class A_Logger {
	protected $buffer = '';
	protected $template = "{datetime} - {message}\n";
	protected $writers = array();
	protected $level = 0;		// logging level default to always write
	protected $errorMsg = '';
	
	/**
	 * @param $writer - filename (will create A_Logger_File($writer)) or array of writer objects
	 * @param $level  - level to log messages
	 */
	public function __construct($writers=array(), $level=0) {
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
	 */
	public function addWriter($writer) {
		if (is_string($writer)) {
			$this->writers[] = new A_Logger_File($writer);
		} elseif (is_object($writer)) {
			$this->writers[] = $writer;
		}
		return $this;
	}
	
	/**
	 * @param $template - string containing the {datetime} and {message} tags for replacement
	 */
	public function setTemplate($template) {
		$this->template = $template;
		return $this;
	}
	
	/**
	 * @param $level  - level to write messages
	 */
	public function setLevel($level) {
		$this->level = $level;
		return $this;
	}
	
	/**
	 * Returns whether to log at given level
	 * 
	 * @param $level  - level to log messages
	 */
	public function isLoggable($level) {
		return $level <= $this->level;
	}
	
	/**
	 * @param $message - string to write to log
	 * @param $level   - level to log messages
	 */
	public function log($message, $level=0) {
		$this->levels[$this->nMessages] = $level;
		$this->messages[$this->nMessages] = str_replace(array('{datetime}', '{message}') , array(date('Y-m-d H:i:s'), $message), $this->template);
		++$this->nMessages;
		return $this;
	}
	
	/**
	 * Remove previous messages from all logs
	 */
	public function clear() {
		foreach ($this->writers as $writer) {
			$writer->clear();
		}
	}
	
	/**
	 * @param $message - optional message to log
	 */
	public function write($level=null) {
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
		} else {
			$this->errorMsg .= "No log writer. ";
		}
		return $this;
	}

	/**
	 * Return current error message
	 */
	public function getErrorMsg() {
		return $this->errorMsg;
	}
	
}

