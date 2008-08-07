<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for alphanumeric values
 * 
 * @package A_Validator 
 */

class A_Rule_Alnum extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Alnum';

    public function __construct($field, $errorMsg) {
      $this->field    = $field;
      $this->errorMsg = $errorMsg;
    }

    protected function validate() {
      return (preg_match("/^[[:alnum:]]+$/", $this->getValue()));
    }
}
