<?php
if (! class_exists('A_Rule_Abstract')) include 'A/Rule/Abstract.php';

class A_Rule_Match extends A_Rule_Abstract {
   protected $refField;

    public function __construct($field, $refField, $errorMsg) {
      $this->field    = $field;
      $this->refField = $refField;
      $this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
      return (strcmp($container->get($this->field), $container->get($this->refField)) == 0);
    }
}
