<?php
/**
 * Writer.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Log_Writer
 *
 * File writer for the A_Log class.
 * 
 * @package A_Log
 */
class A_Log_Writer
{
	protected $source = '';
	protected $level = null;
	protected $template = "{time} - {msg}\r\n";
	protected $errorMsg = '';
	
	public function __construct($source, $level=null)
	{
		$this->source = $source;
		$this->level = $level;
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
	 * @param $level  - maximum level at or below which messages will be written to log
	 * @return $this
	 */
	public function getLevel($level)
	{
		return $this->level;
	}
	
	public function clear()
	{
		if ($this->source) {
 			unset($this->source);
		}
	}
	
	public function write($log, $level=null)
	{
		if ($this->source) {
		} else {
			$this->errorMsg .= "No source. ";
		}
		return $this;
	}

	public function isError()
	{
		return $this->errorMsg != '';
	}
	
	public function getErrorMsg()
	{
		return $this->errorMsg;
	}
	
}
