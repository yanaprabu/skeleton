<?php
if (! class_exists('A_Rule_Abstract')) include 'A/Rule/Abstract.php';

class A_Rule_Date extends A_Rule_Abstract {

    public function __construct($field, $errorMsg) {
      $this->field    = $field;
      $this->errorMsg = $errorMsg;
    }

    public function getErrorMsg() {
      return $this->errorMsg;
    }

    public function isValid($container) {
      return (preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/", $container->get($this->field),
              $matches) && checkdate($matches[2], $matches[3], $matches[1]));
    }
}
