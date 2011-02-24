<?php
#include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for a value being a number
 * 
 * @package A_Rule_Set 
 */

class A_Rule_Numeric extends A_Rule_Base {
	const ERROR = 'A_Rule_Numeric';
	
    protected function validate() {
      return (is_numeric($this->getValue()));
    }
}
