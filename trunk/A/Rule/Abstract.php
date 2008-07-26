<?php
/**
 * Abstract base class for validation rules 
 * 
 * @package A_Validator 
 */

class A_Rule_Abstract {
	protected $field;
	protected $errorMsg;

	public function __construct($field, $errorMsg) {
		$this->field = $field;
		$this->errorMsg = $errorMsg;
	}
	
    public function setName($field) {
		$this->field = $field;
		return $this;
    }

    public function getName() {
		return $this->field;
    }

    public function setErrorMsg($errorMsg) {
		$this->errorMsg = $errorMsg;
		return $this;
    }

    public function getErrorMsg() {
		return $this->errorMsg;
    }

    public function isValid($container) {
		trigger_error("A_Rule_::isValid() is abstract!", E_USER_ERROR);
    }
}
