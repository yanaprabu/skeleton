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

    protected function validate() {
      return (preg_match("/^[[:digit:]]+$/", $this->getValue()));
    }
}
