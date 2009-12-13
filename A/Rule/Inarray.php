<?php
#include_once 'A/Rule/Abstract.php';
/**
 * Rule to check if string is in provided array
 * 
 * @package A_Rule_Set 
 */

class A_Rule_Inarray extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Inarray';
	protected $params = array(
							'array' => array(), 
							'field' => '', 
							'errorMsg' => '', 
							'optional' => false
							);

	protected function validate() {
		return in_array($this->getValue(), $this->params['array']);
	}
}
