<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for a value matching a provided regular expression
 * 
 * @package A_Validator 
 */

class A_Rule_Regexp extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Regexp';

	protected $params = array(
							'regexp' => '', 
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);
							
    protected function validate() {
		return (preg_match($this->params['regexp'], $this->getValue()));
	}
}
