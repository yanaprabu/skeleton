<?php
include_once 'A/Rule/Abstract.php';
/**
 * Rule to check for a value not being ''
 * 
 * @package A_Validator 
 */

class A_Rule_Notnull extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Notnull';
	
	protected function validate() {
		$value = $this->getValue();
		return $value != '';
	}
}
