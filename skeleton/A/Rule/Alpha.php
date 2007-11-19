<?php
if (! class_exists('A_Rule_Abstract')) include 'A/Rule/Abstract.php';

class A_Rule_Alpha extends A_Rule_Abstract {

    public function __construct($field, $errorMsg) {
      $this->field    = $field;
      $this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
      return (preg_match("/^[[:alpha:]]+$/", $container->get($this->field)));
    }
}
