<?php
/**
 * Notempty.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Rule_Notempty
 *
 * Rule to check for a value not being empty, null, zero, or false.  Uses the empty() method.
 *
 * @package A_Rule
 */
class A_Rule_Notempty extends A_Rule_Base
{

	const ERROR = 'A_Rule_Notempty';

	protected function validate()
	{
		$value = $this->getValue();
		return !empty($value);
	}

}
