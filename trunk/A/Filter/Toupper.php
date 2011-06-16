<?php
/**
 * Toupper.php
 *
 * @package  A_Filter
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Filter_Toupper
 * 
 * Convert a string to uppercase
 */
class A_Filter_Toupper extends A_Filter_Base
{

	public function filter()
	{
		return strtoupper($this->getValue());
	}

}
