<?php
/**
 * Alpha.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Alpha
 * 
 * Rule to make sure string only contains alphabetic characters
 */
class A_Rule_Alpha extends A_Rule_Base
{

	const ERROR = 'A_Rule_Alpha';
	
	protected function validate()
	{
		return (preg_match("/^[[:alpha:]]+$/", $this->getValue()));
	}

}
