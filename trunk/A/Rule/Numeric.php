<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for a value being a number
 * 
 * @package A_Validator 
 */

class A_Rule_Numeric extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Numeric';
	
    public function __construct($field, $errorMsg) {
      $this->field    = $field;
      $this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
      return (is_numeric($container->get($this->field)));
    }
}
