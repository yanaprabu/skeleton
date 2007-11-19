<?php
if (! class_exists('A_Rule_Abstract')) include 'A/Rule/Abstract.php';

class A_Rule_Email extends A_Rule_Abstract {

    public function __construct($field, $errorMsg) {
      $this->field    = $field;
      $this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
      $user      = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\|\{\}~\']+';
      $doIsValid = '(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9]\.?)+';
      $ipv4      = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
      $ipv6      = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';

      return (preg_match("/^$user@($doIsValid|(\[($ipv4|$ipv6)\]))$/", $container->get($this->field)));
    }
}
