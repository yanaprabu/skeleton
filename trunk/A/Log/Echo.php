<?php
/**
 * Echo.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Log_Echo
 *
 * Screen output for the A_Log class.
 * 
 * @package A_Log
 */
class A_Log_Echo
{
	protected $template = "<div style=\"clear:both;background:#fff;border:1px solid #ddd;padding:5px;\">{time} {tag}<pre style=\"background:#eee; margin:0px; padding:5px;\">{msg}</pre></div>\r\n";
	protected $errorMsg = '';
	
	public function __construct()
	{
#		$this->option = $option;
	}
	
	/**
	 * @param $template - string containing the {time}, {tag} and {msg} tags for replacement
	 * @return $this
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
		return $this;
	}
	
	public function clear()
	{
	}
	
	public function write($log=array())
	{
		foreach ($log as $entry) {
			echo str_replace(array('{time}', '{msg}', '{tag}'), $entry, $this->template);
		}
		return $this;
	}

	public function getErrorMsg()
	{
		return $this->errorMsg;
	}
	
}
