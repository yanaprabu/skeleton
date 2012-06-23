<?php
/**
 * Alnum.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Alnum
 *
 * Filter a string to leave only alpha-numeric characters
 *
 * @package A_Filter
 */
class A_Filter_Alnum extends A_Filter_Base
{

	public function filter()
	{
		return preg_replace('/[^[:alnum:]]/', '', $this->getValue());
	}

}
