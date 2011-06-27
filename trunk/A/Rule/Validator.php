<?php
/**
 * Validator.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Validator
 * 
 * This is the interface for Rule objects.
 */
interface A_Rule_Validator
{

	public function isValid($container);
	public function getErrorMsg();

}
