<?php
/**
 * Digit.php
 *
 * @package  A_Filter
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Filter_Digit
 * 
 * Filter a string to leave only digits
 */
class A_Filter_Digit extends A_Filter_Base
{

	public function filter()
	{ 
		return preg_replace('/[^[:digit:]]/', '', $this->getValue());
	}

}
