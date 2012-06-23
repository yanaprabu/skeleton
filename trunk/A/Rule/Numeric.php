<?php
/**
 * Numeric.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Rule_Numeric
 *
 * Rule to check for a value being a number
 *
 * @package A_Rule
 */
class A_Rule_Numeric extends A_Rule_Base
{

	const ERROR = 'A_Rule_Numeric';

	protected function validate()
	{
		return (is_numeric($this->getValue()));
	}

}
