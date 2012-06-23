<?php
/**
 * Alpha.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Alpha
 *
 * Filter a string to leave only alpha characters
 *
 * @package A_Filter
 */
class A_Filter_Alpha extends A_Filter_Base
{

	public function filter()
	{
		return preg_replace('/[^[:alpha:]]/', '', $this->getValue());
	}

}
