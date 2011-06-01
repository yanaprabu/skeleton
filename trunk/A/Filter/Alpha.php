<?php
#include_once 'A/Filter/Abstract.php';
/**
 * Alpha.php
 *
 * @package  A_Filter
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Filter_Alpha
 * 
 * Filter a string to leave only alpha characters
 */
class A_Filter_Alpha extends A_Filter_Base {
	
	public function filter () {
		return preg_replace('/[^[:alpha:]]/', '', $this->getValue());
	}

}