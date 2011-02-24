<?php
#include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for alphabetic values
 * 
 * @package A_Rule_Set 
 */

class A_Rule_Alpha extends A_Rule_Base {
	const ERROR = 'A_Rule_Alpha';

    protected function validate() {
      return (preg_match("/^[[:alpha:]]+$/", $this->getValue()));
    }
}
