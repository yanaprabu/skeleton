<?php
/**
 * Toupper.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Toupper
 *
 * Convert a string to uppercase
 *
 * @package A_Filter
 */
class A_Filter_Toupper extends A_Filter_Base
{

	public function filter()
	{
		return strtoupper($this->getValue());
	}

}
