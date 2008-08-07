<?php
/**
 * Abstract base class for validation rules 
 * 
 * @package A_Validator 
 */

class A_Rule_Abstract {
	protected $container;
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

	public function getValue($name='') {
		if ($name == '') {
			$name = $this->field;
		}
		if (is_array($this->container)) {
			return $this->container[$name];
		} elseif (is_object($this->container)) {
			return $this->container->get($name);
		} else {
			return $this->container;
		}
	}

	public function setErrorMsg($errorMsg) {
		$this->errorMsg = $errorMsg;
		return $this;
	}

	public function getErrorMsg() {
		return $this->errorMsg;
	}

	public function isValid($container) {
		$this->container = $container;
		return $this->validate($container);
	}

	protected function validate($container=null) {
		trigger_error("A_Rule_::validate() is abstract!", E_USER_ERROR);
	}
}
