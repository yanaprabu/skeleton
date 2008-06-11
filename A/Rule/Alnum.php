<?php
include_once 'A/Rule/Abstract.php';

class A_Rule_Alnum extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Alnum';

    public function __construct($field, $errorMsg) {
      $this->field    = $field;
      $this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
      return (preg_match("/^[[:alnum:]]+$/", $container->get($this->field)));
    }
}
