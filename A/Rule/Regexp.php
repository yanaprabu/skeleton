<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for a value matching a provided regular expression
 * 
 * @package A_Validator 
 */

class A_Rule_Regexp extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Regexp';
	protected $regex;

    public function __construct($field, $regexp, $errorMsg) {
		$this->field = $field;
		$this->regexp = $regexp;
		$this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
		return (preg_match($this->regexp, $container->get($this->field)));
	}
}
