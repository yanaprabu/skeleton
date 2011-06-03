<?php
/**
 * Notnull.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Notnull
 * 
 * Rule to check for a value not being empty, null, or false
 */
class A_Rule_Notnull extends A_Rule_Base {
	const ERROR = 'A_Rule_Notnull';
	
	protected function validate() {
		$value = $this->getValue();
		return ($value !== null) && ($value !== '');
	}
}
