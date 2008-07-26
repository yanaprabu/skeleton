<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for values with only digits
 * 
 * @package A_Validator 
 */

class A_Rule_Digit extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Digit';

    public function __construct($field, $errorMsg) {
      $this->field    = $field;
      $this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
      return (preg_match("/^[[:digit:]]+$/", $container->get($this->field)));
    }
}
