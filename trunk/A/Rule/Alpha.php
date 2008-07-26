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

    public function isValid($container) {
      return (preg_match("/^[[:alpha:]]+$/", $container->get($this->field)));
    }
}
