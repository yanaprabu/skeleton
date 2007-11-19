<?php

class A_Rule_Abstract {
   protected $field;
   protected $errorMsg;

	public function __construct($field, $errorMsg) {
		$this->field = $field;
		$this->errorMsg = $errorMsg;
	}
	
    public function getErrorMsg() {
      return $this->errorMsg;
    }

    public function isValid($container) {
      trigger_error("A_Rule_::isValid() is abstract!", E_USER_ERROR);
    }
}
