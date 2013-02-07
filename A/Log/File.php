<?php
/**
 * File.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Log_File
 *
 * File writer for the A_Log class.
 * 
 * @package A_Log
 */
class A_Log_File extends A_Log_Writer
{
	protected $template = "{time} - {msg}\r\n";
	protected $filename = '';
	protected $errorMsg = '';
	
	public function setFilename($filename)
	{
		$this->source = $filename;
		return $this;
	}
	
	public function getFilename()
	{
		return $this->source;
	}
	
	public function clear()
	{
		if ($this->source && file_exists($this->source)) {
 			unlink($this->source);
		}
	}
	
	public function write($log)
	{
		if ($this->source) {
			$fp = fopen($this->source, 'a');
			if ($fp) {
				$buffer = '';
				foreach ($log as $msg) {
					$buffer .= str_replace(array('{time}', '{msg}', '{tag}'), $msg, $this->template);
				}
				flock($fp, LOCK_EX);
				if (fwrite($fp, $buffer) === false) {
					$this->errorMsg .= "Error writing to {$this->source}. ";
				}
				flock($fp, LOCK_UN);
				fclose($fp);
			} else {
				$this->errorMsg .= "Error opening {$this->source}. ";
			}
		}
		return $this;
	}

}
