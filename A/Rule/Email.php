<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for email address
 * 
 * @package A_Validator 
 */

class A_Rule_Email extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Email';
	
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
