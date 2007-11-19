<?php

class A_Rule {
	protected $field;
	protected $errorMsg;

	public function __construct($field, $errorMsg) {
		$this->field    = $field;
		$this->errorMsg = $errorMsg;
	}
	
    public function getErrorMsg() {
		return $this->errorMsg;
    }

    public function isValid($container) {
		trigger_error("A_Rule_::isValid() is abstract!", E_USER_ERROR);
    }
}

class A_Rule_NotNull extends A_Rule {

	public function isValid($container) {
		$value = $container->get($this->field);
		return $value != '';
	}
}

class A_Rule_Regexp extends A_Rule {
	protected $regex;

    public function __construct($field, $regexp, $errorMsg) {
		$this->field = $field;
		$this->regexp = $regexp;
		$this->errorMsg = $errorMsg;
    }

    public function isValid($container) {
		return (preg_match($this->regexp, $container->get($this->field)));
	}
}
