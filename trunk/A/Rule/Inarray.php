<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check if string is in provided array
 * 
 * @package A_Validator 
 */

class A_Rule_Inarray extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Inarray';
	protected $min;
	protected $max;
	
	public function __construct($field, $array, $errorMsg) {
		$this->field = $field;
		$this->array = $array;
		$this->errorMsg = $errorMsg;
	}
	
	public function isValid($container) {
		return in_array($value, $this->array);
	}
}
