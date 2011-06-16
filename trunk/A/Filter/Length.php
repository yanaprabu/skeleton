<?php
/**
 * Length.php
 *
 * @package  A_Filter
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Filter_Length
 * 
 * Trim value to specified length.
 */
class A_Filter_Length extends A_Filter_Base
{

	protected $length = 0;
	
	public function __construct($length)
	{
		$this->length = $length;
	}
	
	public function filter()
	{
		if ($this->length < strlen($this->getValue())) {
			$value = substr($this->getValue(), 0, $this->length);
		}
		return $value;
	}

}
