<?php
/**
 * Equals.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Equals
 * 
 * Rule to make sure a field matches a given value.  The first parameter is the value to compare to.  The second value defines whether or not the comparison ought to be strict.  The third parameter is the name of the field.  The fourth parameter sets the error message.
 */
class A_Rule_Equals extends A_Rule_Base
{

	const ERROR = 'A_Rule_Equals';
	
	protected $params = array(
		'value' => '',
		'strict' => '',
		'field' => '', 
		'errorMsg' => '', 
		'optional' => false
	);
							
	protected function validate()
	{
	  return $this->params['strict'] ? $this->getValue() === $this->params['value'] : $this->getValue() == $this->params['value'];
	}

}
