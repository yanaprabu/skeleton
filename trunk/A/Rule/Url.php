<?php
/**
 * Url.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Rule_url
 *
 * Rule to make sure string contains valid URL
 *
 * @package A_Rule
 */
class A_Rule_Url extends A_Rule_Base
{

	const ERROR = 'A_Rule_Url';

	protected function validate()
	{
		if(filter_var($this->getValue(), FILTER_VALIDATE_URL) === false){
			return false;
		} else {
			return true;
		}
	}

}
