<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for alphabetic values
 * 
 * @package A_Validator 
 */

class A_Rule_Alpha extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Alpha';

    public function __construct($field, $errorMsg) {
      $this->field    = $field;
      $this->errorMsg = $errorMsg;
    }

    protected function validate() {
      return (preg_match("/^[[:alpha:]]+$/", $this->getValue()));
    }
}
