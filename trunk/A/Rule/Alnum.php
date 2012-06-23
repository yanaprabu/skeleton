<?php
/**
 * Alnum.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Rule_Alnum
 *
 * Rule to make sure string only contains alphanumeric characters
 *
 * @package A_Rule
 */
class A_Rule_Alnum extends A_Rule_Base
{

	const ERROR = 'A_Rule_Alnum';

	protected function validate()
	{
		return (preg_match("/^[[:alnum:]]+$/", $this->getValue()));
	}

}
