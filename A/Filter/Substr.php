<?php
/**
 * Substr.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Substr
 * 
 * Trim a string to a specified substring
 * 
 * @package A_Filter 
 */
class A_Filter_Substr extends A_Filter_Base
{

	protected $start = 0;
	protected $length = 0;
	
	public function __construct($start, $length)
	{
		$this->start = $start;
		$this->length = $length;
	}
	
	public function filter()
	{
		$value = $this->getValue();
		if ($this->length < strlen($value)) {
			$value = substr($value, $this->start, $this->length);
		}
		return $value;
	}

}
