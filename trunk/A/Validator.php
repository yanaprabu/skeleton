<?php
include_once 'A/Rule/Set.php';
/**
 * Run one or more Rules on a container
 * 
 * @package A_Validator 
 */

class A_Validator extends A_Rule_Set {

	public function validate ($container) {
		return $this->isValid($container);
	}
	
	public function isError() {
		return ! empty($this->errorMsg);
	}

}



