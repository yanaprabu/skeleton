<?php
/**
 * File.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Logger_File
 *
 * File writer for the A_Logger class.
 *
 * @package A_Logger
 */
class A_Logger_File
{

	protected $filename = '';
	protected $errorMsg = '';

	public function __construct($filename)
	{
		$this->filename = $filename;
	}

	public function setFilename($filename)
	{
		$this->filename = $filename;
		return $this;
	}

	public function getFilename()
	{
		return $this->filename;
	}

	public function getErrorMsg()
	{
		return $this->errorMsg;
	}

	public function clear()
	{
		if ($this->filename && file_exists($this->filename)) {
 			unlink($this->filename);
		}
	}

	public function write($buffer='')
	{
		if ($this->filename) {
			$fp = fopen($this->filename, 'a');
			if ($fp) {
				flock($fp, LOCK_EX);
				if (fwrite($fp, $buffer) === false) {
					$this->errorMsg .= "Error writing to {$this->filename}. ";
				}
				flock($fp, LOCK_UN);
				fclose($fp);
			} else {
				$this->errorMsg .= "Error opening {$this->filename}. ";
			}
		}
		return $this;
	}

}
