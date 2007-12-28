<?php
include_once 'A/Rule/Abstract.php';

class A_Rule_Numeric extends A_Rule_Abstract {

    public function __construct($field, $errorMsg) {
      $this->field    = $field;
      $this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
      return (is_numeric($container->get($this->field)));
    }
}
